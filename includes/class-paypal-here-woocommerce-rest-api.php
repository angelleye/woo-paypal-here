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

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->site_url;
        $this->cs = 'cs_'.$this->paypal_here_settings['uniq_cs'];
        $this->ck = 'ck_'.$this->paypal_here_settings['uniq_ck'];
        try {
            include_once PAYPAL_HERE_PLUGIN_DIR . '/includes/lib/api/vendor/autoload.php';
            $this->woocommerce = new Client(
                    $this->site_url, $this->ck, $this->ck, [
                'wp_api' => true,
                'version' => 'wc/v2',
                    ]
            );
        } catch (Exception $ex) {
            
        }
    }

    public function angelleye_paypal_here_get_product() {
        $this->result = $this->woocommerce->get('products', array( 'status' => 'processing', 'customer_id' => '1'));
    }

    public function angelleye_paypal_here_get_pending_order() {
        $this->result = $this->woocommerce->get('orders', array( 'status' => 'processing', 'customer_id' => '1'));
    }

    public function angelleye_paypal_here_get_shipping_methods() {
        $this->result = $this->woocommerce->get('orders', array( 'status' => 'processing', 'customer_id' => '1'));
    }

}
