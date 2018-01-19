<div class="row">
    <?php if (!empty($this->order_list)) { ?>
        <div class="col">
            <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <?php
                    $columns = apply_filters('woocommerce_account_orders_columns', array(
                        'order-select' => __('Select', 'woocommerce'),
                        'order-total' => __('Total', 'woocommerce'),
                    ));
                    foreach ($this->order_list as $customer_order) :
                        $order = wc_get_order($customer_order);
                        $item_count = $order->get_item_count();
                        $billing_first_name = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_first_name : $order->get_billing_first_name();
                        $billing_last_name = version_compare(WC_VERSION, '3.0', '<') ? $order->billing_last_name : $order->get_billing_last_name();
                        if(!empty($billing_first_name) && !empty($billing_last_name) ) {
                            $billing_first_last_name = $billing_first_name . ' ' . $billing_last_name;
                        } else {
                            $billing_first_last_name = 'N/A';
                        }
                        ?>
                        <tr>
                            <?php foreach ($columns as $column_id => $column_name) : ?>
                                <td>
                                    <?php if ('order-select' === $column_id) : ?>
                                        <div class="form-check w-100">
                                            <label><?php echo $billing_first_last_name; ?></label>
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
        </div>
        <?php
    } else {
        echo __('No Pending order found');
    }
    ?>
</div>