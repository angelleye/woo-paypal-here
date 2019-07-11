<?php
if (!defined('ABSPATH')) {
    exit;
}

class Woo_PayPal_Here_Payment extends WC_Payment_Gateway {

    public $order_item;

    public function __construct() {
        $this->id = 'angelleye_woo_paypal_here';
        $this->has_fields = true;
        $this->method_title = __('PayPal Here', 'woo-paypal-here');
        $this->method_description = __('', 'woo-paypal-here');
        $this->description = $this->get_option('description', 'PayPal Here');
        $this->title = $this->get_option('title', 'PayPal Here');
        $this->init_form_fields();
        $this->init_settings();
        $this->paypal_here_endpoint_url = $this->get_option('paypal_here_endpoint_url', 'paypal-here');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        $this->generate_woocommerce_rest_api_key = $this->get_option('generate_woocommerce_rest_api_key', 'Generate WooCommerce REST API Key');
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
        $this->email = $this->get_option('email');
        $this->accepted_payment_methods = $this->get_option('accepted_payment_methods', array('cash', 'card', 'paypal'));
        $this->return_url = '';
        $this->invoice_id_prefix = $this->get_option('invoice_id_prefix', '');
        $this->debug = 'yes' === $this->get_option('paypal_here_debug', 'no');
        $this->paypal_here_payment_url = 'paypalhere://takePayment/?returnUrl=';
        $this->home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
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
        $this->generate_woocommerce_rest_api_key_value = $this->get_option('generate_woocommerce_rest_api_key_value');
        if (!empty($this->generate_woocommerce_rest_api_key_value)) {
            $_POST['woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_key_value'] = $this->generate_woocommerce_rest_api_key_value;
        }
        $_POST['woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_push_button'] = 'Generate WooCommerce REST API key';
        parent::process_admin_options();
    }

