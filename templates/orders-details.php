
<div class="row">
    
    
    <?php
    wp_enqueue_style('jquery-ui-styles');
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
            <table class="table">
                <tbody>
                    <?php
                    
                    foreach ($order_items as $item_id => $item) :
                        $product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
                        echo '<tr>';
                        echo '<td>' . $item->get_name() . ' &times <span class="badge badge-primary">' . $item->get_quantity() . '</span>' . '</td>';
                        echo '<td>' . $this->order->get_formatted_line_subtotal($item) . '</td>';
                        echo '</tr>';
                    endforeach;
                    foreach ($this->order->get_order_item_totals() as $key => $total) :
                        if (!in_array($key, array('payment_method', 'order_total'))) :
                            echo '<tr>';
                            echo '<td>' . $total['label'] . '</th>';
                            echo '<td>' . $total['value'] . '</td>';
                            echo '</tr>';
                        endif;
                    endforeach;
                    if (!array_key_exists('discount', $this->order->get_order_item_totals())) :
                        echo '<tr class="paypal_here_discount">';
                        echo '<td>' . 'Discount:' . '</th>';
                        echo '<td>' . $this->order->get_discount_to_display() . '</td>';
                        echo '</tr>';
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="paypal_here_modal_discount" tabindex="-1" role="dialog" aria-labelledby="paypal_here_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header"  style="text-align: center;display: inline;">
                        <h5 class="modal-title" id="paypal_here_modal_discountLabel">Discount</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-group">
                            <div class="text-right form-group"> <?php echo $this->order->get_discount_to_display(); ?></div>
                            <div>
                                <input type="text" class="form-control" id="coupon_code" placeholder="Coupon code" name="coupon_code">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary paypal_here_apply_coupon">Apply coupon</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo __('No Pending order found');
    }
    ?>
</div>