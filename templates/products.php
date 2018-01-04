<div class="row">
    <?php if (!empty($this->product_list)) { ?>
        <div class="col">
            <table class="table">
                <tbody>
                    <?php
                    foreach ($this->product_list as $product):
                        $product_obj = wc_get_product($product);
                        ?>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <label><input type="checkbox" class="form-check-input open-modal" id="<?php echo $product_obj->get_id(); ?>"><?php echo $product_obj->get_title(); ?></label>
                                </div>
                            </td>
                            <td><?php echo $product_obj->get_price_html(); ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <?php
            wp_enqueue_script('wc-add-to-cart-variation');
            global $product, $post, $woocommerce;
            $product_id = 30;
            $post = get_post($product_id);
            $product = wc_get_product($product_id);
            ?>
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button type="button" class="btn btn-light">ADD ITEM</button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form>
                                <div class="row form-group">
                                    <div class="col-9 col-sm-5">
                                        <span><?php echo $product->get_title();?></span>
                                    </div>
                                    <div class="col-3 col-sm-4 tal">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    <div class="col-12 col-sm-12 mtonerem">
                                        <input id="colorful" class="input-text qty text form-control" step="1" min="<?php echo apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ); ?>" max="<?php echo apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ); ?>" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" type="number">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {

                jQuery(".form-check").click(function () {
                    //console.log(jQuery(this).attr("id"));
                    //alert(jQuery(this).attr("id"));
                    jQuery('#exampleModal').modal({show: true});
                });
            });
        </script>


        <?php
    } else {
        echo __('No product found');
    }
    ?>
</div>