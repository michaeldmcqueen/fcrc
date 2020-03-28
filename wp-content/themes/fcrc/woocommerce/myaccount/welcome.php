<?php
/**
 * WooCommerce Memberships: "Welcome" section
 * Custom template added to the "Member Area"
 */
  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Renders the welcome section for the membership in the my account area.
 *
 * @param array $args {
 *		@type \WC_Memberships_User_Membership $user_membership user membership object
 *		@type int $user_id current user's ID
 * }
 */
?>

<h3><?php esc_html_e( sprintf( 'Welcome, %s!', wp_get_current_user()->display_name ), 'my-textdomain' ); ?></h3>

<?php do_action( 'wc_memberships_before_members_area', 'welcome' ); ?>

<div>
<iframe class="aligncenter" width="560" height="315" src="https://www.youtube.com/embed/YQHsXMglC9A" frameborder="0" allowfullscreen></iframe>
</div>

<p><?php esc_html_e( 'Congratulations, you are a member!', 'my-textdomain' ); ?></p>

<?php do_action( 'wc_memberships_after_members_area', 'welcome' );
