
<div class="row">
    
    
    <?php
    wp_enqueue_script('paypal_here_autoNumeric', PAYPAL_HERE_ASSET_URL . 'public/js/autoNumeric.min.js', array('jquery'), '1.0.0', true);
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
            <div class="table-responsive">
            <table class="table table-hover">
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
                        if (!in_array($key, array('payment_method', 'order_total', 'discount'))) :
                            echo '<tr>';
                           
                            echo '<td>' . $total['label'] . '</th>';
                            echo '<td>' . $total['value'] . '</td>';
                            echo '</tr>';
                        endif;
                    endforeach;
                    
                        echo '<tr class="paypal_here_discount">';
                        echo '<td> ' . 'Discount:' . '</th>';
                        echo '<td>' . '-' .$this->order->get_discount_to_display() . '</td>';
                        echo '</tr>';
                  
                    ?>
                </tbody>
            </table>
            </div>
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
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/coupon.png'; ?>" width="42" alt="..." class="img-check img-check-check"><input type="radio" name="discount_amount" value="coupon" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/percentage.png'; ?>" width="29" alt="..." class="img-check"><input type="radio" name="discount_amount" value="percentage" class="hidden" autocomplete="off"></label>
                                <label><img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/dollar.png'; ?>"  alt="..." class="img-check"><input type="radio" name="discount_amount" value="amount" class="hidden" autocomplete="off"></label>
                            </div>
                            <div>
                                <input type="text" class="form-control discount_field" id="paypal_here_coupon_code" placeholder="Coupon code" name="coupon_code">
                                <input type="text" class="form-control discount_field" id="paypal_here_percentage" placeholder="Percentage" name="paypal_here_percentage" >
                                <input type="text" class="form-control discount_field" id="paypal_here_dollar" placeholder="Amount" name="paypal_here_amount">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary paypal_here_apply_coupon">Apply</button>
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