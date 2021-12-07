<?php

/**
 * WooCommerce Fonepay setup
 *
 * @package WooCommerce_Fonepay
 */

defined('ABSPATH') || exit;

/**
 * Main WooCommerce Fonepay Class.
 *
 * @class WooCommerce_Fonepay
 */
final class WooCommerce_Fonepay
{

    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.1.0';

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin.
     */
    private function __construct()
    {
        // Load plugin text domain.
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Checks with WooCommerce is installed.
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.0', '>=')) {
            $this->includes();

            // Hooks.
            add_filter('woocommerce_payment_gateways', array($this, 'add_gateway'));
            add_filter('plugin_action_links_' . plugin_basename(WC_FONEPAY_PLUGIN_FILE), array($this, 'plugin_action_links'));
        } else {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
        }
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

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     *
     * Locales found in:
     *      - WP_LANG_DIR/integrate-fonepay-in-woocommerce/woocommerce-fonepay-LOCALE.mo
     *      - WP_LANG_DIR/plugins/woocommerce-fonepay-LOCALE.mo
     */
    public function load_plugin_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'woocommerce-fonepay');

        load_textdomain('woocommerce-fonepay', WP_LANG_DIR . '/integrate-fonepay-in-woocommerce/woocommerce-fonepay-' . $locale . '.mo');
        load_plugin_textdomain('woocommerce-fonepay', false, plugin_basename(dirname(WC_FONEPAY_PLUGIN_FILE)) . '/languages');
    }

    /**
     * Includes.
     */
    private function includes()
    {
        include_once dirname(WC_FONEPAY_PLUGIN_FILE) . '/includes/class-wc-gateway-fonepay.php';
    }

    /**
     * Add the gateway to WooCommerce.
     *
     * @param  array $methods WooCommerce payment methods.
     * @return array          Payment methods with Fonepay.
     */
    public function add_gateway($methods)
    {
        $methods[] = 'WC_Gateway_Fonepay';
        return $methods;
    }

    /**
     * Display action links in the Plugins list table.
     *
     * @param  array $actions Plugin Action links.
     * @return array
     */
    public function plugin_action_links($actions)
    {
        $new_actions = array(
            'settings' => '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=fonepay') . '" aria-label="' . esc_attr(__('View WooCommerce Fonepay settings', 'woocommerce-fonepay')) . '">' . __('Settings', 'woocommerce-fonepay') . '</a>',
        );

        return array_merge($new_actions, $actions);
    }

    /**
     * WooCommerce fallback notice.
     */
    public function woocommerce_missing_notice()
    {
        /* translators: %s: woocommerce version */
        echo '<div class="error notice is-dismissible"><p>' . sprintf(esc_html__('WooCommerce Fonepay depends on the version of %s or later to work!', 'woocommerce-fonepay'), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . esc_html__('WooCommerce 3.0', 'woocommerce-fonepay') . '</a>') . '</p></div>';
    }
}
