<?php

/**
 * @link              https://www.angelleye.com
 * @since             1.0.0
 * @package           Paypal_Here_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal Here for WooCommerce
 * Plugin URI:        https://www.angelleye.com/paypal-here-woocommerce
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Angell EYE
 * Author URI:        https://www.angelleye.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       paypal-here-woocommerce
 * Domain Path:       /languages
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
add_action('init', 'angelleye_paypal_here_add_endpoints');
add_filter('template_include', 'template_loader', 0, 1);

function load_angelleye_paypal_here() {
    if (class_exists('WC_Payment_Gateway')) {
        run_paypal_here_woocommerce();
    }
}

function angelleye_paypal_here_add_endpoints() {
    $woocommerce_angelleye_paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');

    if (!empty($woocommerce_angelleye_paypal_here_settings['paypal_here_endpoint_value'])) {

        add_rewrite_endpoint($woocommerce_angelleye_paypal_here_settings['paypal_here_endpoint_value'], EP_PERMALINK | EP_PAGES);
    }
}

function template_loader($template) {
    global $wp;
    $woocommerce_angelleye_paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
    if (!empty($wp->query_vars)) {
        if (!empty($woocommerce_angelleye_paypal_here_settings['paypal_here_endpoint_value']) && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == $woocommerce_angelleye_paypal_here_settings['paypal_here_endpoint_value']) {
            $template = plugin_path() . '/templates/' . 'page.php';
        }
    }
    return $template;
}

function plugin_path() {
    return untrailingslashit(plugin_dir_path(PAYPAL_HERE_PLUGIN_FILE));
}
