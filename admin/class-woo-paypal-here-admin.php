<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Paypal_Here_Woocommerce
 * @subpackage Paypal_Here_Woocommerce/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class Paypal_Here_Woocommerce_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    public $paypal_here_settings = array();
    public $paypal_here_endpoint_url;
    public $home_url;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->paypal_here_endpoint_url = !empty($this->paypal_here_settings['paypal_here_endpoint_url']) ? $this->paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-paypal-here-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-paypal-here-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'woocommerce_admin_api_keys', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'update_api_nonce' => wp_create_nonce('update-api-key'),
            'user' => get_current_user_id(),
            'paypal_here_url' => $this->home_url . $this->paypal_here_endpoint_url
                )
        );
    }

    public function angelleye_paypal_here_add_payment_method($payment_method) {
        $payment_method[] = 'Paypal_Here_Woocommerce_Payment';
        return $payment_method;
    }

    public function angelleye_paypal_here_add_meta_box() {
        add_meta_box('angelleye_admin_paypal_here_metabox', __('PayPal Here', 'woo-paypal-here'), array($this, 'angelleye_paypal_here_metabox'), 'shop_order', 'side', 'default');
    }

    public function angelleye_paypal_here_metabox($post) {
        $order_id = $post->ID;
        $qrcode_order_url = add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url);
        $url = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=" . urlencode($qrcode_order_url) . "&chld=H|O";
        echo "<div style='text-align:center;'><img src='$url'></div>";
    }
    
    public function paypal_here_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
                'configure' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=checkout&section=angelleye_paypal_here' ), __( 'Configure', 'woo-paypal-here' ) ),
                'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', 'http://www.angelleye.com/category/docs/woo-paypal-here/?utm_source=paypal_here_woocommerce&utm_medium=docs_link&utm_campaign=paypal_here_woocommerce', __( 'Docs', 'woo-paypal-here' ) ),
                'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/plugin/woo-paypal-here/', __( 'Support', 'woo-paypal-here' ) ),
                'review'    => sprintf( '<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/view/plugin-reviews/woo-paypal-here', __( 'Write a Review', 'woo-paypal-here' ) ),
            );
        return array_merge( $custom_actions, $actions );
    }

}
