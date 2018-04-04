<style>
    html {
        
       
        width: 100%;
        height: 100%;
        margin-top: 0px !important;
    }
    
.angelleye_paypal_here_default_main {
  
 position: absolute;
      overflow: auto;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      border: 15px solid #012269;


}

</style>
<div class="angelleye_paypal_here_default_main">
    
    <div class="container-fluid pt30">  
        <?php echo wc_print_notices(); ?>
    <div class="row">
        <div class="default-ceneter-button">
            <img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/pp_here_flat.png'; ?>" class="img-fluid img-fluid-default ">
            <img src="<?php echo PAYPAL_HERE_ASSET_URL .'public/img/logo-woocommerce@2x.png'; ?>" class="img-fluid img-fluid-default" alt="PayPal Here" >
            <br>
            <p class="text-secondary"><?php echo __('Create and Process WooCommerce Orders Using PayPal Here.', 'paypal-here-woocommerce'); ?></p><br>
            <?php if (!empty($this->paypal_here_settings['uniq_cs']) && !empty($this->paypal_here_settings['uniq_ck'])) { ?>
            <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'view_products')); ?>" role="button"><?php echo __('Create New Order', 'paypal-here-woocommerce'); ?></a></div><br>
            <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'view_pending_orders')); ?>" role="button"><?php echo __('View Pending Orders', 'paypal-here-woocommerce'); ?></a></div>
            <?php } else { 
                echo "<div class='alert alert-warning alert-dismissible fade show mtonerem' role='alert' ><p>" . __('Consumer key and Consumer secret not available', 'paypal-here-woocommerce') . "</p></div>";
            } ?>
        </div>
    </div>  
    <?php include_once 'footer.php'; ?>
    
</div>

