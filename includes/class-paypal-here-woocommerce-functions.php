<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('paypal_here_set_session')) {

    function paypal_here_set_session($key, $value) {
        if (sizeof(WC()->session) == 0) {
            return false;
        }
        $paypal_here_session = WC()->session->get('paypal_here_session');
        $paypal_here_session[$key] = $value;
        WC()->session->set('paypal_here_session', $paypal_here_session);
    }

}
if (!function_exists('paypal_here_get_session')) {

    function paypal_here_get_session($key) {
        if (sizeof(WC()->session) == 0) {
            return false;
        }
        $paypal_here_session = WC()->session->get('paypal_here_session');
        if (!empty($paypal_here_session[$key])) {
            return $paypal_here_session[$key];
        }
        return false;
    }

}
if (!function_exists('paypal_here_unset_session')) {

    function paypal_here_unset_session($key) {
        if (sizeof(WC()->session) == 0) {
            return false;
        }
        $paypal_here_session = WC()->session->get('paypal_here_session');
        if (!empty($paypal_here_session[$key])) {
            unset($paypal_here_session[$key]);
            WC()->session->set('paypal_here_session', $paypal_here_session);
        }
    }

}