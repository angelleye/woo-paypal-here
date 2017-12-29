<div class="woocommerce">
    <div class="woocommerce-MyAccount-content">
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <tbody>
                <?php
                foreach ($this->order_list as $customer_order) :
                    $order = wc_get_order($customer_order);
                    $item_count = $order->get_item_count();
                    ?>
                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
                        <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
                                <?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
                                    <?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

                                <?php elseif ('order-number' === $column_id) : ?>
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                        <?php echo _x('#', 'hash before order number', 'woocommerce') . $order->get_order_number(); ?>
                                    </a>

                                <?php elseif ('order-date' === $column_id) : ?>
                                    <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

                                <?php elseif ('order-status' === $column_id) : ?>
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

                                <?php elseif ('order-total' === $column_id) : ?>
                                    <?php
                                    /* translators: 1: formatted order total 2: total order items */
                                    printf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count);
                                    ?>

                                <?php elseif ('order-actions' === $column_id) : ?>
                                    <?php
                                    $actions = wc_get_account_orders_actions($order);
                                    if (!empty($actions)) {
                                        foreach ($actions as $key => $action) {
                                            echo '<a href="' . esc_url($action['url']) . '" class="woocommerce-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
                                        }
                                    }
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