<?php

include_once 'header.php';
do_action('angelleye_woo_paypal_here_before_body_content');
$home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
$paypal_here_settings = get_option('woocommerce_angelleye_woo_paypal_here_settings');
$paypal_here_endpoint_url = !empty($paypal_here_settings['paypal_here_endpoint_url']) ? $paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
$action = !empty($_GET['actions']) ? wc_clean($_GET['actions']) : 'dashboard';
switch ($action) {
    case 'dashboard':
        //include_once 'menu.php';
        do_action('angelleye_woo_paypal_here_dashboard_body_content');
        
        break;
    case 'view_products':
        include_once 'menu.php';
        do_action('angelleye_woo_paypal_here_view_products_body_content');
        include_once 'footer.php';
        break;
    case 'view_pending_orders':
        if(!empty($_GET['order_id'])) {
            $order_id = absint( $_GET['order_id'] );
            $order = wc_get_order($order_id);
            $order->calculate_totals( true );
            include_once 'order_menu.php';
            do_action('angelleye_woo_paypal_here_view_pending_orders_details_body_content');
        } else {
            include_once 'menu.php';
            do_action('angelleye_woo_paypal_here_view_pending_orders_body_content');
        }
        include_once 'footer.php';
        break;
    case 'order_billing':
        include_once 'menu.php';
        do_action('angelleye_woo_paypal_here_view_order_billing_body_content');
        include_once 'footer.php';
        break;
    case 'order_shipping':
        include_once 'menu.php';
        do_action('angelleye_woo_paypal_here_view_order_shipping_body_content');
        include_once 'footer.php';
        break;
}
do_action('angelleye_woo_paypal_here_after_body_content');

