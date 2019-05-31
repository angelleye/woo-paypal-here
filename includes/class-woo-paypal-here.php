<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_PayPal_Here
 * @subpackage Woo_PayPal_Here/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class Woo_PayPal_Here {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woo_PayPal_Here_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('WOO_PAYPAL_HERE_VERSION')) {
            $this->version = WOO_PAYPAL_HERE_VERSION;
        } else {
            $this->version = '0.5.0';
        }
        $this->plugin_name = 'woo-paypal-here';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woo_PayPal_Here_Loader. Orchestrates the hooks of the plugin.
     * - Woo_PayPal_Here_i18n. Defines internationalization functionality.
     * - Woo_PayPal_Here_Admin. Defines all hooks for the admin area.
     * - Woo_PayPal_Here_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-paypal-here-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-functions.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woo-paypal-here-public.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-payment.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-navwalker.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-here-checkout.php';



        $this->loader = new Woo_PayPal_Here_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woo_PayPal_Here_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Woo_PayPal_Here_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Woo_PayPal_Here_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_filter('woocommerce_payment_gateways', $plugin_admin, 'angelleye_woo_paypal_here_add_payment_method');
        if (is_admin() && !defined('DOING_AJAX')) {
            $this->loader->add_action('add_meta_boxes', $plugin_admin, 'angelleye_woo_paypal_here_add_meta_box', 10);
        }
        $basename = WOO_PAYPAL_HERE_PLUGIN_BASENAME;
        $prefix = is_network_admin() ? 'network_admin_' : '';
        $this->loader->add_filter("{$prefix}plugin_action_links_$basename", $plugin_admin, 'paypal_here_action_links', 10, 4);
        $this->loader->add_action('wp_ajax_angelleye_paypal_here_adismiss_notice', $plugin_admin, 'angelleye_paypal_here_adismiss_notice', 10);
        $this->loader->add_action('admin_notices', $plugin_admin, 'angelleye_paypal_here_display_push_notification', 10);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Woo_PayPal_Here_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('init', $plugin_public, 'paypal_here_register_session', 1);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 99999);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_filter('woocommerce_locate_template', $plugin_public, 'angelleye_woo_paypal_here_locate_template', 10, 3);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_get_modal_body', $plugin_public, 'paypal_here_get_modal_body', 10);
        $this->loader->add_action('wp_ajax_paypal_here_get_modal_body', $plugin_public, 'paypal_here_get_modal_body', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_add_to_cart', $plugin_public, 'paypal_here_add_to_cart', 10);
        $this->loader->add_action('wp_ajax_paypal_here_add_to_cart', $plugin_public, 'paypal_here_add_to_cart', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_apply_coupon', $plugin_public, 'paypal_here_apply_coupon', 10);
        $this->loader->add_action('wp_ajax_paypal_here_apply_coupon', $plugin_public, 'paypal_here_apply_coupon', 10);
        $this->loader->add_action('wp_ajax_send_to_paypal_here_action', $plugin_public, 'send_to_paypal_here_action', 10);
        $this->loader->add_action('wp_ajax_nopriv_send_to_paypal_here_action', $plugin_public, 'send_to_paypal_here_action', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_apply_shipping', $plugin_public, 'paypal_here_apply_shipping', 10);
        $this->loader->add_action('wp_ajax_paypal_here_apply_shipping', $plugin_public, 'paypal_here_apply_shipping', 10);
        $this->loader->add_action('wp_ajax_paypal_here_apply_tax', $plugin_public, 'paypal_here_apply_tax', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_apply_tax', $plugin_public, 'paypal_here_apply_tax', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_delete_order_item', $plugin_public, 'paypal_here_paypal_here_delete_order_item', 10);
        $this->loader->add_action('wp_ajax_paypal_here_delete_order_item', $plugin_public, 'paypal_here_paypal_here_delete_order_item', 10);
        $this->loader->add_action('wp_ajax_nopriv_paypal_here_delete_order', $plugin_public, 'paypal_here_paypal_here_delete_order', 10);
        $this->loader->add_action('wp_ajax_paypal_here_delete_order', $plugin_public, 'paypal_here_paypal_here_delete_order', 10);
        $this->loader->add_filter('woocommerce_available_payment_gateways', $plugin_public, 'woo_paypal_here_available_payment_gateways', 10, 1);

        $payment_object = new Woo_PayPal_Here_Payment();

        $this->loader->add_action('init', $payment_object, 'paypal_here_call_back_handler', 10);
    }

    //

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woo_PayPal_Here_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
