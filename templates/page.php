<?php

include_once 'header.php';
do_action('angelleye_paypal_here_before_body_content');
$action = !empty($_GET['actions']) ? $_GET['actions'] : 'dashboard';
switch ($action) {
    case 'dashboard':
        include_once 'menu.php';
        do_action('angelleye_paypal_here_dashboard_body_content');
        break;
    case 'view_products':
        include_once 'menu.php';
        do_action('angelleye_paypal_here_view_products_body_content');
        break;
    case 'view_pending_orders':
        if(!empty($_GET['order_id'])) {
            include_once 'order_menu.php';
            do_action('angelleye_paypal_here_view_pending_orders_details_body_content');
        } else {
            include_once 'menu.php';
            do_action('angelleye_paypal_here_view_pending_orders_body_content');
        }
        break;
    case 'order_billing':
        include_once 'menu.php';
        do_action('angelleye_paypal_here_view_order_billing_body_content');
        break;
    case 'order_shipping':
        include_once 'menu.php';
        do_action('angelleye_paypal_here_view_order_shipping_body_content');
        break;
}
do_action('angelleye_paypal_here_after_body_content');
include_once 'footer.php';
