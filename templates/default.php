<style>
    html {
        
       
        width: 100%;
        height: 100%;
        margin-top: 0px !important;
    }
    
.angelleye_woo_paypal_here_default_main {
  
 position: absolute;
      overflow: auto;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      border: 15px solid #012269;


}
.alert {
    margin-bottom: 0px;
}
</style>
<div class="angelleye_woo_paypal_here_default_main">
    
    <div class="container-fluid pt30">  
        <?php echo wc_print_notices(); ?>
    <div class="row">
        <div class="default-ceneter-button">
            <img src="<?php echo WOO_PAYPAL_HERE_ASSET_URL .'public/img/pp_here_flat.png'; ?>" class="img-fluid img-fluid-default ">
            <img src="<?php echo WOO_PAYPAL_HERE_ASSET_URL .'public/img/logo-woocommerce@2x.png'; ?>" class="img-fluid img-fluid-default" alt="PayPal Here" >
            <br>
            <p class="text-secondary"><?php echo __('Create and Process WooCommerce Orders Using PayPal Here.', 'woo-paypal-here'); ?></p>
            <?php if (!empty($this->paypal_here_settings['uniq_cs']) && !empty($this->paypal_here_settings['uniq_ck'])) { ?>
            <br><div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg(array('actions' => 'view_products', 'is_create_new_order' => 'true'))); ?>" role="button"><?php echo __('Create New Order', 'woo-paypal-here'); ?></a></div><br>
            <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'view_pending_orders')); ?>" role="button"><?php echo __('View Pending Orders', 'woo-paypal-here'); ?></a></div>
            <?php } else { 
                echo "<div class='alert alert-warning mtonerem' role='alert' >" . __('Your API keys for WooCommerce are not configured. Please click the Generate WooCommerce REST API Key button in the PayPal Here settings to fix this.', 'woo-paypal-here') . "</div>";
            } ?>
        </div>
    </div>  
    <?php include_once 'footer.php'; ?>
    
</div>

