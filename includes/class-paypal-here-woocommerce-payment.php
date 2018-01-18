<?php
if (!defined('ABSPATH')) {
    exit;
}

class Paypal_Here_Woocommerce_Payment extends WC_Payment_Gateway {

    public $order_item;

    public function __construct() {
        $this->id = 'angelleye_paypal_here';
        $this->has_fields = true;
        $this->method_title = __('PayPal Here', 'paypal-here-woocommerce');
        $this->method_description = __('', 'paypal-here-woocommerce');
        $this->description = $this->get_option('description', 'PayPal Here');
        $this->title = $this->get_option('title', 'PayPal Here');
        $this->init_form_fields();
        $this->init_settings();
        $this->paypal_here_endpoint_url = $this->get_option('paypal_here_endpoint_url', 'paypal-here');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        $this->generate_woocommerce_rest_api_key = $this->get_option('generate_woocommerce_rest_api_key', 'Generate WooCommerce REST API key');
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
        $this->email = $this->get_option('email');
        $this->accepted_payment_methods = $this->get_option('accepted_payment_methods', array('cash', 'card', 'paypal'));
        $this->return_url = $this->angelleye_paypal_here_return_url();
        $this->invoice_id_prefix = $this->get_option('invoice_id_prefix', '');
        $this->paypal_here_payment_url = 'paypalhere://takePayment?returnUrl=';
        if (!has_action('woocommerce_api_' . strtolower('Paypal_Here_Woocommerce_Payment'))) {
            add_action('woocommerce_api_' . strtolower('Paypal_Here_Woocommerce_Payment'), array($this, 'handle_wc_api'));
        }
    }

    public function is_available() {
        if ($this->enabled === "yes") {
            if (!$this->generate_woocommerce_rest_api_key || !$this->generate_woocommerce_rest_api_key_value || !$this->email) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function payment_fields() {
        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }
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
                <input name="save" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_attr_e('Save changes', 'paypal-here-woocommerce'); ?>" />
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
            'title' => __('Enable/Disable', 'paypal-here-woocommerce'),
            'type' => 'checkbox',
            'label' => __('Enable PayPal Here', 'paypal-here-woocommerce'),
            'default' => 'no'
        );
        $this->form_fields['email'] = array(
            'title' => __('PayPal email', 'paypal-here-woocommerce'),
            'type' => 'email',
            'description' => __('Please enter your PayPal email address; this is needed in order to take payment.', 'paypal-here-woocommerce'),
            'default' => get_option('admin_email'),
            'desc_tip' => true,
            'placeholder' => 'you@youremail.com',
        );
        $this->form_fields['accepted_payment_methods'] = array(
            'title' => __('Accepted payment methods', 'paypal-here-woocommerce'),
            'type' => 'multiselect',
            'class' => 'chosen_select',
            'css' => 'width: 350px;',
            'desc_tip' => __('Select accepted payment methods.', 'paypal-here-woocommerce'),
            'options' => array(
                'cash' => 'Cash',
                'card' => 'Card',
                'invoice' => 'Invoice',
                'check' => 'Check',
                'paypal' => 'PayPal'
            ),
            'default' => array('cash', 'card', 'paypal'),
        );
        $this->form_fields['invoice_id_prefix'] = array(
            'title' => __('Invoice ID Prefix', 'paypal-here-woocommerce'),
            'type' => 'text',
            'description' => __('Add a prefix to the invoice ID sent to PayPal. This can resolve duplicate invoice problems when working with multiple websites on the same PayPal account.', 'paypal-here-woocommerce'),
            'desc_tip' => true,
            'default' => 'WC-PH'
        );
        if (empty($this->generate_woocommerce_rest_api_key_value)) {
            $this->form_fields['generate_woocommerce_rest_api_push_button'] = array(
                'title' => __('WooCommerce REST API', 'paypal-here-woocommerce'),
                'type' => 'button',
                'description' => __('', ''),
                'default' => 'Generate WooCommerce REST API key',
                'class' => "button button-primary"
            );
        }
        $this->form_fields['generate_woocommerce_rest_api_key_value'] = array(
            'title' => __('Consumer key ending in', 'paypal-here-woocommerce'),
            'type' => 'text',
            'description' => '<div id="rest_api_key_value_description"><a style="color: #a00; text-decoration: none;" href="">Revoke key</a></div>',
            'disabled' => true
        );
        $this->form_fields['product_filter_settings'] = array(
            'title' => __('View Products', 'paypal-here-woocommerce'),
            'type' => 'select',
            'class' => 'wc-enhanced-select',
            'description' => __('Choose whether you wish to how to display the "View Products" screen in the web app.', 'paypal-here-woocommerce'),
            'default' => 'featured_products',
            'desc_tip' => true,
            'options' => array(
                'featured_products' => __('Featured Products', 'paypal-here-woocommerce'),
                'new_products' => __('New Products', 'paypal-here-woocommerce'),
                'top_selling_products' => __('Top Selling Products', 'paypal-here-woocommerce')
            ),
        );
        $this->form_fields['paypla_here_endpoint'] = array('title' => __('PayPal Here endpoint', 'paypal-here-woocommerce'), 'type' => 'title', 'description' => __('Endpoints are appended to your page URLs to handle specific actions during the checkout process. They should be unique.', 'paypal-here-woocommerce'));
        $this->form_fields['paypal_here_endpoint_url'] = array(
            'title' => __('PayPal Here URL', 'paypal-here-woocommerce'),
            'id' => 'paypal_here_endpoint_url',
            'type' => 'text',
            'description' => sprintf(__('Endpoint for PayPal Here e.g %s/%s', 'paypal-here-woocommerce'), site_url(), 'paypal-here'),
            'default' => 'paypal-here',
        );
        $this->form_fields['paypal_here_endpoint_title'] = array(
            'title' => __('PayPal Here Page Title', 'paypal-here-woocommerce'),
            'id' => 'paypal_here_endpoint_title',
            'type' => 'text',
            'description' => '',
            'default' => 'PayPal Here',
        );
    }

