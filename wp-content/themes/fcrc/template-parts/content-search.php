<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fort_Collins_Running_Club
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('md:w-1/3 rounded overflow-hidden border-2 border-lightGreen mx-3 sm:my-3'); ?>>
	<div class="clip-image"><?php fcrc_post_thumbnail(''); ?></div>
	<div class="p-6">
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title text-3xl font-medium leading-none pb-6"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="divider w-12 bg-lightGreen mb-5"></div>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta mb-5">
			<?php
			fcrc_posted_on();
			fcrc_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->


	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer pt-5 flex flex-col">
		<?php fcrc_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</div>
</article><!-- #post-<?php the_ID(); ?> -->
