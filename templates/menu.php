<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top"> 
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="text-center float-right">
        <img src="<?php echo WOO_PAYPAL_HERE_ASSET_URL .'public/img/ppcom-white.svg'; ?>" class="img-fluid here_header_img">
        <img src="<?php echo WOO_PAYPAL_HERE_ASSET_URL .'public/img/logo-woocommerce@2x.png'; ?>" class="img-fluid here_header_img" alt="PayPal Here" >
    </div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $paypal_here_endpoint_url; ?>"><?php echo __('Dashboard', 'woo-paypal-here'); ?></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo esc_url(add_query_arg('actions', 'view_pending_orders')); ?>"> <?php echo __('View Pending Orders', 'woo-paypal-here'); ?></a>
            </li>
        </ul>
        <?php
        /* wp_nav_menu(array(
          'menu' => 'top_menu',
          'depth' => 2,
          'container' => false,
          'menu_class' => 'navbar-nav mr-auto',
          'walker' => new Woo_PayPal_Here_Rest_API_Navwalker())
          ); */
        ?>
    </div>
</nav>
<div class="container-fluid pt30 min-content-ht">  
    <?php
    echo wc_print_notices();
    