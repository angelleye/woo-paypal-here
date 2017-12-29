<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Paypal_Here_Woocommerce
 * @subpackage Paypal_Here_Woocommerce/public
 * @author     Angell EYE <service@angelleye.com>
 */
class Paypal_Here_Woocommerce_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        global $wp_query, $wp, $wp_styles;
        $wp->query_vars;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && is_page() == false) {
            foreach ($wp_styles->registered as $handle => $data) {
                wp_deregister_style($handle);
                wp_dequeue_style($handle);
            }
            wp_enqueue_style($this->plugin_name . 'bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-here-woocommerce-public.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        global $wp_query, $wp;
        $wp->query_vars;
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-here-woocommerce-public.js', array('jquery'), $this->version, false);
        if (!is_null($wp_query) && !is_admin() && is_main_query() && is_page() == false) {
            wp_enqueue_script($this->plugin_name . 'bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js', array('jquery'), $this->version, false);
        }
    }

}
