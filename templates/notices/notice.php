<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ) {
	return;
}

?>
<div class="alert alert-warning alert-dismissible fade show mtonerem" role="alert">
    <?php foreach ( $messages as $message ) : ?>
		<?php echo wp_kses_post( $message ) .PHP_EOL; ?>
	<?php endforeach; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

