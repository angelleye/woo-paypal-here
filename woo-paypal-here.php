<?php

/**
 * @link              https://www.angelleye.com
 * @since             0.1.0
 * @package           Woo_PayPal_Here
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal Here for WooCommerce
 * Plugin URI:        https://www.angelleye.com/woo-paypal-here
 * Description:       Process WooCommerce orders with PayPal Here, or create new orders from the mobile app using WooCommerce data and sync the order back to WooCommerce.
 * Version:           0.5.3
 * Author:            Angell EYE
 * Author URI:        https://www.angelleye.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woo-paypal-here
 * Domain Path:       /languages
 * Tested up to: 5.2.2
 * WC requires at least: 3.0.0
 * WC tested up to: 3.6.5
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('WOO_PAYPAL_HERE_VERSION', '0.5.3');
if (!defined('WOO_PAYPAL_HERE_PLUGIN_DIR')) {
    define('WOO_PAYPAL_HERE_PLUGIN_DIR', dirname(__FILE__));
}
if (!defined('WOO_PAYPAL_HERE_ASSET_URL')) {
    define('WOO_PAYPAL_HERE_ASSET_URL', plugin_dir_url(__FILE__));
}
if (!defined('WOO_PAYPAL_HERE_PLUGIN_FILE')) {
    define('WOO_PAYPAL_HERE_PLUGIN_FILE', __FILE__);
}
if (!defined('WOO_PAYPAL_HERE_PLUGIN_BASENAME')) {
    define('WOO_PAYPAL_HERE_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('AEU_ZIP_URL')) {
    define('AEU_ZIP_URL', 'https://updates.angelleye.com/ae-updater/angelleye-updater/angelleye-updater.zip');
}

if (!defined('PAYPAL_FOR_WOOCOMMERCE_PUSH_NOTIFICATION_WEB_URL')) {
    define('PAYPAL_FOR_WOOCOMMERCE_PUSH_NOTIFICATION_WEB_URL', 'https://www.angelleye.com/');
}

/**
 * Required functions
 */
if (!function_exists('angelleye_queue_update')) {
    require_once( 'includes/angelleye-functions.php' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-paypal-here-activator.php
 */
function activate_woo_paypal_here() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-here-activator.php';
    Woo_PayPal_Here_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-paypal-here-deactivator.php
 */
function deactivate_woo_paypal_here() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-here-deactivator.php';
    Woo_PayPal_Here_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woo_paypal_here');
register_deactivation_hook(__FILE__, 'deactivate_woo_paypal_here');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-here.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_woo_paypal_here() {

    $plugin = new Woo_PayPal_Here();
    $plugin->run();
}

add_action('plugins_loaded', 'load_angelleye_woo_paypal_here');
add_action('init', 'angelleye_woo_paypal_here_load_end_point', 0);


function load_angelleye_woo_paypal_here() {
    if (class_exists('WC_Payment_Gateway')) {
        run_woo_paypal_here();
    }
}

function angelleye_woo_paypal_here_load_end_point() {
    require plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-here-end-point.php';
    run_woo_paypal_here_end_point();
}

function run_woo_paypal_here_end_point() {
    if (class_exists('Woo_PayPal_Here_End_Point')) {
        $end_point = new Woo_PayPal_Here_End_Point();
        $end_point->angelleye_woo_paypal_here_add_endpoints();
    }
}