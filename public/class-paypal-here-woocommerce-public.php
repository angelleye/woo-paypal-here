<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Paypal_Here_Woocommerce
 * @subpackage Paypal_Here_Woocommerce/public
 * @author     Angell EYE <service@angelleye.com>
 */
class Paypal_Here_Woocommerce_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    public $checkout;
    public $order;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->checkout = new Paypal_Here_Woocommerce_Checkout();
        $this->home_url = is_ssl() ? home_url('/', 'https') : home_url('/');
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        $this->paypal_here_endpoint_url = !empty($this->paypal_here_settings['paypal_here_endpoint_url']) ? $this->paypal_here_settings['paypal_here_endpoint_url'] : 'paypal-here';
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        global $wp_query, $wp, $wp_styles;
        $wp->query_vars;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {
            foreach ($wp_styles->registered as $handle => $data) {
                wp_deregister_style($handle);
                wp_dequeue_style($handle);
            }
            wp_register_style('jquery-ui-styles', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
            wp_enqueue_style($this->plugin_name . 'bootstrap_css', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/paypal-here-woocommerce-public.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        global $wp_query, $wp;
        $wp->query_vars;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {
            wp_enqueue_script($this->plugin_name . 'popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . 'bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script('jquery-ui-autocomplete');
            wp_enqueue_script($this->plugin_name . 'input_button', plugin_dir_url(__FILE__) . 'js/bootstrap-number-input.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/paypal-here-woocommerce-public.js', array('jquery', $this->plugin_name . 'input_button'), $this->version, true);
            wp_localize_script($this->plugin_name, 'paypal_here_ajax_param', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'paypal_here_nonce' => wp_create_nonce('paypal_here_nonce')
                    )
            );
        }
    }

    public function is_angelleye_paypal_here_endpoint_url($title = false) {
        global $wp;
        $this->paypal_here_settings = get_option('woocommerce_angelleye_paypal_here_settings');
        if (!empty($wp->query_vars)) {
            if (!empty($this->paypal_here_settings['paypal_here_endpoint_url']) && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == $this->paypal_here_settings['paypal_here_endpoint_url'] && strpos($title, 'not found') !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function angelleye_paypal_here_woocommerce_locate_template($template, $template_name, $template_path) {
        global $wp_query, $wp;
        $wp->query_vars;
        global $woocommerce;
        if (!is_null($wp_query) && !is_admin() && is_main_query() && !empty($wp->query_vars['name']) && $wp->query_vars['name'] == 'paypal-here') {
            $_template = $template;
            if (!$template_path)
                $template_path = $woocommerce->template_url;

            $plugin_path = PAYPAL_HERE_PLUGIN_DIR . '/templates/';
            $template = locate_template(
                    array(
                        $template_path . $template_name,
                        $template_name
                    )
            );
            if (!$template && file_exists($plugin_path . $template_name))
                $template = $plugin_path . $template_name;

            if (!$template)
                $template = $_template;

            // Return what we found
            return $template;
        } else {
            return $template;
        }
    }

    public function paypal_here_get_modal_body() {
        ob_start();
        global $wpdb, $woocommerce, $post;
        $product_id = absint($_POST['product_id']);
        check_ajax_referer('paypal_here_nonce', 'security');
        $product = wc_get_product($product_id);
        setup_postdata($product);
        $GLOBALS['product'] = $product;
//        if (empty($product) || !$product->is_visible() || !$product->is_purchasable() || !$product->is_in_stock()) {
//            continue;
//        }
        $input_id = uniqid('quantity_');
        $input_name = 'quantity';
        $input_value = '1';
        $min_value = apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product);
        $max_value = apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product);
        $min_value = max($min_value, 0);
        $max_value = 0 < $max_value ? $max_value : '';
        if ('' !== $max_value && $max_value < $min_value) {
            $max_value = $min_value;
        }
        $step = apply_filters('woocommerce_quantity_input_step', 1, $product);
        $pattern = apply_filters('woocommerce_quantity_input_pattern', has_filter('woocommerce_stock_amount', 'intval') ? '[0-9]*' : '');
        $inputmode = apply_filters('woocommerce_quantity_input_inputmode', has_filter('woocommerce_stock_amount', 'intval') ? 'numeric' : '');
        ?>
        <div class="container-fluid">
            <form>
                <div class="row form-group">
                    <div class="col-9 col-sm-6">
                        <input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>">
                        <span><?php echo $product->get_title(); ?></span>
                    </div>
                    <div class="col-3 col-sm-3 tal">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="col-12 col-sm-12 mtonerem">
                        <?php if ($max_value && $min_value === $max_value) {
                            ?>
                            <div class="quantity hidden form-group">
                                <input type="hidden" id="<?php echo esc_attr($input_id); ?>" class="input-text qty text form-control paypal_here_number_input" name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($min_value); ?>" />
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="quantity form-group">
                                <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php esc_html_e('Quantity', 'woocommerce'); ?></label>
                                <input type="number" id="<?php echo esc_attr($input_id); ?>" class="input-text qty text form-control paypal_here_number_input" step="<?php echo esc_attr($step); ?>" min="<?php echo esc_attr($min_value); ?>" max="<?php echo esc_attr(0 < $max_value ? $max_value : empty($max_value) ? 99 : '' ); ?>" name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>" title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce') ?>" size="4" pattern="<?php echo esc_attr($pattern); ?>" inputmode="<?php echo esc_attr($inputmode); ?>" />
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <?php
        $data['html'] = ob_get_clean();
        wp_send_json_success($data);
    }

    public function paypal_here_add_to_cart() {
        global $wpdb, $post, $product;
        $product_id = '';
        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }
        check_ajax_referer('paypal_here_nonce', 'security');
        WC()->shipping->reset_shipping();
        if (empty($post->ID)) {
            $product_id = $_POST['product_id'];
        } else {
            $product_id = $post->ID;
        }
        $product = wc_get_product($product_id);
        if (is_object($product)) {
            if (!defined('WOOCOMMERCE_CART')) {
                define('WOOCOMMERCE_CART', true);
            }
            $qty = !isset($_POST['qty']) ? 1 : absint($_POST['qty']);
            if ($product->is_type('variable')) {
                $attributes = array_map('wc_clean', $_POST['attributes']);
                if (version_compare(WC_VERSION, '3.0', '<')) {
                    $variation_id = $product->get_matching_variation($attributes);
                } else {
                    $data_store = WC_Data_Store::load('product');
                    $variation_id = $data_store->find_matching_product_variation($product, $attributes);
                }
                WC()->cart->add_to_cart($product->get_id(), $qty, $variation_id, $attributes);
            } elseif ($product->is_type('simple')) {
                WC()->cart->add_to_cart($product->get_id(), $qty);
            }
            WC()->shipping->reset_shipping();
            WC()->cart->calculate_totals();
            $order_id = $this->checkout->create_order(array());
            $order = wc_get_order($order_id);
            $order->calculate_totals();
            WC()->session->set('angelleye_paypal_here_order_awaiting_payment', $order_id);
            WC()->cart->empty_cart();
            if (is_wp_error($order_id)) {
                throw new Exception($order_id->get_error_message());
            } else {
                $this->angelleye_paypal_here_redirect(add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url));
            }
        }
    }

    public function angelleye_paypal_here_redirect($location) {
        if (is_ajax()) {
            wp_send_json(array(
                'result' => 'success',
                'redirect' => $location
            ));
            exit();
        } else {
            wp_redirect($location);
            exit();
        }
    }

    public function paypal_here_apply_coupon_handler($coupon_code, $order_id, $is_delete = false) {
        if (!empty($coupon_code)) {
            if (!empty($coupon_code) && WC()->cart->is_empty() == false) {
                WC()->cart->apply_coupon($coupon_code);
                WC()->shipping->reset_shipping();
                WC()->cart->calculate_totals();
                $order_id = $this->checkout->create_order(array());
                $order = wc_get_order($order_id);
                $order->calculate_totals();
                WC()->session->set('angelleye_paypal_here_order_awaiting_payment', $order_id);
                if (is_wp_error($order_id)) {
                    throw new Exception($order_id->get_error_message());
                } else {
                    if($is_delete == true) {
                        $coupon = new WC_Coupon( $coupon_code );
                        if(!empty($coupon)) {
                            $coupon->delete(true);
                        }
                    }
                    
                    $this->angelleye_paypal_here_redirect(add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url));
                }
            } else {
                if (!empty($order_id) && !empty($coupon_code)) {
                    $order_id = $order_id;
                    $this->order = wc_get_order($order_id);
                    try {
                        $return = $this->order->apply_coupon($coupon_code);
                        if($is_delete == true) {
                            $coupon = new WC_Coupon( $coupon_code );
                            if(!empty($coupon)) {
                                $coupon->delete(true);
                            }
                        }
                        if (is_wp_error($return)) {
                            wc_add_notice($return->get_error_message(), 'error');
                            $this->angelleye_paypal_here_redirect(add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url));
                        }
                        if ($return == true) {
                            wc_add_notice(__('Coupon code applied successfully.', 'woocommerce'), 'success');
                            $this->angelleye_paypal_here_redirect(add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url));
                        }
                    } catch (Exception $ex) {
                        wc_add_notice($ex->getMessage(), 'error');
                        $this->angelleye_paypal_here_redirect(add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $order_id), $this->home_url . $this->paypal_here_endpoint_url));
                    }
                }
            }
        }
    }

    public function paypal_here_apply_coupon() {
        if (!empty($_POST['coupon_code']) && !empty($_POST['order_id'])) {
            $coupon_code = $_POST['coupon_code'];
            $order_id = $_POST['order_id'];
            $this->paypal_here_apply_coupon_handler($coupon_code, $order_id);
        } elseif (!empty($_POST['paypal_here_percentage']) && !empty($_POST['order_id'])) {
            $arg = array('coupon_code' => 'Discount_PayPal_Here' . wp_rand(1, 10000),
                'amount' => str_replace('%', '', $_POST['paypal_here_percentage']),
                'discount_type' => 'percent'
            );
            $order_id = $_POST['order_id'];
            $this->paypal_here_create_coupon($arg);
            $this->paypal_here_apply_coupon_handler($arg['coupon_code'], $order_id, true);
            
                
        } elseif (!empty($_POST['paypal_here_amount']) && !empty($_POST['order_id'])) {
            $arg = array('coupon_code' => 'Discount_PayPal_Here' . wp_rand(1, 10000),
                'amount' => str_replace('$', '', $_POST['paypal_here_amount']),
                'discount_type' => 'fixed_cart'
            );
            $order_id = $_POST['order_id'];
            $this->paypal_here_create_coupon($arg);
            $this->paypal_here_apply_coupon_handler($arg['coupon_code'], $order_id, true);
            
        }
    }

    public function paypal_here_create_coupon($arg) {
        $coupon_code = $arg['coupon_code'];
        $amount = $arg['amount'];
        $discount_type = $arg['discount_type'];

        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );

        $new_coupon_id = wp_insert_post($coupon);


        update_post_meta($new_coupon_id, 'discount_type', $discount_type);
        update_post_meta($new_coupon_id, 'coupon_amount', $amount);
        update_post_meta($new_coupon_id, 'individual_use', 'no');
        update_post_meta($new_coupon_id, 'product_ids', '');
        update_post_meta($new_coupon_id, 'exclude_product_ids', '');
        update_post_meta($new_coupon_id, 'usage_limit', '');
        update_post_meta($new_coupon_id, 'expiry_date', '');
        update_post_meta($new_coupon_id, 'apply_before_tax', 'no');
        update_post_meta($new_coupon_id, 'free_shipping', 'no');
    }

    public function send_to_paypal_here_action() {
        if (!class_exists('Paypal_Here_Woocommerce_Payment')) {
            require_once PAYPAL_HERE_PLUGIN_DIR . 'includes/class-paypal-here-woocommerce-payment.php';
        }
        $payment_gateway = new Paypal_Here_Woocommerce_Payment();
        $payment_gateway->angelleye_paypal_here_process_payment();
    }

}
