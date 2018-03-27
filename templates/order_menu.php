<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top"> 
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <button type="button" class="btn btn-primary light-color send_to_paypal_here">SEND TO PAYPAL HERE</button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo $paypal_here_endpoint_url; ?>"><?php echo __('Home', 'paypal-here-woocommerce'); ?></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo esc_url(add_query_arg('actions', 'view_pending_orders')); ?>"> <?php echo __('View Pending Orders', 'paypal-here-woocommerce'); ?></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo esc_url(add_query_arg('actions', 'order_billing')); ?>"><?php echo __('Create New Order', 'paypal-here-woocommerce'); ?></a>
            </li>
        </ul>
        <?php
        /* Primary navigation */
      /*  wp_nav_menu(array(
            'menu' => 'top_menu',
            'depth' => 2,
            'container' => false,
            'menu_class' => 'navbar-nav mr-auto',
            //Process nav menu using our custom nav walker
            'walker' => new Paypal_Here_Woocommerce_Rest_API_Navwalker())
        ); */
        ?>
    </div>
</nav>
<div class="container-fluid pt30 min-content-ht">     
<?php echo wc_print_notices(); ?>
