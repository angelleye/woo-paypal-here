<?php

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

define('YOUR_STORE_URL', 'https://www.angelleye.com/');
define('CONSUMER_KEY', 'ck_a91408bd58fc514f801b981b2febf68ed2d497cc');
define('CONSUMER_SECRET', 'cs_1e750d26b6648614c0b07793b870b697f1c653df');

$woocommerce = new Client(
        YOUR_STORE_URL, CONSUMER_KEY, CONSUMER_SECRET, ['query_string_auth' => true, 'timeout' => 60]
);

try {
    $addons = $woocommerce->get('orders', array( 'status' => 'completed' ));
	echo print_r($addons, true);
    
} catch (HttpClientException $e) {
    echo $e->getMessage(); 
}