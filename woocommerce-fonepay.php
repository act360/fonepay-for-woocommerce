<?php
/**
 * Plugin Name: Integrate Fonepay in WooCommerce
 * Plugin URI: https://github.com/act360/fonepay-for-woocommerce
 * Description: Integrate Fonepay in WooCommerce is a plugin that enables Fonepay payment in WooCommerce for Nepal.
 * Version: 1.1.1
 * Author: ACT360
 * Author URI: https://act360.com.np
 * Text Domain: woocommerce-fonepay
 * Domain Path: /languages
 *
 * WC requires at least: 4.3.0
 * WC tested up to: 5.8.2
 *
 * @package Fonepay-WooCommerce
 */

defined('ABSPATH') || exit;

// Define WC_FONEPAY_PLUGIN_FILE.
if (! defined('WC_FONEPAY_PLUGIN_FILE')) {
    define('WC_FONEPAY_PLUGIN_FILE', __FILE__);
}

// Include the main WooCommerce Fonepay class.
if (! class_exists('WooCommerce_Fonepay')) {
    include_once dirname(__FILE__) . '/includes/class-woocommerce-fonepay.php';
}

// Initialize the plugin.
add_action('plugins_loaded', array( 'WooCommerce_Fonepay', 'get_instance' ));
