<div class="row">
    <div class="col">
        <div class="col-md-12">
            <p class="h5 text-center"><?php echo __('Billing Information', 'paypal-here-woocommerce'); ?></p>
        </div>
        <br>
        <form method="post">
            <input type="hidden" name="last_action" value="order_billing">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_first_name"><?php echo __('First name', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_first_name" placeholder="<?php echo __('First name', 'paypal-here-woocommerce'); ?>" name="billing_first_name">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_last_name"><?php echo __('Last name', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_last_name" placeholder="<?php echo __('Last name', 'paypal-here-woocommerce'); ?>" name="billing_last_name">
                </div>
            </div>
            <div class="form-group">
                <label for="billing_address_1"><?php echo __('Street address', 'paypal-here-woocommerce'); ?></label>
                <input type="text" class="form-control" id="billing_address_1" placeholder="<?php echo __('Street address', 'paypal-here-woocommerce'); ?>" name="billing_address_1">
            </div>
            <div class="form-group">
                <label for="billing_address_2"><?php echo __('Address 2', 'paypal-here-woocommerce'); ?></label>
                <input type="text" class="form-control" id="billing_address_2" placeholder="<?php echo __('Apartment, suite, unit etc. (optional)', 'paypal-here-woocommerce'); ?>" name="billing_address_2">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_city"><?php echo __('City', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="<?php echo __('City', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_state"><?php echo __('State', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_state" placeholder="<?php echo __('State', 'paypal-here-woocommerce'); ?>" name="billing_state">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_postcode"><?php echo __('Postcode / ZIP', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" placeholder="<?php echo __('Postcode / ZIP', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_country"><?php echo __('Country', 'paypal-here-woocommerce'); ?></label>
                    <input type="text" class="form-control" id="billing_country" name="billing_country" placeholder="<?php echo __('Country', 'paypal-here-woocommerce'); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_email"><?php echo __('Email', 'paypal-here-woocommerce'); ?></label>
                    <input type="billing_email" class="form-control" id="billing_email" placeholder="<?php echo __('Email', 'paypal-here-woocommerce'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_phone"><?php echo __('Phone', 'paypal-here-woocommerce'); ?></label>
                    <input type="tel" class="form-control" id="billing_phone" placeholder="<?php echo __('Phone', 'paypal-here-woocommerce'); ?>" name="billing_phone">
                </div>
            </div>
            <br>

            <div class="form-row btn-group-vertical text-center float-right">
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" value="continue_to_shipping" name="action"><?php echo __('Continue to Shipping', 'paypal-here-woocommerce'); ?></button>
                </div>
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" value="skip_shipping" name="action"><?php echo __('Skip Shipping', 'paypal-here-woocommerce'); ?></button>
                </div>
            </div>
    </div>
</form>
</div>
<br>
