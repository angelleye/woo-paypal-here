<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('paypal_here_set_session')) {

    function paypal_here_set_session($key, $value) {
        if ( ! class_exists( 'WooCommerce' ) || WC()->session == null ) {
            return false;
        }
        $paypal_here_session = WC()->session->get('paypal_here_session');
        if(empty($paypal_here_session)) {
            $paypal_here_session = array();
        }
        $paypal_here_session[$key] = $value;
        WC()->session->set('paypal_here_session', $paypal_here_session);
        $_SESSION['paypal_here_session'] = $paypal_here_session;
    }

}
if (!function_exists('paypal_here_get_session')) {

    function paypal_here_get_session($key) {
        if ( ! class_exists( 'WooCommerce' ) || WC()->session == null ) {
            return false;
        }
        if ( WC()->session->paypal_here_session > 0 ) {
            $paypal_here_session = WC()->session->paypal_here_session;
            if (!empty($paypal_here_session[$key])) {
                return $paypal_here_session[$key];
            } else {
                return false;
            }
        } else {
            if( !empty($_SESSION['paypal_here_session'])) {
                WC()->session->set('paypal_here_session', $_SESSION['paypal_here_session']);
                $paypal_here_session = WC()->session->paypal_here_session;
                if (!empty($paypal_here_session[$key])) {
                    return $paypal_here_session[$key];
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
            
        }
        
    }

}
if (!function_exists('paypal_here_unset_session')) {

    function paypal_here_unset_session($key) {
        if ( ! class_exists( 'WooCommerce' ) || WC()->session == null ) {
            return false;
        }
        $paypal_here_session = WC()->session->get('paypal_here_session');
        if (!empty($paypal_here_session[$key])) {
            unset($paypal_here_session[$key]);
            WC()->session->set('paypal_here_session', $paypal_here_session);
            $_SESSION['paypal_here_session'] = $paypal_here_session;
        }
    }

}


if ( ! function_exists( 'print_attribute_radio' ) ) {
		function print_attribute_radio( $checked_value, $value, $label, $name ) {
			global $product;

			$input_name = 'attribute_' . esc_attr( $name ) ;
			$esc_value = esc_attr( $value );
			$id = esc_attr( $name . '_v_' . $value . $product->get_id() ); //added product ID at the end of the name to target single products
			$checked = checked( $checked_value, $value, false );
			$filtered_label = apply_filters( 'woocommerce_variation_option_name', $label );
			printf( '<div class="float-left mr-3"><label class="btn btn-outline-secondary" for="%3$s"><input type="radio" class="paypal_here_variation_radio" name="%1$s" value="%2$s" id="%3$s" %4$s>%5$s</label></div>', $input_name, $esc_value, $id, $checked, $filtered_label );
		}
	}