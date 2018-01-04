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
        if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {
            foreach ($wp_styles->registered as $handle => $data) {
                //wp_deregister_style($handle);
                //wp_dequeue_style($handle);
            }
            wp_enqueue_style($this->plugin_name . 'bootstrap_css', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css', array(), $this->version, 'all');
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
        if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {

            if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {
                wp_enqueue_script($this->plugin_name . 'popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array('jquery'), $this->version, false);
                wp_enqueue_script($this->plugin_name . 'bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js', array('jquery'), $this->version, false);
            }
            wp_enqueue_script($this->plugin_name . 'input_button', plugin_dir_url(__FILE__) . 'js/bootstrap-number-input.js', array('jquery'), $this->version, true);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-here-woocommerce-public.js', array('jquery', $this->plugin_name . 'input_button'), $this->version, true);
        }
    }

    public function is_angelleye_paypal_here_endpoint_url($title = false) {
        global $wp;
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($wp->query_vars)) {
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_url']) && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == $this->paypal_here_settings['paypal_here_endpoint_url'] && strpos($title, 'not found') !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function paypal_here_quick_view() {
        require_once PAYPAL_HERE_PLUGIN_DIR . '/includes/class-paypal-here-woocommerce-quick-view.php';
        $quick_view_obj = new Paypal_Here_Woocommerce_Quick_View();
        $quick_view_obj->quick_view();
    }

    public function angelleye_paypal_here_woocommerce_locate_template($template, $template_name, $template_path) {
        global $woocommerce;

        $_template = $template;

        if (!$template_path)
            $template_path = $woocommerce->template_url;

        $plugin_path = PAYPAL_HERE_PLUGIN_DIR . '/templates/';

        // Look within passed path within the theme - this is priority
        $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
        );

        // Modification: Get the template from this plugin, if it exists
        if (!$template && file_exists($plugin_path . $template_name))
            $template = $plugin_path . $template_name;

        // Use default template
        if (!$template)
            $template = $_template;

        // Return what we found
        return $template;
    }

}
