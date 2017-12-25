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
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-here-woocommerce-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-here-woocommerce-admin.js', array('jquery'), $this->version, false);
        wp_localize_script(
				$this->plugin_name,
				'woocommerce_admin_api_keys',
				array(
					'ajax_url'         => admin_url( 'admin-ajax.php' ),
					'update_api_nonce' => wp_create_nonce( 'update-api-key' ),
                                        'user' => get_current_user_id(),
					'clipboard_failed' => esc_html__( 'Copying to clipboard failed. Please press Ctrl/Cmd+C to copy.', 'woocommerce' ),
				)
			);
    }
    
    public function angelleye_paypal_here_add_payment_method($payment_method) {
        $payment_method[] = 'Paypal_Here_Woocommerce_Payment';
        return $payment_method;
    }

}
