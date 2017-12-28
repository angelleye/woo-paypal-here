<?php

/**
 * @since      1.0.0
 * @package    Paypal_Here_Woocommerce_Rest_API
 * @subpackage Paypal_Here_Woocommerce_Rest_API/includes
 * @author     Angell EYE <service@angelleye.com>
 */
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class Paypal_Here_Woocommerce_Rest_API {

    public $paypal_here_settings = array();
    public $result;
    public $customer_id;
    public $product_filter_settings;

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->site_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        $this->cs = 'cs_' . $this->paypal_here_settings['uniq_cs'];
        $this->ck = 'ck_' . $this->paypal_here_settings['uniq_ck'];
        $this->product_filter_settings = $this->paypal_here_settings['product_filter_settings'];
        try {
            include_once PAYPAL_HERE_PLUGIN_DIR . '/includes/lib/api/vendor/autoload.php';
            $this->woocommerce = new Client(
                    $this->site_url, $this->ck, $this->cs, [
                'wp_api' => true,
                'version' => 'wc/v2',
                    ]
            );
        } catch (Exception $ex) {
            
        }
    }

    public function angelleye_paypal_here_get_product() {
        switch ($this->product_filter_settings) {
            case 'featured_products':
                $this->result = $this->woocommerce->get('products', array('featured' => true));
                break;
            case 'new_products':
                $this->result = $this->woocommerce->get('products');
                break;
            case 'top_selling_products':

                // not supported
                //filter[orderby]=meta_value_num&filter[orderby_meta_key]=_price. 
                //$query_args['meta_key'] = 'total_sales'; // @codingStandardsIgnoreLine
                //$query_args['order']    = 'DESC';
                //$query_args['orderby']  = 'meta_value_num';
                $this->result = $this->woocommerce->get('products', array('meta_key' => 'total_sales', 'order' => 'desc', 'orderby' => 'date'));
                break;
        }
        return $this->result;
    }

    public function angelleye_paypal_here_get_pending_order() {
        $this->customer_id = get_current_user_id();
        return $this->result = $this->woocommerce->get('orders', array('customer' => $this->customer_id, 'status' => 'pending'));
    }

    public function angelleye_paypal_here_get_shipping_methods() {
        
    }

}
