<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top"> 
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">PayPal Here</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
        /* Primary navigation */
        wp_nav_menu(array(
            'menu' => 'top_menu',
            'depth' => 2,
            'container' => false,
            'menu_class' => 'navbar-nav mr-auto',
            //Process nav menu using our custom nav walker
            'walker' => new Paypal_Here_Woocommerce_Rest_API_Navwalker())
        );
        ?>
    </div>
</nav>
<div class="container-fluid pt50">  
<?php echo wc_print_notices(); ?>