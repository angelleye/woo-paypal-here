<div class="row">
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
                    <td><?php echo $product_obj->get_title(); ?></td>
                    <td><?php echo $product_obj->get_price_html(); ?></td>
                </tr>
                <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>