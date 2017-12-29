<?php

include_once 'header.php';
do_action('angelleye_paypal_here_before_body_content');
$action = !empty($_GET['action']) ? $_GET['action'] : 'dashboard';
switch ($action) {
    case 'dashboard':
        do_action('angelleye_paypal_here_dashboard_body_content');
        break;
    case 'view_products':
        do_action('angelleye_paypal_here_view_products_body_content');
        break;
    case 'view_pending_orders':
        do_action('angelleye_paypal_here_view_pending_orders_body_content');
        break;
}
do_action('angelleye_paypal_here_after_body_content');
include_once 'footer.php';
