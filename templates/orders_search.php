<div class="row">
    <?php 
    global $wp;
$url_part = add_query_arg(array(),$wp->request);
    
    if (!empty($this->order_list)) { ?>
        <div class="col">
            <form class="form-inline fr" method="GET" action="<?php echo esc_url( add_query_arg(array())); ?>">
                <div class="input-group mb-3">
                    <input type="hidden" name="actions" value="view_pending_orders">
                    <input name="search" type="text" class="form-control" placeholder="Order Number / Name" aria-label="Order Number / Name" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    
    <?php } ?>
</div>