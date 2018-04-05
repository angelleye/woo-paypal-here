<div class="row">
    <?php
    wp_enqueue_script('paypal_here_autoNumeric', PAYPAL_HERE_ASSET_URL . 'public/js/autoNumeric.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('jquery-ui-styles');
    wp_enqueue_style( 'dashicons' );
    if (!empty($this->order)) {
        $order_items = $this->order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
        ?>
        <div class="col">
            <div class="form-group">
                <h2 class="text-center text-primary"><?php echo $this->order->get_formatted_order_total(); ?></h2>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="<?php echo esc_url(add_query_arg('actions', 'view_products', remove_query_arg('order_id'))); ?>" role="button">&plus; Add Item</a>
            </div>
            <input type="hidden" name="order_id" value="<?php echo $this->order->get_id();?>">
            <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <?php
                    foreach ($order_items as $item_id => $item) :
                        $product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
                        echo '<tr>';
                        
                        echo '<td>' . $item->get_name() . ' &times <span class="badge badge-primary">' . $item->get_quantity() . '</span><span title="Delete Product" data-item_id="'. $item_id .'"  class="angelleye_delete_button_paypal_here dashicons dashicons-trash"></span>' . '</td>';
                        echo '<td>' . $this->order->get_formatted_line_subtotal($item) . '</td>';
                        echo '</tr>';
                    endforeach;
                    foreach ($this->order->get_order_item_totals() as $key => $total) :
                        if (!in_array($key, array('payment_method', 'order_total', 'discount', 'shipping'))) :
                            echo '<tr>';
                            echo '<td>' . $total['label'] . '</th>';
                            echo '<td>' . $total['value'] . '</td>';
                            echo '</tr>';
                        endif;
                    endforeach;
                        echo '<tr class="paypal_here_shipping">';
                        echo '<td> ' . 'Shipping:' . '</th>';
                        echo '<td>' . $this->order->get_shipping_to_display() . '</td>';
                        echo '</tr>';
                        echo '<tr class="paypal_here_discount">';
                        echo '<td> ' . 'Discount:' . '</th>';
                        echo '<td>' . '-' .$this->order->get_discount_to_display() . '</td>';
                        echo '</tr>';
                    ?>
                </tbody>
            </table>
            </div>
            <div class="form-row btn-group-vertical text-center float-right">
                <div class="form-group col-md-12 ">
                    <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'order_billing')); ?>" role="button"><?php echo __('Continue to Billing Address', 'woo-paypal-here'); ?></a></div><br>
                </div>
               
            </div>
        </div>
        <div class="modal fade" id="paypal_here_modal_discount" tabindex="-1" role="dialog" aria-labelledby="paypal_here_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header"  style="text-align: center;display: inline;">
                        <h5 class="modal-title" id="paypal_here_modal_discountLabel"><?php echo __('Discount', 'woo-paypal-here'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-group">
                            <div class="text-right form-group"> <?php echo $this->order->get_discount_to_display(); ?></div>
                            <div>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/coupon.png'; ?>" width="42" alt="..." class="discount-img checked"><input type="radio" name="discount_amount" value="coupon" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/percentage.png'; ?>" width="29" alt="..." class="discount-img"><input type="radio" name="discount_amount" value="percentage" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/dollar.png'; ?>"  alt="..." class="discount-img"><input type="radio" name="discount_amount" value="amount" class="hidden" autocomplete="off"></label>
                            </div>
                            <div>
                                <input type="text" class="form-control discount_field" id="paypal_here_coupon_code" placeholder="<?php echo __('Coupon code', 'woo-paypal-here'); ?>" name="coupon_code">
                                <input type="text" class="form-control discount_field" id="paypal_here_percentage" placeholder="<?php echo __('Percentage', 'woo-paypal-here'); ?>" name="paypal_here_percentage" >
                                <input type="text" class="form-control discount_field" id="paypal_here_dollar" placeholder="<?php echo __('Amount', 'woo-paypal-here'); ?>" name="paypal_here_amount">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo __('Close', 'woo-paypal-here'); ?></button>
                        <button type="button" class="btn btn-primary paypal_here_apply_coupon"><?php echo __('Apply', 'woo-paypal-here'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal fade" id="paypal_here_modal_shipping" tabindex="-1" role="dialog" aria-labelledby="paypal_here_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header"  style="text-align: center;display: inline;">
                        <h5 class="modal-title" id="paypal_here_modal_shippingLabel"><?php echo __('Shipping', 'woo-paypal-here'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-group">
                            <div class="text-right form-group"> <?php echo $this->order->get_shipping_to_display(); ?></div>
                            <div>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/postal_code.png'; ?>" width="29" alt="..." class="shipping-img checked"><input type="radio" name="shipping_amount" value="postal_code" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/percentage.png'; ?>" width="29" alt="..." class="shipping-img"><input type="radio" name="shipping_amount" value="percentage" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/dollar.png'; ?>"  alt="..." class="shipping-img"><input type="radio" name="shipping_amount" value="amount" class="hidden" autocomplete="off"></label>
                            </div>
                            <div>
                                <input type="text" class="form-control shipping_field" id="paypal_here_shipping_postal_code" placeholder="<?php echo __('Postal code', 'woo-paypal-here'); ?>" name="paypal_here_shipping_postal_code">
                                <input type="text" class="form-control shipping_field" id="paypal_here_shipping_percentage" placeholder="<?php echo __('Percentage', 'woo-paypal-here'); ?>" name="paypal_here_shipping_percentage" >
                                <input type="text" class="form-control shipping_field" id="paypal_here_shipping_dollar" placeholder="<?php echo __('Amount', 'woo-paypal-here'); ?>" name="paypal_here_shipping_dollar">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo __('Close', 'woo-paypal-here'); ?></button>
                        <button type="button" class="btn btn-primary paypal_here_apply_shipping"><?php echo __('Apply', 'woo-paypal-here'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="col">'. __('No Pending order found') . '</div>';
    }
    ?>
</div>