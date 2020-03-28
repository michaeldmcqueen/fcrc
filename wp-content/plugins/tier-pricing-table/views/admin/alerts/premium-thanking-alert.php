<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var string $accountUrl
 * @var string $contactUsUrl
 */
?>
<div class="tpt-alert">

    <div class="tpt-alert__text">
        <div class="tpt-alert__inner">
            <?php
                _e( 'Thanks! You are using premium version of the plugin!', 'tier-pricing-table' );
            ?>
        </div>
    </div>

    <div class="tpt-alert__buttons">
        <div class="tpt-alert__inner">
            <a class="tpt-button tpt-button--accent" href="<?php echo $accountUrl; ?>"><?php _e( 'My Account',
			        'tier-pricing-table' ); ?></a>
            <a class="tpt-button tpt-button--default" href="<?php echo $contactUsUrl; ?>"><?php _e( 'Contact us', 'tier-pricing-table' ); ?></a>
        </div>
    </div>
</div>