    public function process_payment($order_id) {
        try {
            unset(WC()->session->angelleye_paypal_here_order_awaiting_payment);
            require PAYPAL_HERE_PLUGIN_DIR . '/includes/class-paypal-here-woocommerce-calculations.php';
            if (class_exists('Paypal_Here_Woocommerce_Calculation')) {
                $order = wc_get_order($order_id);
                $billing_phone = $order->get_billing_phone();
                $billing_email = $order->get_billing_email();
                $this->calculation = new Paypal_Here_Woocommerce_Calculation();
                $this->order_item = $this->calculation->order_calculation($order_id);
                $this->invoice['itemList'] = array('item' => $this->order_item['order_items']);
                $this->invoice['paymentTerms'] = 'DueOnReceipt';
                $this->invoice['currencyCode'] = $order->get_currency();
                $this->invoice['number'] = str_replace("#", "", $order->get_order_number());
                if (!empty($billing_email)) {
                    $this->invoice['payerEmail'] = $billing_email;
                }
                $this->invoice['merchantEmail'] = $this->email;
                if ($this->order_item['shippingamt'] > 0) {
                    $this->invoice['shippingAmount'] = $this->order_item['shippingamt'];
                }
                $this->invoice_encoded = urlencode(json_encode($this->invoice));
                $accepted_payment_methods_string = implode(",", $this->accepted_payment_methods);
                $this->retUrl = urlencode($this->return_url . "?{result}?Type={Type}&InvoiceId={InvoiceId}&Tip={Tip}&Email={Email}&TxId={TxId}");
                $this->paypal_here_payment_url .= $this->retUrl;
                $this->paypal_here_payment_url .= "&accepted=" . $accepted_payment_methods_string;
                $this->paypal_here_payment_url .= "&InvoiceId=" . $this->invoice_id_prefix . preg_replace("/[^a-zA-Z0-9]/", "", str_replace("#", "", $order->get_order_number()));
                $this->paypal_here_payment_url .= "&step=choosePayment";

                if (!empty($billing_phone)) {
                    if (in_array($order->get_billing_country(), array('US', 'CA'))) {
                        $billing_phone = str_replace(array('(', '-', ' ', ')', '.'), '', $billing_phone);
                        $billing_phone = ltrim($billing_phone, '+1');
                    }
                    // $this->paypal_here_payment_url .= "&payerPhone=" . $billing_phone;
                }
                $this->paypal_here_payment_url .= "&invoice=" . $this->invoice_encoded;


                return array(
                    'result' => 'success',
                    'redirect' => $this->paypal_here_payment_url,
                );
            }
        } catch (Exception $ex) {
            
        }
    }

    public function handle_wc_api() {
        
    }

    public function angelleye_paypal_here_return_url() {
        //urlencode('paypalhere://takePayment/{result}?Type={Type}&InvoiceId={InvoiceId}&Tip={Tip}&TxId={TxId}');
        return 'http://192.168.1.2/here/paypal-here';
        //return add_query_arg(array('paypal_here_action' => 'return_action', 'utm_nooverride' => '1'), WC()->api_request_url('Paypal_Here_Woocommerce_Payment'));
    }
    
    public function angelleye_paypal_here_process_payment() {
        $order_id = $_POST['order_id'];
        $location = $this->process_payment($order_id);
        wp_send_json($location);
    }

}
