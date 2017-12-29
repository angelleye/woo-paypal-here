
<div class="woocommerce">
    <div class="woocommerce-MyAccount-content">
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <tbody>
                <?php
                foreach ($this->product_list as $product):
                    echo $product . '<br/>';
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>