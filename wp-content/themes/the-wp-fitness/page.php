<?php
/**
 * The template for displaying all pages.
 * @package The WP Fitness
 */

get_header(); ?>

<?php do_action('the_wp_fitness_page_footer'); ?>

<?php while ( have_posts() ) : the_post(); ?>
    <div class="title-box">
    	<div class="container">
    		<h1><?php the_title();?></h1>
    	</div>
    </div>

    <div id="wrapper" class="container">
        <div class="main-wrap-box">
            <div class="bradcrumbs">
                <?php the_wp_fitness_the_breadcrumb(); ?>
            </div>
            <div class="feature-box">
                <img src="<?php the_post_thumbnail_url('full'); ?>" width="100%">
            </div>
            <?php the_content(); ?>
            <?php wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'the-wp-fitness' ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span class="page-number">',
                    'link_after'  => '</span>',
                    'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>%',
                    'separator'   => '<span class="screen-reader-text">, </span>',
            ) ); ?>       
            <div class="clear"></div>    
        </div>
        <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        ?>
    </div>
<?php endwhile; // end of the loop. 
wp_reset_postdata();?>

<?php do_action('the_wp_fitness_page_footer'); ?>

<?php get_footer(); ?>