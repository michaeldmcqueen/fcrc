<?php
/**
 * The template for displaying 404 pages (Not Found).
 * @package The WP Fitness
 */

get_header(); ?>

<div class="container">
	<div id="wrapper">
        <div class="page-content">					
			<div class="notfound">
				<h1><?php esc_html_e('404 Not Found', 'the-wp-fitness' ); ?></h1>
				<p class="text-404"><?php esc_html_e( 'Looks like you have taken a wrong turn &hellip;','the-wp-fitness' );  ?></p>
				<p class="text-404"><?php esc_html_e( 'Dont worry &hellip; it happens to the best of us.', 'the-wp-fitness' ); ?></p>
				<div class="read-moresec">
            		<a href="<?php echo esc_html(home_url() ); ?>" class="button"><?php esc_html_e( 'Back to Home Page', 'the-wp-fitness' ); ?></a>
				</div>
			</div>			
			<div class="clearfix"></div>
        </div>
	</div>
</div>
<?php get_footer(); ?>