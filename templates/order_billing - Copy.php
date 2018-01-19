<div class="row">
    <div class="col">
        <div class="col-md-12">
            <p class="h5 text-center"><?php echo __('Billing Information', ''); ?></p>
        </div>
        <br>
        <form method="post">
            <input type="hidden" name="last_action" value="order_billing">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_first_name"><?php echo __('First name'); ?></label>
                    <input type="text" class="form-control" id="billing_first_name" placeholder="First name" name="billing_first_name">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_last_name"><?php echo __('Last name', ''); ?></label>
                    <input type="text" class="form-control" id="billing_last_name" placeholder="Last name" name="billing_last_name">
                </div>
            </div>
            <div class="form-group ">
                <label for="billing_country"><?php echo __('Country', ''); ?></label>
                <?php woocommerce_form_field('billing_country', array('type' => 'country', 'input_class' => array('form-control'))); ?>
            </div>
            <div class="form-group">
                <label for="billing_address_1"><?php echo __('Street address', ''); ?></label>
                <input type="text" class="form-control" id="billing_address_1" placeholder="Street address" name="billing_address_1">
            </div>
            <div class="form-group">
                <label for="billing_address_2"><?php echo __('Address 2', ''); ?></label>
                <input type="text" class="form-control" id="billing_address_2" placeholder="Apartment, suite, unit etc. (optional)" name="billing_address_2">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_city"><?php echo __('City', ''); ?></label>
                    <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="City">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_state"><?php echo __('State', ''); ?></label>
                   
                    <?php woocommerce_form_field('billing_country', array('type' => 'state', 'input_class' => array('form-control'))); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_postcode"><?php echo __('Postcode / ZIP', ''); ?></label>
                    <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" placeholder="Postcode / ZIP">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_email"><?php echo __('Email', ''); ?></label>
                    <input type="billing_email" class="form-control" id="billing_email" placeholder="Email">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_phone"><?php echo __('Phone', ''); ?></label>
                    <input type="tel" class="form-control" id="billing_phone" placeholder="Phone" name="billing_phone">
                </div>
            </div>
            <br>
            <div class="form-row btn-group-vertical text-center float-right">
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" value="continue_to_shipping" name="action"><?php echo __('Continue to Shipping', ''); ?></button>
                </div>
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" value="skip_shipping" name="action"><?php echo __('Skip Shipping', ''); ?></button>
                </div>
            </div>
    </div>
</form>
</div>
