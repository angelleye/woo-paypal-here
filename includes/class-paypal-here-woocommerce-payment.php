<?php

if (!defined('ABSPATH')) {
    exit;
}

class Paypal_Here_Woocommerce_Payment extends WC_Payment_Gateway {

    public function __construct() {
        $this->id = 'angelleye_paypal_here';
        $this->method_title = __('PayPal Here', 'paypal-here-woocommerce');
        $this->method_description = __('PayPal Here allows you to accept payments using Visa, MasterCard, American Express, and Discover branded credit cards and debit cards (“Cards”) into your PayPal account. You can also keep records of cash and check payments. PayPal Here is available in the fifty United States and the District of Columbia. To register for PayPal Here, you must provide certain personal information, agree to these terms and have a business account in good standing. If you have a personal account (rather than a business account) prior to signing up for PayPal Here, you will be upgraded automatically to a business account as part of the PayPal Here sign-up process, depending on your expected use of PayPal Here. You must be approved by PayPal to use PayPal Here.', 'paypal-here-woocommerce');
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
        parent::admin_options();
        ?>
        <style type="text/css">
            .woocommerce table.form-table {
                width: 60%;
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
