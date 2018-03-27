<?php

/**
 * @link              https://www.angelleye.com
 * @since             1.0.0
 * @package           Paypal_Here_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal Here for WooCommerce
 * Plugin URI:        https://www.angelleye.com/paypal-here-woocommerce
 * Description:       Process WooCommerce orders with PayPal Here, or create new orders from the mobile app using WooCommerce data and sync the order back to WooCommerce.
 * Version:           1.0.0
 * Author:            Angell EYE
 * Author URI:        https://www.angelleye.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       paypal-here-woocommerce
 * Domain Path:       /languages
 * Tested up to: 4.9.4
 * WC requires at least: 3.0.0
 * WC tested up to: 3.3.2
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PAYPAL_HERE_VERSION', '1.0.0');
if (!defined('PAYPAL_HERE_PLUGIN_DIR')) {
    define('PAYPAL_HERE_PLUGIN_DIR', dirname(__FILE__));
}
if (!defined('PAYPAL_HERE_ASSET_URL')) {
    define('PAYPAL_HERE_ASSET_URL', plugin_dir_url(__FILE__));
}
if (!defined('PAYPAL_HERE_PLUGIN_FILE')) {
    define('PAYPAL_HERE_PLUGIN_FILE', __FILE__);
}
if (!defined('PAYPAL_HERE_PLUGIN_BASENAME')) {
    define('PAYPAL_HERE_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-paypal-here-woocommerce-activator.php
 */
function activate_paypal_here_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-here-woocommerce-activator.php';
    Paypal_Here_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-paypal-here-woocommerce-deactivator.php
 */
function deactivate_paypal_here_woocommerce() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-paypal-here-woocommerce-deactivator.php';
    Paypal_Here_Woocommerce_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_paypal_here_woocommerce');
register_deactivation_hook(__FILE__, 'deactivate_paypal_here_woocommerce');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-paypal-here-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_paypal_here_woocommerce() {

    $plugin = new Paypal_Here_Woocommerce();
    $plugin->run();
}

add_action('plugins_loaded', 'load_angelleye_paypal_here');
add_action('init', 'angelleye_load_end_point');

function load_angelleye_paypal_here() {
    if (class_exists('WC_Payment_Gateway')) {
        run_paypal_here_woocommerce();
    }
}

function angelleye_load_end_point() {
    require plugin_dir_path(__FILE__) . 'includes/class-paypal-here-woocommerce-end-point.php';
    run_paypal_here_woocommerce_end_point();
}

function run_paypal_here_woocommerce_end_point() {
    if( class_exists('Paypal_Here_Woocommerce_End_Point') ) {
        $end_point = new Paypal_Here_Woocommerce_End_Point();
        $end_point->angelleye_paypal_here_add_endpoints();
    }
    
}

function run_paypal_here_woocommerce_rest_api() {
    
}
