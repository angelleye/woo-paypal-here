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
                                    <label><input type="checkbox" class="form-check-input" id=""><?php echo $product_obj->get_title(); ?></label>
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
        <script type="text/javascript">




            jQuery(document).ready(function () {
                jQuery(".form-check").click(function () {
                    jQuery('#exampleModal').modal({show: true});
                });
            });
        </script>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span>ADD ITEM</span>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo __('No product found');
    }
    ?>
</div>