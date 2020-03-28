<?php
/**
 * The template part for displaying a message that posts cannot be found.
 * @package The WP Fitness
 */
?>

<header>
	<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'the-wp-fitness' ); ?></h1>
</header>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	
	<p><?php printf( esc_html('Ready to publish your first post? Get started here.','the-wp-fitness'), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'the-wp-fitness' ); ?></p><br />
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Dont worry&hellip; it happens to the best of us.', 'the-wp-fitness' ); ?></p><br />
	<div class="read-moresec">
		<div><a href="<?php echo esc_url( home_url() ); ?>" class="button"><?php esc_html_e( 'Back to Home Page', 'the-wp-fitness' ); ?></a></div>
	</div>
<?php endif; ?>