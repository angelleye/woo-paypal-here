<?php
/**
 * Quick view template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post, $woocommerce;

do_action( 'wc_quick_view_before_single_product' );
?>
<div class="woocommerce quick-view">

	<div class="product">
		

		<div class="quick-view-content entry-summary">

			<?php woocommerce_template_single_title(); ?>
			<?php woocommerce_template_single_price(); ?>
			<?php //woocommerce_template_single_excerpt(); ?>
			<?php woocommerce_template_single_add_to_cart(); ?>

		</div>
	</div>
</div>
