<div class="row">
    <?php
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
            <table class="table ">
                <tbody>
                    <?php
                    foreach ($order_items as $item_id => $item) :
                        $product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
                        echo '<tr>';
                        echo '<td>' . $item->get_name() . ' &times <span class="badge badge-primary">' . $item->get_quantity() . '</strong>' . '</td>';
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

                    if (!in_array('discount', $this->order->get_order_item_totals())) :
                        echo '<tr>';
                        echo '<td class="paypal_here_discount">' . 'Discount:' . '</th>';
                        echo '<td>' . $this->order->get_discount_to_display() . '</td>';
                        echo '</tr>';
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
                    <?php
                } else {
                    echo __('No Pending order found');
                }
                ?>
</div>