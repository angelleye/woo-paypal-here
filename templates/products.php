<div class="row">
    <?php
    if (!empty($this->product_list)) {
        global $post, $product;
        ?>
        <div class="col">
            <table class="table">
                <tbody>
                    <?php
                    foreach ($this->product_list as $product_id):
                        $product_obj = wc_get_product($product_id);
                        $GLOBALS['product'] = $product_obj;
                        $GLOBALS['post'] = get_post($product_id);
                        $post = get_post($product_id);
                        $product = $product_obj;
                        if (empty($product_obj) || !$product_obj->is_visible() || !$product_obj->is_purchasable() || !$product_obj->is_in_stock()) {
                            continue;
                        }
                        ?>
                    <tr class="open-modal" id="<?php echo $product_obj->get_id(); ?>">
                            <td>
                                <?php if (has_post_thumbnail($post)) { ?>
                                    <img class="angelleye_paypal_here_shop_thumbnail" src="<?php echo get_the_post_thumbnail_url($product_id, 'shop_thumbnail'); ?> ">
                                <?php } else { ?>
                                    <?php echo sprintf('<img src="%s" alt="%s" class="wp-post-image angelleye_paypal_here_shop_thumbnail" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce')) ?>
                                <?php } ?>
                            </td> 
                            <td colspan="2">
                                <div class="form-check">
                                <?php echo $product_obj->get_title(); ?>
                                </div>
                            </td>
                            <td><?php echo $product_obj->get_price_html(); ?></td>
                        </tr>
                        <?php
                        setup_postdata($product_obj);
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="paypal_here_modal" tabindex="-1" role="dialog" aria-labelledby="paypal_here_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button type="button" class="btn btn-light paypal_here_add_to_cart_button">ADD ITEM</button>
                    </div>
                    <div class="modal-body">
                    <?php wp_enqueue_script('wc-add-to-cart-variation'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo __('No product found');
    }
    ?>
</div>