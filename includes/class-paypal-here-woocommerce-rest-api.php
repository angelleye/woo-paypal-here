<?php

/**
 * @since      1.0.0
 * @package    Paypal_Here_Woocommerce_Rest_API
 * @subpackage Paypal_Here_Woocommerce_Rest_API/includes
 * @author     Angell EYE <service@angelleye.com>
 */
use Automattic\WooCommerce\Client;

class Paypal_Here_Woocommerce_Rest_API {

    public $paypal_here_settings = array();

    public function __construct() {
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->site_url;
        $this->ck;
        $this->ck;
        try {
            include_once PAYPAL_HERE_PLUGIN_DIR . 'includes/lib/wc-api-php-master/vendor/autoload.php';
            $this->woocommerce = new Client(
                    $this->site_url, $this->ck, $this->ck, [
                'wp_api' => true,
                'version' => 'wc/v1',
                    ]
            );
        } catch (Exception $ex) {
            
        }
    }

    public function angelleye_paypal_here_get_product() {
        
    }

    public function angelleye_paypal_here_get_pending_order() {
        
    }

    public function angelleye_paypal_here_get_shipping_methods() {
        
    }

}
