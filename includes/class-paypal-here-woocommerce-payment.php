<?php
if (!defined('ABSPATH')) {
    exit;
}

class Paypal_Here_Woocommerce_Payment extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'angelleye_paypal_here';
        $this->method_title = __('PayPal Here', 'paypal-here-woocommerce');
        $this->method_description = __('', 'paypal-here-woocommerce');
        $this->has_fields = true;
        $this->init_form_fields();
        $this->init_settings();
        $this->paypal_here_endpoint_url = $this->get_option('paypal_here_endpoint_url', 'paypal-here');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        $this->generate_woocommerce_rest_api_key = $this->get_option('generate_woocommerce_rest_api_key', 'Generate WooCommerce REST API key');
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
    }

    public function is_available() {
        return false;
    }

    public function process_admin_options() {
        $_POST['woocommerce_angelleye_paypal_here_generate_woocommerce_rest_api_push_button'] = 'Generate WooCommerce REST API key';
        parent::process_admin_options();
    }

    public function admin_options() {
        echo '<div id="angelleye_paypal_here_right_sidebar_main" class="wrap">';
        echo '<div id="poststuff">';
        echo '<div id="post-body" class="metabox-holder columns-2">';
        echo '<div id="post-body-content" style="position: relative;">';
        echo '<h1>' . esc_html($this->get_method_title()) . '</h1>';
        echo '<div id="paypal_here_details">' . wp_kses_post($this->get_method_description()) . '</div>';
        echo '<table class="form-table">' . $this->generate_settings_html($this->get_form_fields(), false) . '</table>';
        ?>
        <p class="submit_button">
            <?php if (empty($GLOBALS['hide_save_button'])) : ?>
                <input name="save" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>" />
            <?php endif; ?>
            <?php wp_nonce_field('woocommerce-settings'); ?>
        </p>
        <?php
        echo '</div>';
        echo '<div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                <div id="postimagediv" class="postbox ">
                    <h2 class="hndle ui-sortable-handle" style="text-align: center;"><span>' . __('Load PayPal Here Web App', 'paypal-here-woocommerce') . '</span></h2>
                    <div class="inside">
                    <div id="paypal_here_endpoint_url_qrcode"></div>
                    </div>
                </div>
                </div>
            </div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
        if (empty($this->generate_woocommerce_rest_api_key_value)) {
            ?>
            <script type="text/javascript">
                jQuery('#woocommerce_angelleye_paypal_here_generate_woocommerce_rest_api_key_value').closest('tr').hide();
            </script>
            <?php
        }
        ?>
        <style type="text/css">
            #woocommerce_angelleye_paypal_here_generate_woocommerce_rest_api_push_button {
                padding: 0px;
                width: 251px;
            }


            p.submit {
                display: none;
            }
        </style>
        <?php
    }

    public function init_form_fields() {
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
        $this->form_fields['enabled'] = array(
            'title' => __('Enable/Disable', 'woocommerce'),
            'type' => 'checkbox',
            'label' => __('Enable PayPal Here', 'woocommerce'),
            'default' => 'no'
        );
        if (empty($this->generate_woocommerce_rest_api_key_value)) {
            $this->form_fields['generate_woocommerce_rest_api_push_button'] = array(
                'title' => __('WooCommerce REST API', 'woocommerce'),
                'type' => 'button',
                'description' => __('', ''),
                'default' => 'Generate WooCommerce REST API key',
                'class' => "button button-primary"
            );
        }
        $this->form_fields['generate_woocommerce_rest_api_key_value'] = array(
            'title' => __('Consumer key ending in', 'woocommerce'),
            'type' => 'text',
            'description' => '<div id="rest_api_key_value_description"><a style="color: #a00; text-decoration: none;" href="">Revoke key</a></div>',
            'disabled' => true
        );
        $this->form_fields['paypla_here_endpoint'] = array('title' => __('PayPal Here endpoint', 'woocommerce'), 'type' => 'title', 'description' => __('Endpoints are appended to your page URLs to handle specific actions during the checkout process. They should be unique.', 'woocommerce'));
        $this->form_fields['paypal_here_endpoint_url'] = array(
            'title' => __('PayPal Here URL', 'woocommerce'),
            'id' => 'paypal_here_endpoint_url',
            'type' => 'text',
            'description' => sprintf(__('Endpoint for PayPal Here e.g %s/%s', 'woocommerce'), site_url(), 'paypal-here'),
            'default' => 'paypal-here',
        );
        $this->form_fields['paypal_here_endpoint_title'] = array(
            'title' => __('PayPal Here Page Title', 'woocommerce'),
            'id' => 'paypal_here_endpoint_title',
            'type' => 'text',
            'description' => '',
            'default' => 'PayPal Here',
        );
    }
    
    

}
