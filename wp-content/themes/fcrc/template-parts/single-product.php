<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fort_Collins_Running_Club
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('content-area md:w-4/5 mx-auto '); ?>>
	<div class="entry-content text-lg">
    <?php the_post_thumbnail(); ?>

        <?php fcrc_post_thumbnail(); ?>
        <?php the_title( '<h1 class="entry-title page-title text-5xl">', '</h1>' ); ?>
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer pt-4">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'fcrc' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
