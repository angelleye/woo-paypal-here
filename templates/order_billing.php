<div class="row">
    <div class="col">
        <div class="col-md-12">
            <p class="h5 text-center">Billing Information</p>
        </div>
        <br>
        <form method="post">
            <input type="hidden" name="last_action" value="order_billing">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_first_name">First name</label>
                    <input type="text" class="form-control" id="billing_first_name" placeholder="First name" name="billing_first_name">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_last_name">Last name</label>
                    <input type="text" class="form-control" id="billing_last_name" placeholder="Password" name="billing_last_name">
                </div>
            </div>
            <div class="form-group">
                <label for="billing_address_1">Street address</label>
                <input type="text" class="form-control" id="billing_address_1" placeholder="Street address" name="billing_address_1">
            </div>
            <div class="form-group">
                <label for="billing_address_2">Address 2</label>
                <input type="text" class="form-control" id="billing_address_2" placeholder="Apartment, suite, unit etc. (optional)" name="billing_address_2">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_city">City</label>
                    <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="City">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_state">State</label>
                    <input type="text" class="form-control" id="billing_state" placeholder="State" name="billing_state">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="billing_postcode">Postcode / ZIP</label>
                    <input type="text" class="form-control" id="billing_postcode" name="billing_postcode" placeholder="Postcode / ZIP">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_country">Country</label>
                    <input type="text" class="form-control" id="billing_postcode" name="billing_country" placeholder="Country">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                </div>
                <div class="form-group col-md-6">
                    <label for="billing_phone">Phone</label>
                    <input type="tel" class="form-control" id="billing_phone" placeholder="Phone" name="billing_phone">
                </div>
            </div>
            <br>
            <div class="form-row btn-group-vertical text-center float-right">
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" name="continue_to_shipping">Continue to Shipping</button>
                </div>
                <div class="form-group col-md-12 ">
                    <button type="submit" class="btn btn-primary" name="skip_shipping">Skip Shipping</button>
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