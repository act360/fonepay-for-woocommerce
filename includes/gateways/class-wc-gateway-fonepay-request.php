<?php
/**
 * Generates requests to send to Fonepay
 *
 * @package WooCommerce_Fonepay\Classes\Payment
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Gateway_Fonepay_Request class.
 */
class WC_Gateway_Fonepay_Request {

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_Fonepay
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from Fonepay.
	 *
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Endpoint for requests to Fonepay.
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_Fonepay $gateway Gateway class.
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'WC_Gateway_Fonepay' );
	}

	/**
	 * Get the Fonepay request URL for an order.
	 *
	 * @param  WC_Order $order   Order object.
	 * @param  bool     $sandbox Use sandbox or not.
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$this->endpoint = $sandbox ? 'https://dev-clientapi.fonepay.com/api/merchantRequest' : 'https://clientapi.fonepay.com/api/merchantRequest';
		$fonepay_args     = $this->get_fonepay_args( $order, $sandbox );

		WC_Gateway_Fonepay::log( 'Fonepay Request Args for order ' . $order->get_order_number() . ': ' . wc_print_r( $fonepay_args, true ) );
		$redirect_to = $this->endpoint.
		'?PID='.$fonepay_args['merchant_code'].
		'&MD=P'.
		'&AMT='.$fonepay_args['amount'].
		'&CRN='.$fonepay_args['currency'].
		'&DT='.urlencode($fonepay_args['date']).
		'&R1='.$fonepay_args['remark_1'].
		'&R2='.urlencode($fonepay_args['remark_2']).
		'&DV='.$fonepay_args['dv'].
		'&PRN='.$fonepay_args['prod_ref_number'].
		'&RU='.urlencode($fonepay_args['return_url']);

		return $redirect_to;
	}

	/**
	 * Limit length of an arg.
	 *
	 * @param  string  $string Argument to limit.
	 * @param  integer $limit Limit size in characters.
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		$str_limit = $limit - 3;
		if ( function_exists( 'mb_strimwidth' ) ) {
			if ( mb_strlen( $string ) > $limit ) {
				$string = mb_strimwidth( $string, 0, $str_limit ) . '...';
			}
		} else {
			if ( strlen( $string ) > $limit ) {
				$string = substr( $string, 0, $str_limit ) . '...';
			}
		}
		return $string;
	}

	/**
	 * Get Fonepay Args for passing to Fonepay.
	 *
	 * @param  WC_Order $order Order object.
	 * @param  $sandbox boolean.
	 * @return array
	 */
	protected function get_fonepay_args( $order, $sandbox ) {
		WC_Gateway_Fonepay::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		$order->set_order_key( wc_generate_order_key() );
		$order->save();
		
		$args = array(
			'merchant_code' => $this->gateway->merchant_code,
			'prod_ref_number' => $order->get_order_key(),
			'amount' => $order->get_total(),
			'currency' => get_woocommerce_currency(),
			'date' => date( 'm/d/Y' ),
			'remark_1' => $this->get_payment_remark( $order ),
			'remark_2' => get_site_url(),
			'return_url' => $this->notify_url,
		);

		$args['dv'] = hash_hmac(
			'sha512',
			$this->gateway->merchant_code.','.
			'P,'.
			$args['prod_ref_number'].','.
			$args['amount'].','.
			$args['currency'].','.
			$args['date'].','.
			$args['remark_1'].','.
			$args['remark_2'].','.
			$this->notify_url,
			$this->gateway->merchant_secret
		);

		return $args;
	}

	/**
	 * Get remark using product names for Fonepay request.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_payment_remark( $order ) {
		$items = $order->get_items();
		$product_names = array();
		foreach ( $items as $item ) {
			$product = wc_get_product( $item['product_id'] );
			array_push( $product_names, preg_replace( '/[^a-zA-Z0-9_ -]/s', ' ', $product->get_name() ) );
		}

		$remark = implode( ',', $product_names );

		return $this->limit_length( $remark, 150 );
	}
}
