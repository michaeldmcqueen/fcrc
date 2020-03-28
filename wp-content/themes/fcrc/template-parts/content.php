<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fort_Collins_Running_Club
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-thumbnail clip-image -mx-6 mb-5">	
			<?php fcrc_post_thumbnail(); ?>
		</div>
	<div class="p-4 content-area md:w-4/5 mx-auto">
		<div class="flex flex-row">
			<div class="text-green pb-1 pr-4 font-medium uppercase"><?php the_time('F d, Y') ?></div>
			<?php 
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					echo '<div class="text-green pb-1 font-medium uppercase">' . esc_html( $categories[0]->name ) .'</div>';   
				}
			?>
		</div>
	<?php the_title( '<h1 class="entry-title page-title text-5xl leading-none pt-2 pb-3">', '</h1>' ); ?>
	<div class="entry-content pt-3 text-lg">
		<?php echo the_content(); ?>
	</div><!-- .entry-content -->
</div>

</article><!-- #post-<?php the_ID(); ?> -->