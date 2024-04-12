<?php

/**
 * Plugin Name: Integrate Fonepay in WooCommerce
 * Plugin URI: https://github.com/act360/fonepay-for-woocommerce
 * Description: Integrate Fonepay in WooCommerce is a plugin that enables Fonepay payment in WooCommerce for Nepal.
 * Version: 1.2.0
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * Author: ACT360
 * Author URI: https://www.act360.com.np
 * Text Domain: woocommerce-fonepay
 * Domain Path: /languages
 *
 * WC requires at least: 4.5.0
 * WC tested up to: 8.7
 *
 * @package Fonepay-WooCommerce
 */

defined('ABSPATH') || exit;

add_action(
    'before_woocommerce_init',
    function () {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                __FILE__,
                true
            );
        }
    }
);

// Define WC_FONEPAY_PLUGIN_FILE.
if (!defined('WC_FONEPAY_PLUGIN_FILE')) {
    define('WC_FONEPAY_PLUGIN_FILE', __FILE__);
}

// Include the main WooCommerce Fonepay class.
if (!class_exists('WooCommerce_Fonepay')) {
    include_once dirname(__FILE__) . '/includes/class-woocommerce-fonepay.php';
    include_once dirname(__FILE__) . '/includes/class-woocommerce-fonepay-data.php';
}

// Initialize the plugin.
add_action('plugins_loaded', array('WooCommerce_Fonepay', 'get_instance'));
add_action('plugins_loaded', array('WooCommerce_Fonepay_Data', 'get_instance'));
