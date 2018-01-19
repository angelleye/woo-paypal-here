<div class="row">
    <?php
    global $wp;
    $url_part = add_query_arg(array(), $wp->request);
    ?>
    <div class="col">
        <form class="form-inline fr" method="GET" action="<?php echo esc_url(add_query_arg(array())); ?>">
            <div class="input-group mb-3">
                <input type="hidden" name="actions" value="view_products">
                <input name="search" type="text" class="form-control" placeholder="Keywords" aria-label="Keywords" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>