<div class="row">
    <?php
    if (!empty($this->order)) {
        $order_items = $this->order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
        ?>
        <div class="col">
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

                        echo '<tr>';
                        echo '<td>' . $total['label'] . '</th>';
                        echo '<td>' . $total['value'] . '</td>';
                        echo '</tr>';
                    endforeach;
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