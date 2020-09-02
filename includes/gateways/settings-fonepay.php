<?php
/**
 * Payment Gateway: Settings - Fonepay.
 *
 * @package WooCommerce_Fonepay\Classes\Payment
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings for Fonepay Gateway.
 */
return array(
	'enabled'              => array(
		'title'   => __( 'Enable/Disable', 'woocommerce-fonepay' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Fonepay Payment', 'woocommerce-fonepay' ),
		'default' => 'yes',
	),
	'title'                => array(
		'title'       => __( 'Title', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-fonepay' ),
		'default'     => __( 'Fonepay', 'woocommerce-fonepay' ),
	),
	'description'          => array(
		'title'       => __( 'Description', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-fonepay' ),
		'default'     => __( 'Pay via Fonepay; you can pay with Fonepay securely.', 'woocommerce-fonepay' ),
	),
	'merchant_code'         => array(
		'title'       => __( 'Live Merchant Code', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your live Fonepay Merchant Code; this is needed in order to take payment.', 'woocommerce-fonepay' ),
		'default'     => '',
	),
	'merchant_secret'         => array(
		'title'       => __( 'Live Merchant Secret', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your Live Fonepay Merchant Secret; this is needed in order to take payment.', 'woocommerce-fonepay' ),
		'default'     => '',
	),
	'sandbox_merchant_code' => array(
		'title'       => __( 'Test Merchant code', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your test Fonepay Merchant Code; this is needed in order to test payment.', 'woocommerce-fonepay' ),
		'default'     => '',
	),
	'sandbox_merchant_secret' => array(
		'title'       => __( 'Test Merchant Secret', 'woocommerce-fonepay' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your test Fonepay Merchant Secret; this is needed in order to test payment.', 'woocommerce-fonepay' ),
		'default'     => '',
	),
	'advanced'             => array(
		'title'       => __( 'Advanced options', 'woocommerce-fonepay' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'             => array(
		'title'       => __( 'Sandbox mode', 'woocommerce-fonepay' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Sandbox Mode', 'woocommerce-fonepay' ),
		'default'     => 'no',
		/* translators: %s: Fonepay contact page */
		'description' => sprintf( __( 'Enable Fonepay sandbox to test payments. Please contact Fonepay Merchant/Service Provider for a <a href="%s" target="_blank">developer account</a>.', 'woocommerce-fonepay' ), 'https://www.fonepay.com/contact' ),
	),
	'debug'                => array(
		'title'       => __( 'Debug log', 'woocommerce-fonepay' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce-fonepay' ),
		'default'     => 'no',
		/* translators: %s: Fonepay log file path */
		'description' => sprintf( __( 'Log Fonepay events, such as IPN requests, inside <code>%s</code>', 'woocommerce-fonepay' ), wc_get_log_file_path( 'fonepay' ) ),
	),
	'ipn_notification'     => array(
		'title'       => __( 'IPN Email Notifications', 'woocommerce-fonepay' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable IPN email notifications', 'woocommerce-fonepay' ),
		'default'     => 'yes',
		'description' => __( 'Send notifications when an IPN is received from Fonepay indicating cancellations.', 'woocommerce-fonepay' ),
	),
);
