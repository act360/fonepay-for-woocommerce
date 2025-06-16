<?php

defined('ABSPATH') || exit;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * WooCommerce_Foneday_Data Class.
 */
class WooCommerce_Fonepay_Data
{
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    public function __construct()
    {
        add_action(
            'add_meta_boxes',
            array($this, 'add_custom_meta_boxes'),
            10,
            1
        );
    }

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function add_custom_meta_boxes()
    {
        $screen = (class_exists('\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController')
            && wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled())
            ? wc_get_page_screen_id('shop-order')
            : 'shop_order';

        add_meta_box(
            'mv_other_fields',
            'Payment Info',
            array($this, 'fonepay_info'),
            $screen,
            'side',
            'core'
        );
    }

    public function fonepay_info($post)
    {
        $order = ($post instanceof WP_Post) ? wc_get_order($post->ID) : $post;

        $meta_info = '<p>Payment Method: <strong>Fonepay </strong>';
        $meta_info .= '<p>Txn ID: <strong>' . $order->get_meta('_transaction_id') . '</strong>';

        echo wp_kses_post($meta_info);
    }
}
