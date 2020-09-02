<?php
/**
 * Handles responses from Fonepay IPN.
 *
 * @package WooCommerce_Fonepay\Classes\Payment
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/class-wc-gateway-fonepay-response.php';

/**
 * WC_Gateway_Fonepay_IPN_Handler class.
 */
class WC_Gateway_Fonepay_IPN_Handler extends WC_Gateway_Fonepay_Response {

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_Fonepay $gateway Gateway class.
	 */
	public function __construct( $gateway ) {
		add_action( 'woocommerce_api_wc_gateway_fonepay', array( $this, 'check_response' ) );
		add_action( 'valid-fonepay-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->gateway = $gateway;
	}

	/**
	 * Check for Fonepay IPN Response.
	 */
	public function check_response() {
		if ( ! empty( $_REQUEST ) && isset( $_REQUEST['PRN'] ) && isset( $_REQUEST['BID'] ) && isset( $_REQUEST['UID'] ) ) { // WPCS: CSRF ok.
			WC_Gateway_Fonepay::log( 'IPN Response: ' . wc_print_r( $_REQUEST, true ) );
			
			$requested = wp_unslash( $_REQUEST ); // WPCS: CSRF ok, input var ok.
			
			if ( $verification_response = $this->validate_ipn( $requested ) ) {
				// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
				do_action( 'valid-fonepay-standard-ipn-request', $verification_response );
				exit;
			}
			wp_die( 'Fonepay Verification Failure', 'Fonepay IPN', array( 'response' => 500 ) );
		}

		WC_Gateway_Fonepay::log( 'PRN/BID/UID missing in response' );

		wp_die( 'Fonepay Request Failure', 'Fonepay IPN', array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response.
	 *
	 * @param array    $response 	Verification response data
	 */
	public function valid_response( $response ) {
		$order = $this->get_fonepay_order( $_REQUEST['PRN'] );

		if ( $order ) {
			WC_Gateway_Fonepay::log( 'Found order #' . $order->get_id() );

			// Lowercase returned variables.
			$payment_status = strtolower( $response['response_code'] );
			$payment_status = $payment_status == 'successful' ? 'completed' : 'failed';

			WC_Gateway_Fonepay::log( 'Payment status: ' . $payment_status );

			if ( method_exists( $this, "payment_status_$payment_status" ) ) {
				call_user_func( array( $this, "payment_status_$payment_status" ), $order, $response );
				wp_safe_redirect( esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ) );
				exit;
			}
		}

	}

	/**
	 * Check Fonepay IPN validity.
	 */
	public function validate_ipn( $requested ) {
		WC_Gateway_Fonepay::log( 'Validating IPN response' );

		$order = $this->get_fonepay_order( $requested['PRN'] );

		if (! $order) {
			WC_Gateway_Fonepay::log( 'Order not found using order_key from PRN' );
			return false;
		}

		$request_data = [
			'PID' => $this->gateway->merchant_code,
			'AMT' => $order->get_total(),
			'PRN' => wc_clean( wp_unslash( $requested['PRN'] ) ),
			'BID' => wc_clean( wp_unslash( $requested['BID'] ) ),
			'UID' => wc_clean( wp_unslash( $requested['UID'] ) ),
		];
		$request_data['DV'] = hash_hmac( 
			'sha512', 
			$this->gateway->merchant_code.','.
			$request_data['AMT'].','.
			$request_data['PRN'].','.
			$request_data['BID'].','.
			$request_data['UID'], 
			$this->gateway->merchant_secret 
		);

		WC_Gateway_Fonepay::log( 'Sending validation request' );

		//Payment verification URL
		$verification_url = $this->gateway->testmode ? 'https://dev-clientapi.fonepay.com/api/merchantRequest/verificationMerchant' : 'https://clientapi.fonepay.com/api/merchantRequest/verificationMerchant';
		
		// Post back to get a response.
		$response = wp_safe_remote_get( $verification_url.'?'.http_build_query( $request_data ) );
		$body = wp_remote_retrieve_body( $response );
		$response_obj = get_object_vars( simplexml_load_string( $body ) );

		WC_Gateway_Fonepay::log( 'IPN verification response: ' . wc_print_r( $response_obj, true ) );


		// Check to see if the request was valid.
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			WC_Gateway_Fonepay::log( 'Received valid response from Fonepay IPN' );
			return $response_obj;
		}

		WC_Gateway_Fonepay::log( 'Received invalid response from Fonepay IPN' );

		if ( is_wp_error( $response ) ) {
			WC_Gateway_Fonepay::log( 'Error response: ' . $response->get_error_message() );
		}

		return false;
	}

	/**
	 * Handle a completed payment.
	 *
	 * @param WC_Order $order     	Order object.
	 * @param array    $response 	Verification response data.
	 */
	protected function payment_status_completed( $order, $response ) {
		if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
			WC_Gateway_Fonepay::log( 'Aborting, Order #' . $order->get_id() . ' is already complete.' );
			exit;
		}

		if ( $order->has_status( 'cancelled' ) ) {
			$this->payment_status_paid_cancelled_order( $order );
		}

		$this->payment_complete( $order, wc_clean( $response['uniqueId'] ), __( 'IPN payment completed', 'woocommerce-fonepay' ) );

		// Log Fonepay Reference Code.
		if ( ! empty( $requested['uniqueId'] ) ) {
			update_post_meta( $order->get_id(), 'Fonepay Trace Id (Trace ID) ', wc_clean( $response['uniqueId'] ) );
		}
	}

	/**
	 * Handle a failed payment.
	 *
	 * @param WC_Order $order     	Order object.
	 * @param array    $response 	Verification response data.
	 */
	protected function payment_status_failed( $order, $response ) {
		/* translators: %s: payment status */
		$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce-fonepay' ), wc_clean( $response['http_response_code(code)'] ) ) );
	}

	/**
	 * When a user cancelled order is marked paid.
	 *
	 * @param WC_Order $order 	Order object.
	 */
	protected function payment_status_paid_cancelled_order( $order ) {
		if ( version_compare( WC_VERSION, '3.3.0', '>' ) ) {
			$this->send_ipn_email_notification(
				/* translators: %s: order link. */
				sprintf( __( 'Payment for cancelled order %s received', 'woocommerce-fonepay' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %s: order ID. */
				sprintf( __( 'Order #%s has been marked paid by Fonepay IPN, but was previously cancelled. Admin handling required.', 'woocommerce-fonepay' ), $order->get_order_number() )
			);
		}
	}

	/**
	 * Send a notification to the user handling orders.
	 *
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 */
	protected function send_ipn_email_notification( $subject, $message ) {
		$new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
		$mailer             = WC()->mailer();
		$message            = $mailer->wrap_message( $subject, $message );

		$woocommerce_fonepay_settings = get_option( 'woocommerce_fonepay_settings' );
		if ( ! empty( $woocommerce_fonepay_settings['ipn_notification'] ) && 'no' === $woocommerce_fonepay_settings['ipn_notification'] ) {
			return;
		}

		$mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), strip_tags( $subject ), $message );
	}
}