    public function admin_options() {
        echo '<div id="angelleye_woo_paypal_here_right_sidebar_main" class="wrap">';
        echo '<div id="poststuff">';
        echo '<div id="post-body" class="metabox-holder columns-2">';
        echo '<div id="post-body-content" style="position: relative;">';
        echo '<h1>' . esc_html($this->get_method_title()) . '</h1>';
        echo '<div id="paypal_here_details">' . wp_kses_post($this->get_method_description()) . '</div>';
        echo '<table class="form-table">' . $this->generate_settings_html($this->get_form_fields(), false) . '</table>';
        ?>
        <p class="submit_button">
            <?php if (empty($GLOBALS['hide_save_button'])) : ?>
                <input name="save" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_attr_e('Save changes', 'woo-paypal-here'); ?>" />
            <?php endif; ?>
            <?php wp_nonce_field('woocommerce-settings'); ?>
        </p>
        <?php
        echo '</div>';
        echo '<div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                <div id="postimagediv" class="postbox ">
                    <h2 class="hndle ui-sortable-handle" style="text-align: center;"><span>' . __('Load PayPal Here Web App', 'woo-paypal-here') . '</span></h2>
                    <div class="inside">
                    <div style="text-align:center;"><img src="" class="paypal_here_endpoint_url_qrcode"/></div>
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
                jQuery('#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_key_value').closest('tr').hide();
            </script>
            <?php
        }
        ?>
        <style type="text/css">
            #woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_push_button {
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
            'title' => __('Enable/Disable', 'woo-paypal-here'),
            'type' => 'checkbox',
            'label' => __('Enable PayPal Here', 'woo-paypal-here'),
            'default' => 'no'
        );
        $this->form_fields['email'] = array(
            'title' => __('PayPal Email', 'woo-paypal-here'),
            'type' => 'email',
            'description' => __('Enter your PayPal account email address that will be used with the PayPal Here app.', 'woo-paypal-here'),
            'default' => get_option('admin_email'),
            'desc_tip' => true,
            'placeholder' => 'email@domain.com',
        );
        $this->form_fields['accepted_payment_methods'] = array(
            'title' => __('Accepted Payment Methods', 'woo-paypal-here'),
            'type' => 'multiselect',
            'class' => 'chosen_select',
            'css' => 'width: 350px;',
            'desc_tip' => __('Choose the payment methods you would like to make available in the Here app for WooCommerce orders.', 'woo-paypal-here'),
            'options' => array(
                'cash' => 'Cash',
                'card' => 'Credit Card',
                'invoice' => 'Invoice',
                'check' => 'Check',
                'paypal' => 'PayPal'
            ),
            'default' => array('cash', 'card', 'paypal'),
        );
        $this->form_fields['invoice_id_prefix'] = array(
            'title' => __('Invoice ID Prefix', 'woo-paypal-here'),
            'type' => 'text',
            'description' => __('Add a prefix to the invoice ID sent to PayPal. This can resolve duplicate invoice problems when working with multiple websites on the same PayPal account.', 'woo-paypal-here'),
            'desc_tip' => true,
            'default' => 'WC-PH-'
        );
        if (empty($this->generate_woocommerce_rest_api_key_value)) {
            $this->form_fields['generate_woocommerce_rest_api_push_button'] = array(
                'title' => __('WooCommerce REST API', 'woo-paypal-here'),
                'type' => 'button',
                'description' => __('', ''),
                'default' => 'Generate WooCommerce API Key',
                'class' => "button button-primary"
            );
        }
        $this->form_fields['generate_woocommerce_rest_api_key_value'] = array(
            'title' => __('API Key (Ending With)', 'woo-paypal-here'),
            'type' => 'text',
            'description' => '<div id="rest_api_key_value_description"><a style="color: #a00; text-decoration: none;" href="">Revoke key</a></div>',
            'disabled' => true
        );
        $this->form_fields['product_filter_settings'] = array(
            'title' => __('View Products', 'woo-paypal-here'),
            'type' => 'select',
            'class' => 'wc-enhanced-select',
            'description' => __('Choose which products you would like to display by default in the web app when creating new orders.', 'woo-paypal-here'),
            'default' => 'featured_products',
            'desc_tip' => true,
            'options' => array(
                'featured_products' => __('Featured Products', 'woo-paypal-here'),
                'new_products' => __('New Products', 'woo-paypal-here'),
                'top_selling_products' => __('Top Selling Products', 'woo-paypal-here')
            ),
        );
        $this->form_fields['paypal_here_endpoint'] = array('title' => __('PayPal Here Endpoint', 'woo-paypal-here'), 'type' => 'title', 'description' => __('Set the endpoint value you would like to use for the PayPal Here web app.  This will be appended to your site URL to build the full URL for the web app portion of the PayPal Here for WooCommerce solution.', 'woo-paypal-here'));
        $this->form_fields['paypal_here_endpoint_url'] = array(
            'title' => __('PayPal Here Endpoint', 'woo-paypal-here'),
            'id' => 'paypal_here_endpoint_url',
            'type' => 'text',
            'description' => sprintf(__('URL for PayPal Here web app using your endpoint: %s/%s', 'woo-paypal-here'), site_url(), 'paypal-here'),
            'default' => 'paypal-here',
        );
        $this->form_fields['paypal_here_endpoint_title'] = array(
            'title' => __('PayPal Here Page Title', 'woo-paypal-here'),
            'id' => 'paypal_here_endpoint_title',
            'type' => 'text',
            'description' => 'This value will be used as the page title for the PayPal Here web app.',
            'default' => 'PayPal Here',
            'desc_tip' => true,
        );
        $this->form_fields['paypal_here_debug'] = array(
            'title' => __('Debug Log', 'woo-paypal-here'),
            'type' => 'checkbox',
            'label' => __('Enable logging', 'woo-paypal-here'),
            'default' => 'no',
            'description' => sprintf(__('Log PayPal events, inside <code>%s</code>', 'woo-paypal-here'), wc_get_log_file_path('angelleye_woo_paypal_here'))
        );
    }

    public function process_payment($order_id) {
        try {
            paypal_here_unset_session('angelleye_woo_paypal_here_order_awaiting_payment');
            require WOO_PAYPAL_HERE_PLUGIN_DIR . '/includes/class-woo-paypal-here-calculations.php';
            if (class_exists('Woo_PayPal_Here_Calculation')) {
                $order = wc_get_order($order_id);
                $billing_phone = $order->get_billing_phone();
                $billing_email = $order->get_billing_email();
                $this->calculation = new Woo_PayPal_Here_Calculation();
                $this->order_item = $this->calculation->order_calculation($order_id);

                $this->invoice['itemList'] = array('item' => $this->order_item['order_items']);
                $billingInfo = array();
                $billing_company = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_company : $order->get_billing_company();
                $billing_first_name = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_first_name : $order->get_billing_first_name();
                $billing_last_name = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_last_name : $order->get_billing_last_name();
                $billing_address_1 = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_address_1 : $order->get_billing_address_1();
                $billing_address_2 = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_address_2 : $order->get_billing_address_2();
                $billing_city = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_city : $order->get_billing_city();
                $billing_postcode = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_postcode : $order->get_billing_postcode();
                $billing_country = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_country : $order->get_billing_country();
                $billing_state = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_state : $order->get_billing_state();
                if (!empty($billing_first_name)) {
                    $billingInfo['firstName'] = $this->limit_length($billing_first_name);
                }
                if (!empty($billing_last_name)) {
                    $billingInfo['lastName'] = $this->limit_length($billing_last_name);
                }
                if (!empty($billing_company)) {
                    $billingInfo['businessName'] = $this->limit_length($billing_company);
                }
                if (!empty($billing_company)) {
                    $billingInfo['businessName'] = $this->limit_length($billing_company);
                }
                $language = (get_locale() != '') ? get_locale() : '';
                if (!empty($language)) {
                    //$billingInfo['language'] = $this->limit_length($language, 5);
                }
                if (!empty($billing_address_1)) {
                    $billingInfo['address']['line1'] = $this->limit_length($billing_address_1);
                }
                if (!empty($billing_address_2)) {
                    $billingInfo['address']['line2'] = $this->limit_length($billing_address_2);
                }
                if (!empty($billing_city)) {
                    $billingInfo['address']['city'] = $this->limit_length($billing_city);
                }
                if (!empty($billing_state)) {
                    $billingInfo['address']['state'] = $this->limit_length($billing_state);
                }
                if (!empty($billing_postcode)) {
                    $billingInfo['address']['postalCode'] = $this->limit_length($billing_postcode);
                }
                if (!empty($billing_country)) {
                    $billingInfo['address']['countryCode'] = $this->limit_length($billing_country);
                }
                if (!empty($billingInfo)) {
                    $this->invoice['billingInfo'] = $billingInfo;
                }
                $shippingInfo = array();
                $shipping_first_name = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_first_name : $order->get_shipping_first_name();
                $shipping_last_name = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_last_name : $order->get_shipping_last_name();
                $shipping_address_1 = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_address_1 : $order->get_shipping_address_1();
                $shipping_address_2 = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_address_2 : $order->get_shipping_address_2();
                $shipping_city = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_city : $order->get_shipping_city();
                $shipping_state = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_state : $order->get_shipping_state();
                $shipping_postcode = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_postcode : $order->get_shipping_postcode();
                $shipping_country = version_compare(WC_VERSION, '3.0', '<') ? $order->shipping_country : $order->get_shipping_country();
                if (!empty($shipping_first_name)) {
                    $shippingInfo['firstName'] = $this->limit_length($shipping_first_name);
                }
                if (!empty($shipping_last_name)) {
                    $shippingInfo['lastName'] = $this->limit_length($shipping_last_name);
                }
                $language = (get_locale() != '') ? get_locale() : '';
                if (!empty($language)) {
                    //$shippingInfo['language'] = $this->limit_length($language, 5);
                }
                if (!empty($shipping_address_1)) {
                    $shippingInfo['address']['line1'] = $this->limit_length($shipping_address_1);
                }
                if (!empty($shipping_address_2)) {
                    $shippingInfo['address']['line2'] = $this->limit_length($shipping_address_2);
                }
                if (!empty($shipping_city)) {
                    $shippingInfo['address']['city'] = $this->limit_length($shipping_city);
                }
                if (!empty($shipping_state)) {
                    $shippingInfo['address']['state'] = $this->limit_length($shipping_state);
                }
                if (!empty($shipping_postcode)) {
                    $shippingInfo['address']['postalCode'] = $this->limit_length($shipping_postcode);
                }
                if (!empty($shipping_country)) {
                    $shippingInfo['address']['countryCode'] = $this->limit_length($shipping_country);
                }
                if (!empty($shippingInfo)) {
                    $this->invoice['shippingInfo'] = $shippingInfo;
                }
                $this->invoice['paymentTerms'] = 'DueOnReceipt';
                $this->invoice['currencyCode'] = $order->get_currency();
                $this->invoice['number'] = $this->invoice_id_prefix . preg_replace("/[^a-zA-Z0-9]/", "", str_replace("#", "", $order->get_order_number()));
                if (!empty($billing_email)) {
                    $this->invoice['payerEmail'] = $billing_email;
                }
                $this->invoice['merchantEmail'] = $this->email;
                if ($this->order_item['shippingamt'] > 0) {
                    //$this->invoice['shippingAmount'] = $this->order_item['shippingamt'];
                }
                $this->add_log('WooCommerce Version: ' . print_r(WC_VERSION, true));
                $this->add_log('PayPal Here for WooCommerce Version: ' . print_r(WOO_PAYPAL_HERE_VERSION, true));
                $this->add_log('Order ID: ' . print_r($order_id, true));
                $this->add_log('Endpoint: ' . print_r($this->paypal_here_payment_url, true));
                $this->add_log('Request: ' . print_r(json_encode($this->invoice), true));
                $this->invoice_encoded = rawurlencode(json_encode($this->invoice));
                $accepted_payment_methods_string = implode(",", $this->accepted_payment_methods);
                $this->return_url = $this->angelleye_woo_paypal_here_return_url();
                $this->retUrl = urlencode($this->return_url . "/{result}?Type={Type}&InvoiceId={InvoiceId}&Tip={Tip}&Email={Email}&TxId={TxId}&wc_order_id=$order_id");
                $this->paypal_here_payment_url .= $this->retUrl;
                $this->paypal_here_payment_url .= "&accepted=" . $accepted_payment_methods_string;
                $this->paypal_here_payment_url .= "&step=choosePayment";
                $this->paypal_here_payment_url .= "&invoice=" . $this->invoice_encoded;
                $this->add_log('Full Request URL for PayPal Here ' . print_r($this->paypal_here_payment_url, true));
                unset(WC()->session->paypal_here_session);
                return array(
                    'result' => 'success',
                    'redirect' => $this->paypal_here_payment_url,
                );
            }
        } catch (Exception $ex) {
            
        }
    }

    public function angelleye_woo_paypal_here_return_url() {
        $this->home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        $this->paypal_here_settings = get_option('woocommerce_angelleye_woo_paypal_here_settings');
        $this->paypal_here_endpoint_url = !empty($this->paypal_here_settings['paypal_here_endpoint_url']) ? $this->paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
        return $this->home_url . $this->paypal_here_endpoint_url;
    }

    public function angelleye_woo_paypal_here_process_payment() {
        $order_id = absint($_POST['order_id']);
        $location = $this->process_payment($order_id);
        wp_send_json($location);
    }

    public function limit_length($string, $limit = 127) {
        if (strlen($string) > $limit) {
            $string = substr($string, 0, $limit - 3) . '...';
        }
        return $string;
    }

    public function add_log($message, $level = 'info') {
        if ($this->debug) {
            if (version_compare(WC_VERSION, '3.0', '<')) {
                if (empty($this->log)) {
                    $this->log = new WC_Logger();
                }
                $this->log->add('angelleye_woo_paypal_here', $message);
            } else {
                if (empty($this->log)) {
                    $this->log = wc_get_logger();
                }
                $this->log->log($level, $message, array('source' => 'angelleye_woo_paypal_here'));
            }
        }
    }

    public function paypal_here_call_back_handler() {
        if (!empty($_GET['Type'])) {
            $this->add_log('call back response: ' . print_r($_SERVER, true));
            $order_id = !empty($_GET['wc_order_id']) ? absint($_GET['wc_order_id']) : '';
            if (empty($order_id)) {
                $order_id = str_replace($this->invoice_id_prefix, '', wc_clean($_GET['Number']));
            }
            try {
                if (!empty($order_id)) {
                    $order = wc_get_order($order_id);
                    if (is_object($order)) {
                        if (class_exists('WooCommerce') && did_action('wp_loaded')) {
                            if (isset(WC()->cart) && sizeof(WC()->cart->get_cart()) > 0) {
                                WC()->cart->empty_cart();
                            }
                        }
                        $transaction_id = !empty($_GET['InvoiceId']) ? wc_clean($_GET['InvoiceId']) : '';
                        update_post_meta( $order_id, '_payment_method', 'angelleye_woo_paypal_here' );
                        update_post_meta( $order_id, '_payment_method_title', $this->method_title );
                        $type = !empty($_GET['Type']) ? wc_clean($_GET['Type']) : '';
                        update_post_meta($order_id, 'Type', $type);
                        $this->add_log('Type: ' . print_r($type, true));
                        $this->add_log('InvoiceId: ' . print_r($transaction_id, true));
                        if ($type == 'UNKNOWN') {
                            $order->update_status('failed', __('transaction declined', 'woo-paypal-here'));
                        } else {
                            $request = array();
                            $request['amount'] = $order->get_total();
                            $request['transaction_id'] = $transaction_id;
                            $request['Type'] = $type;
                            do_action('angelleye_paypal_here_response_data', $request, array(), '15', false, false, 'paypal_here');
                            $order->payment_complete($transaction_id);
                        }
                        wp_redirect($this->home_url . $this->paypal_here_endpoint_url);
                    }
                }
                exit();
            } catch (Exception $ex) {
                wp_redirect($this->home_url . $this->paypal_here_endpoint_url);
                exit();
            }
        }
    }

}
