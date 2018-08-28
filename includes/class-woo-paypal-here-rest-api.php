<?php

/**
 * @since      1.0.0
 * @package    Woo_PayPal_Here_Rest_API
 * @subpackage Woo_PayPal_Here_Rest_API/includes
 * @author     Angell EYE <service@angelleye.com>
 */
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class Woo_PayPal_Here_Rest_API {

    public $paypal_here_settings = array();
    public $result;
    public $customer_id;
    public $product_filter_settings;

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_woo_paypal_here_settings');
        $this->site_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        if (!empty($this->paypal_here_settings['uniq_cs']) && !empty($this->paypal_here_settings['uniq_ck'])) {
            $this->cs = 'cs_' . $this->paypal_here_settings['uniq_cs'];
            $this->ck = 'ck_' . $this->paypal_here_settings['uniq_ck'];
            $this->product_filter_settings = !empty($this->paypal_here_settings['product_filter_settings']) ? $this->paypal_here_settings['product_filter_settings'] : 'featured_products';
            try {
                include_once WOO_PAYPAL_HERE_PLUGIN_DIR . '/includes/lib/api/vendor/autoload.php';
                $this->woocommerce = new Client(
                        $this->site_url, $this->ck, $this->cs, [
                    'wp_api' => true,
                    'version' => 'wc/v2',
                    'query_string_auth' => true
                        ]
                );
            } catch (Exception $ex) {
                
            }
        } else {
            
        }
    }

    public function angelleye_woo_paypal_here_get_product() {
        $request_param = array();
        if (!empty($_GET['search'])) {
            $request_param['search'] = wc_clean($_GET['search']);
        }
        switch ($this->product_filter_settings) {
            case 'featured_products':
                $request_param['featured'] = true;
                $this->result = $this->woocommerce->get('products', $request_param);
                if (empty($this->result)) {
                    $this->result = $this->woocommerce->get('products', array('per_page' => 100));
                }
                break;
            case 'new_products':
                $this->result = $this->woocommerce->get('products', array('per_page' => 100));
                break;
            case 'top_selling_products':
                $request_param['period'] = 'year';
                $request_param['per_page'] = '100';
                $this->result = $this->woocommerce->get('reports/top_sellers', $request_param);
                if (empty($this->result)) {
                    $this->result = $this->woocommerce->get('products', array('per_page' => 100));
                }
                break;
        }
        return $this->result;
    }

    public function angelleye_woo_paypal_here_get_pending_order() {
        $this->customer_id = get_current_user_id();
        $request_param = array('status' => 'pending');
        if (!empty($_GET['search'])) {
            $request_param['search'] = wc_clean($_GET['search']);
        }
        return $this->result = $this->woocommerce->get('orders', $request_param);
    }

    public function angelleye_woo_paypal_here_get_shipping_methods() {
        
    }

    public function angelleye_woo_paypal_here_get_coupons() {
        if (!empty($_POST['search']['term'])) {
            $request_param['search'] = wp_unslash($_POST['search']['term']);
            $this->result = $this->woocommerce->get('coupons', $request_param);
        } else {
            $this->result = $this->woocommerce->get('coupons');
        }
        return $this->result;
    }

}
