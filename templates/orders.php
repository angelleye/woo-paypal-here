<div class="row">
    <table class="table ">
        <tbody>
            <?php
            $columns = apply_filters('woocommerce_account_orders_columns', array(
                'order-select' => __('Select', 'woocommerce'),
                'order-total' => __('Total', 'woocommerce'),
            ));
            foreach ($this->order_list as $customer_order) :
                $order = wc_get_order($customer_order);
                $item_count = $order->get_item_count();
                ?>
                <tr>
                    <?php foreach ($columns as $column_id => $column_name) : ?>
                        <td>
                            <?php if ('order-select' === $column_id) : ?>
                                <div class="form-check">
                                    <label><input type="checkbox" class="form-check-input" id=""><time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time></label>
                                </div>
                            <?php elseif ('order-total' === $column_id) : ?>
                                <?php
                                echo $order->get_formatted_order_total();
                                /* translators: 1: formatted order total 2: total order items */
                                //printf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count);
                                ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>