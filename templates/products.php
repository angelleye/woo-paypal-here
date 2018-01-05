<div class="row">
    <?php if (!empty($this->product_list)) { ?>
        <div class="col">
            <table class="table">
                <tbody>
                    <?php
                    foreach ($this->product_list as $product_id):
                        $product_obj = wc_get_product($product_id);
                        setup_postdata($product_obj);
                        if (empty($product_obj) || !$product_obj->is_visible() || !$product_obj->is_purchasable() || !$product_obj->is_in_stock()) {
                            continue;
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <label><input type="checkbox" class="form-check-input open-modal" id="<?php echo $product_obj->get_id(); ?>"><?php echo $product_obj->get_title(); ?></label>
                                </div>
                            </td>
                            <td><?php echo $product_obj->get_price_html(); ?></td>
                        </tr>
                        <?php
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