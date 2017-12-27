<?php

/**
 * @since      1.0.0
 * @package    Paypal_Here_Woocommerce
 * @subpackage Paypal_Here_Woocommerce/includes
 * @author     Angell EYE <service@angelleye.com>
 */
class Paypal_Here_Woocommerce_End_Point {

    public $paypal_here_settings = array();

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        add_action('init', array($this, 'angelleye_paypal_here_add_endpoints'), 0);
        add_filter('template_include', array($this, 'template_loader'), 0, 1);
        add_filter('the_title', array($this, 'angelleye_paypal_here_page_endpoint_title'), 10, 1);
        add_filter('wp_title', array($this, 'angelleye_paypal_here_page_endpoint_wp_title'), 0, 1);
        add_action('wp_ajax_angelleye_paypal_here_woocommerce_update_api_key', array($this, 'angelleye_paypal_here_update_api_key'));
        add_action('wp_ajax_angelleye_paypal_here_revoke_key', array($this, 'angelleye_paypal_here_revoke_key'));
        if (!is_admin()) {
            add_filter('query_vars', array($this, 'angelleye_paypal_here_add_query_vars'), 0);
            add_action('parse_request', array($this, 'angelleye_paypal_here_parse_request'), 0);
        }
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
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_title'])) {
                return $this->paypal_here_settings['paypal_here_endpoint_title'];
            }
            remove_filter('the_title', 'angelleye_paypal_here_page_endpoint_title');
        }
        return $title;
    }

    public function is_angelleye_paypal_here_endpoint_url($title = false) {
        global $wp;
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
            do_action('woocommerce_flush_rewrite_rules');
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
        return untrailingslashit(plugin_dir_path(PAYPAL_HERE_PLUGIN_FILE));
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
                if (!empty($_POST['enabled']) && 'true' == $_POST['enabled']) {
                    $this->paypal_here_settings['enabled'] = 'yes';
                }
                $this->paypal_here_settings['key_id'] = $wpdb->insert_id;
                $this->paypal_here_settings['paypal_here_endpoint_url'] = !empty($_POST['paypal_here_endpoint_url']) ? $_POST['paypal_here_endpoint_url'] : 'paypal-here';
                $this->paypal_here_settings['paypal_here_endpoint_title'] = !empty($_POST['paypal_here_endpoint_title']) ? $_POST['paypal_here_endpoint_title'] : 'PayPal Here';
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

}
