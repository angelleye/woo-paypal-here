<?php



    class Paypal_Here_Woocommerce_Quick_View {

        private $quick_view_trigger;

        /**
         * __construct function.
         */
        public function __construct() {

            // Default option
            add_option('quick_view_trigger', 'button');

            // Load options
            $this->quick_view_trigger = get_option('quick_view_trigger');

            // Scripts
            add_action('wp_enqueue_scripts', array($this, 'scripts'), 11);

            // Show a product via API
            

            // Settings
           // add_filter('woocommerce_general_settings', array($this, 'settings'));
        }

        

        /**
         * scripts function.
         */
        public function scripts() {
            global $woocommerce;

            do_action('wc_quick_view_enqueue_scripts');

            wp_enqueue_script('prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.min.js', array('jquery'), $woocommerce->version, true);
            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_style('woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css');
            wp_enqueue_style('wc_quick_view', plugins_url('assets/css/style.css', __FILE__));

            switch ($this->quick_view_trigger) {
                case 'non_ajax' :
                    $ajax_cart_en = get_option('woocommerce_enable_ajax_add_to_cart') === 'yes' ? true : false;

                    if ($ajax_cart_en) {
                        // Read more buttons and add-to-cart buttons of products that do not declare ajax-add-to-cart support
                        $selector = "'.product a.button:not(.add_to_cart_button), .product a.button:not(.ajax_add_to_cart)'";
                    } else {
                        $selector = "'.product a.button'";
                        add_filter('add_to_cart_url', array($this, 'get_quick_view_url'));
                    }

                    add_filter('addons_add_to_cart_url', array($this, 'get_quick_view_url'));
                    add_filter('variable_add_to_cart_url', array($this, 'get_quick_view_url'));
                    add_filter('grouped_add_to_cart_url', array($this, 'get_quick_view_url'));
                    add_filter('external_add_to_cart_url', array($this, 'get_quick_view_url'));
                    add_filter('bundle_add_to_cart_url', array($this, 'get_quick_view_url'), 11);
                    add_filter('woocommerce_composite_add_to_cart_url', array($this, 'get_quick_view_url'), 11);
                    add_filter('not_purchasable_url', array($this, 'get_quick_view_url'));
                    add_filter('woocommerce_product_add_to_cart_url', array($this, 'get_quick_view_url_for_product'), 10, 2);
                    break;
                default :
                    $selector = "'a.quick-view-button'";
                    break;
            }

            $selector = apply_filters('quick_view_selector', $selector);

            $js = "
					$(document).on( 'click', " . $selector . ", function() {

						$.fn.prettyPhoto({
							social_tools: false,
							theme: 'pp_woocommerce pp_woocommerce_quick_view',
							opacity: 0.8,
							modal: false,
							horizontal_padding: 40,
							changepicturecallback: function() {
								jQuery('.quick-view-content .variations_form').wc_variation_form();
								jQuery('.quick-view-content .variations_form').trigger( 'wc_variation_form' );
								jQuery('.quick-view-content .variations_form .variations select').change();
								var container = jQuery('.quick-view-content').closest('.pp_content_container');
								jQuery('body').trigger('quick-view-displayed');
							}
						});

						$.prettyPhoto.open( decodeURIComponent( $(this).attr( 'href' ) ) );

						return false;
					});
				";

            if (function_exists('wc_enqueue_js')) {
                wc_enqueue_js($js);
            } else {
                $woocommerce->add_inline_js($js);
            }
        }

        /**
         * get_quick_view_url function.
         */
        public function get_quick_view_url() {
            global $product;

            $link = add_query_arg(
                    apply_filters('woocommerce_loop_quick_view_link_args', urlencode_deep(array(
                'wc-api' => 'WC_Quick_View',
                'product' => $product->get_id(),
                'width' => '90%',
                'height' => '90%',
                'ajax' => 'true'
                    ))), home_url('/')
            );

            return esc_url_raw($link);
        }

        public function get_quick_view_url_for_product($url, $product) {
            $url = $this->get_quick_view_url();

            return $url;
        }

        /**
         * quick_view function.
         */
        public function quick_view() {
            global $woocommerce, $post;

            $product_id = absint($_GET['product']);

            if ($product_id) {

                // Get product ready
                $post = get_post($product_id);

                setup_postdata($post);

                wc_get_template(
                        'quick-view.php', array(), 'woocommerce-quick-view', PAYPAL_HERE_PLUGIN_DIR . '/templates/'
                );
            }

            exit;
        }
    }
   // $GLOBALS['Paypal_Here_Woocommerce_Quick_View'] = new Paypal_Here_Woocommerce_Quick_View();


