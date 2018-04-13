<div class="row">
    <?php if (!empty($this->order_list)) { 
        wp_enqueue_style( 'dashicons' );
        ?>
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
                        if(version_compare(WC_VERSION, '3.0', '<')) {
                            if( !empty($order->billing_first_name) || !empty($order->billing_last_name)) {
                                $buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $order->billing_first_name, $order->billing_last_name ) );
                            } elseif ( !empty ($order->billing_country)) {
                                $buyer = trim( $order->billing_country );
                            }
                        } else {
                            if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
                                /* translators: 1: first name 2: last name */
                                $buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $order->get_billing_first_name(), $order->get_billing_last_name() ) );
                            } elseif ( $order->get_billing_company() ) {
                                    $buyer = trim( $order->get_billing_company() );
                            } elseif ( $order->get_customer_id() ) {
                                    $user  = get_user_by( 'id', $order->get_customer_id() );
                                    $buyer = ucwords( $user->display_name );
                            }
                        }
                        ?>
                        <tr class='paypal_here_clickable_row' data-href="<?php echo add_query_arg(array('actions' => 'view_pending_orders', 'order_id' => $customer_order), $this->home_url . $this->paypal_here_endpoint_url); ?>">
                            <?php foreach ($columns as $column_id => $column_name) : ?>
                                <td>
                                    <?php if ('order-select' === $column_id) : ?>
                                        <div class="form-check w-100">
                                            <label><?php echo !empty($buyer) ? $buyer : '#'.$customer_order; ?></label>
                                            <span title="Delete Order" data-order_id="<?php echo $customer_order; ?>"  id="angelleye_delete_pending_order_paypal_here" class="dashicons dashicons-trash"></span>
                                        </div>
                                    <?php elseif ('order-total' === $column_id) : ?>
                                        <?php
                                        echo $order->get_formatted_order_total();
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
        ?>
    
        <div class="default-ceneter-button">
            <p class="text-secondary"><?php echo __('No Pending order found'); ?> </p>
            <br>
            <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'view_products')); ?>" role="button"><?php echo __('Create New Order', 'woo-paypal-here'); ?></a></div><br>
        </div>
       
    <?php 
    }
    ?>
</div>