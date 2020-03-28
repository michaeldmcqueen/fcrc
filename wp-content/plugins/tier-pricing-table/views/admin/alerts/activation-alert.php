<?php if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * @var string $link
 */
?>

<div id="message" class="updated notice is-dismissible">
    <p>
        <strong>
			<?php printf(
				__( 'Thanks for installing Tiered Price Table! You can customize it %s', 'tier-pricing-table' ),
				'<a href="' . $link . '">' . sprintf( __( 'here', 'tier-pricing-table' ) . '</a>' ) );
			?>
        </strong>
    </p>
    <button type="button" class="notice-dismiss"></button>
</div>