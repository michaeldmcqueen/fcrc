<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fort_Collins_Running_Club
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('md:w-1/3 rounded overflow-hidden border-2 border-lightGreen mx-3 sm:my-3'); ?>>
	<div class="clip-image"><?php fcrc_post_thumbnail(''); ?></div>
	<div class="p-6">
	<?php 
		$categories = get_the_category();
	
		if ( ! empty( $categories ) ) {
			echo '<div class="text-green pb-2 text-sm font-medium uppercase">' . esc_html( $categories[0]->name ) .'</div>';   
		}
	?>
	<?php
		if ( is_singular() ) :
			the_title( '<h3 class="entry-title text-3xl font-medium leading-none pb-6"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		else :
			the_title( '<h3 class="entry-title text-3xl font-medium leading-none pb-6"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
	<?php endif; ?>
	<div class="divider w-12 bg-lightGreen"></div>
	<div class="entry-content pt-6 pb-10">
	<?php the_excerpt(); ?>
		<a class="block mt-8" href="<?php echo get_permalink(); ?>"><img src="/wp-content/themes/fcrc/images/arrow.svg" /></a>
	</div><!-- .entry-content -->
</div>

</article><!-- #post-<?php the_ID(); ?> -->