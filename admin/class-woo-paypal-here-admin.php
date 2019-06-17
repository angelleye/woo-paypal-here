<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_PayPal_Here
 * @subpackage Woo_PayPal_Here/admin
 * @author     Angell EYE <service@angelleye.com>
 */
class Woo_PayPal_Here_Admin {

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
        $this->paypal_here_settings = get_option('woocommerce_angelleye_woo_paypal_here_settings');
        $this->paypal_here_endpoint_url = !empty($this->paypal_here_settings['paypal_here_endpoint_url']) ? $this->paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
        add_action('admin_notices', array( $this, 'angelleye_woo_paypal_here_admin_notice'), 10);
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

    public function angelleye_woo_paypal_here_add_payment_method($payment_method) {
        $payment_method[] = 'Woo_PayPal_Here_Payment';
        return $payment_method;
    }

    public function angelleye_woo_paypal_here_add_meta_box() {
        global $post;
        if( !empty($post->post_status) && $post->post_status == 'wc-pending' ) {
            add_meta_box('angelleye_admin_paypal_here_metabox', __('PayPal Here', 'woo-paypal-here'), array($this, 'angelleye_woo_paypal_here_metabox'), 'shop_order', 'side', 'default');
        }
    }

    public function angelleye_woo_paypal_here_metabox($post) {
        $order_id = $post->ID;
        $qrcode_order_url = add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url);
        $url = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=" . urlencode($qrcode_order_url) . "&chld=H|O";
        echo "<div style='text-align:center;'><img src='$url'></div>";
    }

    public function paypal_here_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=checkout&section=angelleye_woo_paypal_here'), __('Configure', 'woo-paypal-here')),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://www.angelleye.com/category/docs/paypal-here-for-woocommerce/?utm_source=woo_paypal_here&utm_medium=docs_link&utm_campaign=woo_paypal_here', __('Docs', 'woo-paypal-here')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/plugin/woo-paypal-here/', __('Support', 'woo-paypal-here')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'http://wordpress.org/support/view/plugin-reviews/woo-paypal-here', __('Write a Review', 'woo-paypal-here')),
        );
        return array_merge($custom_actions, $actions);
    }
    
    public function angelleye_woo_paypal_here_admin_notice() {
        if (empty($this->paypal_here_settings['generate_woocommerce_rest_api_key_value'])) {
            echo "<div class='notice notice-error angelleye_paypal_here_notice'><p>" . sprintf(__('Your API keys for WooCommerce are not configured. Please click the %s button in the PayPal Here settings to fix this.', 'woo-paypal-here'), '<a target="_self" href="'.get_admin_url().'admin.php?page=wc-settings&tab=checkout&section=angelleye_woo_paypal_here#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_push_button">Generate WooCommerce REST API Key</a>') . "</p></div>";
        }
    }
    
    public function angelleye_paypal_here_display_push_notification() {
        global $current_user;
        $user_id = $current_user->ID;
        if (false === ( $response = get_transient('angelleye_push_notification_result') )) {
            $response = $this->angelleye_get_push_notifications();
            if(is_object($response)) {
                set_transient('angelleye_push_notification_result', $response, 12 * HOUR_IN_SECONDS);
            }
        }
        if (is_object($response)) {
            foreach ($response->data as $key => $response_data) {
                if (!get_user_meta($user_id, $response_data->id)) {
                    $this->angelleye_display_push_notification($response_data);
                }
            }
        }
    }

    public function angelleye_get_push_notifications() {
        $args = array(
            'plugin_name' => 'woo-paypal-here',
        );
        $api_url = PAYPAL_FOR_WOOCOMMERCE_PUSH_NOTIFICATION_WEB_URL . '?Wordpress_Plugin_Notification_Sender';
        $api_url .= '&action=angelleye_get_plugin_notification';
        $request = wp_remote_post($api_url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array('user-agent' => 'AngellEYE'),
            'body' => $args,
            'cookies' => array(),
            'sslverify' => false
        ));
        if (is_wp_error($request) or wp_remote_retrieve_response_code($request) != 200) {
            return false;
        }
        if ($request != '') {
            $response = json_decode(wp_remote_retrieve_body($request));
        } else {
            $response = false;
        }
        return $response;
    }

    public function angelleye_display_push_notification($response_data) {
        echo '<div class="notice notice-success angelleye-notice" style="display:none;" id="'.$response_data->id.'">'
        . '<div class="angelleye-notice-logo-push"><span> <img src="' . $response_data->ans_company_logo . '"> </span></div>'
        . '<div class="angelleye-notice-message">'
        . '<h3>' . $response_data->ans_message_title . '</h3>'
        . '<div class="angelleye-notice-message-inner">'
        . '<p>' . $response_data->ans_message_description . '</p>'
        . '<div class="angelleye-notice-action"><a target="_blank" href="' . $response_data->ans_button_url . '" class="button button-primary">' . $response_data->ans_button_label . '</a></div>'
        . '</div>'
        . '</div>'
        . '<div class="angelleye-notice-cta">'
        . '<button class="angelleye-notice-dismiss angelleye-dismiss-welcome" data-msg="' . $response_data->id . '">Dismiss</button>'
        . '</div>'
        . '</div>';
    }
    
    public function angelleye_paypal_here_adismiss_notice() {
        global $current_user;
        $user_id = $current_user->ID;
        if (!empty($_POST['action']) && $_POST['action'] == 'angelleye_paypal_here_adismiss_notice') {
            add_user_meta($user_id, wc_clean($_POST['data']), 'true', true);
            wp_send_json_success();
        }
    }
    

}
