<div class="row">
<div class="default-ceneter-button">
    <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'order_billing')); ?>" role="button"><?php echo __('Create New Order', ''); ?></a></div><br>
    <?php if( is_user_logged_in()) : ?>
    <div><a class="btn btn-primary w195" href="<?php echo esc_url(add_query_arg('actions', 'view_pending_orders')); ?>" role="button"><?php echo __('View Pending Orders', ''); ?></a></div>
    <?php endif; ?>
</div>
</div>    






