<?php

/**
 * @since      1.0.0
 * @package    Woo_PayPal_Here
 * @subpackage Woo_PayPal_Here/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class Woo_PayPal_Here_End_Point {

    public $paypal_here_settings = array();
    public $here_rest_api;
    public $result;
    public $order_list = array();
    public $product_list;
    public $order;

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        add_action('init', array($this, 'angelleye_paypal_here_add_endpoints'), 0);
        add_filter('template_include', array($this, 'template_loader'), 0, 1);
        add_filter('the_title', array($this, 'angelleye_paypal_here_page_endpoint_title'), 10, 1);
        add_filter('wp_title', array($this, 'angelleye_paypal_here_page_endpoint_wp_title'), 0, 1);
        add_action('wp_ajax_angelleye_paypal_here_woocommerce_update_api_key', array($this, 'angelleye_paypal_here_update_api_key'));
        add_action('wp_ajax_angelleye_paypal_here_revoke_key', array($this, 'angelleye_paypal_here_revoke_key'));
        add_action('angelleye_paypal_here_before_body_content', array($this, 'angelleye_paypal_here_before_body_content'), 5);
        add_action('angelleye_paypal_here_dashboard_body_content', array($this, 'angelleye_paypal_here_dashboard_body_content'), 5);
        add_action('angelleye_paypal_here_view_products_body_content', array($this, 'angelleye_paypal_here_view_products_body_content'), 5);
        add_action('angelleye_paypal_here_view_pending_orders_body_content', array($this, 'angelleye_paypal_here_view_pending_orders_body_content'), 5);
        add_action('angelleye_paypal_here_view_order_billing_body_content', array($this, 'angelleye_paypal_here_view_order_billing_body_content'), 5);
        add_action('angelleye_paypal_here_view_order_shipping_body_content', array($this, 'angelleye_paypal_here_view_order_shipping_body_content'), 5);
        add_action('template_redirect', array($this, 'angelleye_paypal_here_handle_submit_action'), 20);
        add_action('angelleye_paypal_here_view_pending_orders_details_body_content', array($this, 'angelleye_paypal_here_view_pending_orders_details_body_content'), 10);
        add_action('wp_ajax_nopriv_paypal_here_get_copon_code', array($this, 'paypal_here_get_copon_code'), 10);
        add_action('wp_ajax_paypal_here_get_copon_code', array($this, 'paypal_here_get_copon_code'), 10);
        if (!is_admin()) {
            add_filter('query_vars', array($this, 'angelleye_paypal_here_add_query_vars'), 0);
            add_action('parse_request', array($this, 'angelleye_paypal_here_parse_request'), 0);
        }
        try {
            require WOO_WOO_PAYPAL_HERE_PLUGIN_DIR . '/includes/class-woo-paypal-here-rest-api.php';
            if (class_exists('Woo_PayPal_Here_Rest_API')) {
                $this->here_rest_api = new Woo_PayPal_Here_Rest_API();
            }
        } catch (HttpClientException $ex) {
            return $ex->getMessage();
        }
        $this->home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->paypal_here_endpoint_url = !empty($this->paypal_here_settings['paypal_here_endpoint_url']) ? $this->paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
    }

    public function angelleye_paypal_here_parse_request() {
        global $wp;
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($this->paypal_here_settings['paypal_here_endpoint_url'])) {
            if (isset($_GET[$this->paypal_here_settings['paypal_here_endpoint_url']])) {
                $wp->query_vars[] = $_GET[$this->paypal_here_settings['paypal_here_endpoint_url']];
            }
        }
    }

    public function angelleye_paypal_here_add_query_vars($vars) {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($this->paypal_here_settings['paypal_here_endpoint_url'])) {
            $vars[] = $this->paypal_here_settings['paypal_here_endpoint_url'];
        }
        return $vars;
    }

    public function angelleye_paypal_here_page_endpoint_wp_title($title) {
        global $wp_query, $wp;
        $wp->query_vars;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && $this->is_angelleye_paypal_here_endpoint_url($title)) {
            add_filter('show_admin_bar', '__return_false');
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_title'])) {
                return $this->paypal_here_settings['paypal_here_endpoint_title'] . ' | ';
            }
            remove_filter('the_title', 'angelleye_paypal_here_page_endpoint_wp_title');
        }
        return $title;
    }

    public function angelleye_paypal_here_page_endpoint_title($title) {
        global $wp_query, $wp;
        $wp->query_vars;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && $this->is_angelleye_paypal_here_endpoint_url($title)) {
            add_filter('show_admin_bar', '__return_false');
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_title'])) {
                return $this->paypal_here_settings['paypal_here_endpoint_title'];
            }
            remove_filter('the_title', 'angelleye_paypal_here_page_endpoint_title');
        }
        return $title;
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

    public function angelleye_paypal_here_add_endpoints() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($this->paypal_here_settings['paypal_here_endpoint_url'])) {
            add_rewrite_endpoint($this->paypal_here_settings['paypal_here_endpoint_url'], EP_PAGES | EP_PERMALINK);
        }
    }

    public function template_loader($template) {
        global $wp;
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($wp->query_vars)) {
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_url']) && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == $this->paypal_here_settings['paypal_here_endpoint_url']) {
                $template = $this->plugin_path() . '/templates/' . 'page.php';
            }
        }
        return $template;
    }

    public function plugin_path() {
        return untrailingslashit(plugin_dir_path(WOO_PAYPAL_HERE_PLUGIN_FILE));
    }

    public function angelleye_paypal_here_revoke_key() {
        global $wpdb;
        if (empty($this->paypal_here_settings)) {
            $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        }
        ob_start();
        check_ajax_referer('update-api-key', 'security');
        if (!current_user_can('manage_woocommerce')) {
            wp_die(-1);
        }
        $this->angelleye_paypal_here_remove_key($this->paypal_here_settings['key_id']);
        unset($this->paypal_here_settings['generate_woocommerce_rest_api_key_value']);
        unset($this->paypal_here_settings['uniq_cs']);
        unset($this->paypal_here_settings['key_id']);
        update_option('woocommerce_angelleye_paypal_here_settings', $this->paypal_here_settings);
        $data['message'] = 'API key revoked successfully.';
        wp_send_json_success($data);
    }

    public function angelleye_paypal_here_update_api_key() {
        if (empty($this->paypal_here_settings)) {
            $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        }
        ob_start();
        global $wpdb;
        check_ajax_referer('update-api-key', 'security');
        if (!current_user_can('manage_woocommerce')) {
            wp_die(-1);
        }
        try {
            if (empty($_POST['description'])) {
                throw new Exception(__('Description is missing.', 'woocommerce'));
            }
            if (empty($_POST['user'])) {
                throw new Exception(__('User is missing.', 'woocommerce'));
            }
            if (empty($_POST['permissions'])) {
                throw new Exception(__('Permissions is missing.', 'woocommerce'));
            }
            $key_id = absint($_POST['key_id']);
            $description = sanitize_text_field(wp_unslash($_POST['description']));
            $permissions = ( in_array($_POST['permissions'], array('read', 'write', 'read_write')) ) ? sanitize_text_field($_POST['permissions']) : 'read';
            $user_id = absint($_POST['user']);
            if (0 < $key_id) {
                $data = array(
                    'user_id' => $user_id,
                    'description' => $description,
                    'permissions' => $permissions,
                );
                $wpdb->update(
                        $wpdb->prefix . 'woocommerce_api_keys', $data, array('key_id' => $key_id), array(
                    '%d',
                    '%s',
                    '%s',
                        ), array('%d')
                );
                $data['consumer_key'] = '';
                $data['consumer_secret'] = '';
                $data['message'] = __('API Key updated successfully.', 'woocommerce');
            } else {
                $consumer_key = 'ck_' . wc_rand_hash();
                $consumer_secret = 'cs_' . wc_rand_hash();
                $data = array(
                    'user_id' => $user_id,
                    'description' => $description,
                    'permissions' => $permissions,
                    'consumer_key' => wc_api_hash($consumer_key),
                    'consumer_secret' => $consumer_secret,
                    'truncated_key' => substr($consumer_key, -7),
                );
                $wpdb->insert(
                        $wpdb->prefix . 'woocommerce_api_keys', $data, array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                        )
                );
                $this->paypal_here_settings['generate_woocommerce_rest_api_key_value'] = '...' . substr($consumer_key, -7);
                $this->paypal_here_settings['uniq_cs'] = str_replace('cs_', '', $consumer_secret);
                $this->paypal_here_settings['uniq_ck'] = str_replace('ck_', '', $consumer_key);
                if (!empty($_POST['enabled']) && 'true' == $_POST['enabled']) {
                    $this->paypal_here_settings['enabled'] = 'yes';
                }
                $this->paypal_here_settings['key_id'] = $wpdb->insert_id;
                $this->paypal_here_settings['paypal_here_endpoint_url'] = !empty($_POST['paypal_here_endpoint_url']) ? sanitize_text_field($_POST['paypal_here_endpoint_url']) : 'paypal-here';
                $this->paypal_here_settings['paypal_here_endpoint_title'] = !empty($_POST['paypal_here_endpoint_title']) ? sanitize_text_field($_POST['paypal_here_endpoint_title']) : 'PayPal Here';
                update_option('woocommerce_angelleye_paypal_here_settings', $this->paypal_here_settings);
                $key_id = $wpdb->insert_id;
                $data['consumer_key'] = $consumer_key;
                $data['consumer_secret'] = $consumer_secret;
                $data['message'] = __('API Key generated successfully. Make sure to copy your new keys now as the secret key will be hidden once you leave this page.', 'woocommerce');
                $data['revoke_url'] = '<a style="color: #a00; text-decoration: none;" href="' . esc_url(wp_nonce_url(add_query_arg(array('revoke-key' => $key_id), admin_url('admin.php?page=wc-settings&tab=api&section=keys')), 'revoke')) . '">' . __('Revoke key', 'woocommerce') . '</a>';
            }
            wp_send_json_success($data);
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    public function angelleye_paypal_here_remove_key($key_id) {
        try {
            global $wpdb;
            $delete = $wpdb->delete($wpdb->prefix . 'woocommerce_api_keys', array('key_id' => $key_id), array('%d'));
            return $delete;
        } catch (Exception $ex) {
            
        }
    }

    public function angelleye_paypal_here_dashboard_body_content() {
        if (class_exists('WooCommerce') && did_action('wp_loaded')) {
            if (isset(WC()->cart) && sizeof(WC()->cart->get_cart()) > 0) {
                WC()->cart->empty_cart();
            }
        }
        paypal_here_unset_session('angelleye_paypal_here_order_awaiting_payment');
        include $this->plugin_path() . '/templates/' . 'default.php';
    }

    public function angelleye_paypal_here_view_products_body_content() {
        try {
            $this->angelleye_paypal_here_display_product_list();
        } catch (HttpClientException $ex) {
            echo print_r($ex->getMessage(), true);
        } catch (Exception $ex) {
            echo print_r($ex->getMessage(), true);
        }
    }

    public function angelleye_paypal_here_display_product_list() {
        $this->result = $this->here_rest_api->angelleye_paypal_here_get_product();
        $this->angelleye_paypal_here_get_product_list();
        include $this->plugin_path() . '/templates/' . 'products_search.php';
        include $this->plugin_path() . '/templates/' . 'products.php';
    }

    public function angelleye_paypal_here_get_product_list() {
        if (!empty($this->result)) {
            foreach ($this->result as $key => $value) {
                if (!empty($value['id'])) {
                    $this->product_list[$key] = $value['id'];
                } elseif (!empty($value['product_id'])) {
                    $this->product_list[$key] = $value['product_id'];
                }
            }
        }
    }

    public function angelleye_paypal_here_view_pending_orders_body_content() {
        try {
            $this->angelleye_paypal_here_display_order_list();
        } catch (HttpClientException $ex) {
            echo print_r($ex->getMessage(), true);
        } catch (Exception $ex) {
            echo print_r($ex->getMessage(), true);
        }
    }

    public function angelleye_paypal_here_display_order_list() {
        $this->result = $this->here_rest_api->angelleye_paypal_here_get_pending_order();

        $this->angelleye_paypal_here_get_order_list();
        include $this->plugin_path() . '/templates/' . 'orders_search.php';
        include $this->plugin_path() . '/templates/' . 'orders.php';
    }

    public function angelleye_paypal_here_get_order_list() {
        if (!empty($this->result)) {
            foreach ($this->result as $key => $value) {
                $this->order_list[$key] = $value['id'];
            }
        }
    }

    public function angelleye_paypal_here_view_order_billing_body_content() {
        include $this->plugin_path() . '/templates/' . 'order_billing.php';
    }

    public function angelleye_paypal_here_view_order_shipping_body_content() {
        include $this->plugin_path() . '/templates/' . 'order_shipping.php';
    }

    public function angelleye_paypal_here_before_body_content() {
        
    }

    public function angelleye_paypal_here_handle_submit_action() {
        if (!empty($_POST['last_action']) && $_POST['last_action'] == 'order_billing') {
            WC()->customer->set_props(array(
                'billing_country' => isset($_POST['billing_country']) ? wp_unslash($_POST['billing_country']) : null,
                'billing_state' => isset($_POST['billing_state']) ? wp_unslash($_POST['billing_state']) : null,
                'billing_postcode' => isset($_POST['billing_postcode']) ? wp_unslash($_POST['billing_postcode']) : null,
                'billing_city' => isset($_POST['billing_city']) ? wp_unslash($_POST['billing_city']) : null,
                'billing_address_1' => isset($_POST['billing_address_1']) ? wp_unslash($_POST['billing_address_1']) : null,
                'billing_address_2' => isset($_POST['billing_address_2']) ? wp_unslash($_POST['billing_address_2']) : null,
            ));
            paypal_here_set_session('billing_address', $_POST);
            WC()->customer->save();
            if (!empty($_POST['action']) && 'skip_shipping' == $_POST['action']) {
                WC()->customer->set_props(array(
                    'shipping_country' => isset($_POST['shipping_country']) ? wp_unslash($_POST['shipping_country']) : null,
                    'shipping_state' => isset($_POST['shipping_state']) ? wp_unslash($_POST['shipping_state']) : null,
                    'shipping_postcode' => isset($_POST['shipping_postcode']) ? wp_unslash($_POST['shipping_postcode']) : null,
                    'shipping_city' => isset($_POST['shipping_city']) ? wp_unslash($_POST['shipping_city']) : null,
                    'shipping_address_1' => isset($_POST['shipping_address_1']) ? wp_unslash($_POST['shipping_address_1']) : null,
                    'shipping_address_2' => isset($_POST['shipping_address_2']) ? wp_unslash($_POST['shipping_address_2']) : null,
                ));
                WC()->customer->save();
                $order_id = absint(paypal_here_get_session('angelleye_paypal_here_order_awaiting_payment'));
                $qrcode_order_url = add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url);
                wp_redirect($qrcode_order_url);
                exit();
            } else {
                wp_redirect(add_query_arg('actions', 'order_shipping', remove_query_arg('actions')));
                exit();
            }
        }
        if (!empty($_POST['last_action']) && $_POST['last_action'] == 'order_shipping') {
            WC()->customer->set_props(array(
                'shipping_country' => isset($_POST['shipping_country']) ? wp_unslash($_POST['shipping_country']) : null,
                'shipping_state' => isset($_POST['shipping_state']) ? wp_unslash($_POST['shipping_state']) : null,
                'shipping_postcode' => isset($_POST['shipping_postcode']) ? wp_unslash($_POST['shipping_postcode']) : null,
                'shipping_city' => isset($_POST['shipping_city']) ? wp_unslash($_POST['shipping_city']) : null,
                'shipping_address_1' => isset($_POST['shipping_address_1']) ? wp_unslash($_POST['shipping_address_1']) : null,
                'shipping_address_2' => isset($_POST['shipping_address_2']) ? wp_unslash($_POST['shipping_address_2']) : null,
            ));
            WC()->customer->save();
            paypal_here_set_session('shipping_address', $_POST);
            $order_id = absint(paypal_here_get_session('angelleye_paypal_here_order_awaiting_payment'));
            $qrcode_order_url = add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url);
            wp_redirect($qrcode_order_url);
            exit();
        }
    }

    public function angelleye_paypal_here_view_pending_orders_details_body_content() {
        if (!empty($_GET['order_id'])) {
            if (class_exists('WooCommerce') && did_action('wp_loaded')) {
                if (isset(WC()->cart) && sizeof(WC()->cart->get_cart()) > 0) {
                    WC()->cart->empty_cart();
                }
            }
            $order_id = $_GET['order_id'];
            paypal_here_unset_session('angelleye_paypal_here_order_awaiting_payment');
            paypal_here_set_session('angelleye_paypal_here_order_awaiting_payment', $order_id);
            $this->order = wc_get_order($order_id);
            include $this->plugin_path() . '/templates/' . 'orders-details.php';
        }
    }

    public function paypal_here_get_copon_code() {
        $items = array();
        $this->result = $this->here_rest_api->angelleye_paypal_here_get_coupons();
        if (!empty($this->result)) {
            foreach ($this->result as $key => $value) {
                $items[] = $value['code'];
            }
        }
        wp_send_json_success($items);
    }

}
