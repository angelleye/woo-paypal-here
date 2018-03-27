<div class="row">
    <div class="col">
        <div class="col-md-12">
            <p class="h5 text-center"><?php echo __('Shipping Information', ''); ?></p>
        </div>
        <br>
        <form method="post">
            <input type="hidden" name="last_action" value="order_shipping">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="shipping_first_name"><?php echo __('First name', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_first_name" placeholder="<?php echo __('First name', 'paypal-here-woocommerce'); ?>" name="shipping_first_name">
                </div>
                <div class="form-group col-md-6">
                    <label for="shipping_last_name"><?php echo __('Last name', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_last_name" placeholder="<?php echo __('Last name', 'paypal-here-woocommerce'); ?>" name="shipping_last_name">
                </div>
            </div>
            <div class="form-group">
                <label for="shipping_address_1"><?php echo __('Street address', 'paypal-here-woocommerce'); ?></label>
                <input type="text" class="form-control" id="shipping_address_1" placeholder="<?php echo __('Street address', 'paypal-here-woocommerce'); ?>" name="shipping_address_1">
            </div>
            <div class="form-group">
                <label for="shipping_address_2"><?php echo __('Address 2', 'paypal-here-woocommerce'); ?></label>
                <input type="text" class="form-control" id="shipping_address_2" placeholder="<?php echo __('Apartment, suite, unit etc. (optional)', 'paypal-here-woocommerce'); ?>" name="shipping_address_2">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="shipping_city"><?php echo __('City', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_city" name="shipping_city" placeholder="<?php echo __('City', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="shipping_state"><?php echo __('State', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_state" placeholder="<?php echo __('State', 'paypal-here-woocommerce'); ?>" name="shipping_state">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="shipping_postcode"><?php echo __('Postcode / ZIP', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_postcode" name="shipping_postcode" placeholder="<?php echo __('Postcode / ZIP', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="shipping_country"><?php echo __('Country', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="shipping_country" name="shipping_country" placeholder="<?php echo __('Country', 'paypal-here-woocommerce'); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="shipping_email"><?php echo __('Email', 'paypal-here-woocommerce'); ?></label>
                    <input type="shipping_email" class="form-control" id="shipping_email" placeholder="<?php echo __('Email', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="shipping_phone"><?php echo __('Phone', 'paypal-here-woocommerce'); ?></label>
                    <input type="tel" class="form-control" id="shipping_phone" placeholder="<?php echo __('Phone', 'paypal-here-woocommerce'); ?>" name="shipping_phone">
                </div>
            </div>
            <br>
            <div class="form-row btn-group-vertical text-center float-right">
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" name="continue_to_add_items"><?php echo __('Continue to Order', 'paypal-here-woocommerce'); ?></button>
                </div>
            </div>
    </div>
</form>
</div>
<style>
    .pt50 {
        padding-top: 25px;
    }

</style>