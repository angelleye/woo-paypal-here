<?php

class AngellEYE_PayPal_Here_Payment_Logger {

    protected static $_instance = null;
    public $allow_method = array();
    public $api_url;
    public $api_key;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->api_url = 'https://gtctgyk7fh.execute-api.us-east-2.amazonaws.com/default/PayPalPaymentsTracker';
        $this->api_key = 'srGiuJFpDO4W7YCDXF56g2c9nT1JhlURVGqYD7oa';
        $this->allow_method = array('PayPal Here');
        add_action('angelleye_paypal_here_response_data', array($this, 'own_angelleye_paypal_here_response_data'), 10, 6);
    }

    public function own_angelleye_paypal_here_response_data($result_data, $request_data, $product_id = 1, $sandbox = false, $is_nvp = true, $payment_method = 'express_checkout') {
        $request_param = array();
        if (isset($result_data) && is_array($result_data) && !empty($result_data['CURL_ERROR'])) {
            return $result_data;
        } else {
            $result = $result_data;
            $request = $request_data;
            if ($payment_method == 'paypal_here') {
                $request['METHOD'] = 'PayPal Here';
            }
            if (isset($request['METHOD']) && !empty($request['METHOD']) && in_array($request['METHOD'], $this->allow_method)) {
                $opt_in_log = get_option('angelleye_send_opt_in_logging_details', 'no');
                $request_param['site_url'] = '';
                if ($opt_in_log == 'yes') {
                    $request_param['site_url'] = get_bloginfo('url');
                }
                $request_param['type'] = $request['Type'];
                $request_param['mode'] = ($sandbox) ? 'sandbox' : 'live';
                $request_param['product_id'] = $product_id;
                if ($request['METHOD'] == 'paypal_here') {
                    if (isset($request['transaction_id'])) {
                        $request_param['status'] = 'Success';
                        $request_param['transaction_id'] = isset($request['transaction_id']) ? $request['transaction_id'] : '';
                    } else {
                        $request_param['status'] = 'Failure';
                    }
                    $request_param['merchant_id'] = '';
                    $request_param['correlation_id'] = '';
                    $request_param['amount'] = isset($request['amount']) ? $request['amount'] : '0.00';
                    $this->angelleye_tpv_request($request_param);
                }
            }
        }
        return $result_data;
    }

    public function angelleye_tpv_request($request_param) {
        try {
            $payment_type = $request_param['type'];
            $amount = $request_param['amount'];
            $status = $request_param['status'];
            $site_url = $request_param['site_url'];
            $payment_mode = $request_param['mode'];
            $merchant_id = $request_param['merchant_id'];
            $correlation_id = $request_param['correlation_id'];
            $transaction_id = $request_param['transaction_id'];
            $product_id = $request_param['product_id'];
            $params = [
                "product_id" => $product_id,
                "type" => $payment_type,
                "amount" => $amount,
                "status" => $status,
                "site_url" => $site_url,
                "mode" => $payment_mode,
                "merchant_id" => $merchant_id,
                "correlation_id" => $correlation_id,
                "transaction_id" => $transaction_id
            ];
            $params = apply_filters('angelleye_log_params', $params);
            $post_args = array(
                'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8',
                    'x-api-key' => $this->api_key
                ),
                'body' => json_encode($params),
                'method' => 'POST',
                'data_format' => 'body',
            );
            $response = wp_remote_post($this->api_url, $post_args);
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                error_log(print_r($error_message, true));
                return false;
            } else {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                if ($body['status']) {
                    return true;
                }
            }
            return false;
        } catch (Exception $ex) {
            
        }
    }

}
