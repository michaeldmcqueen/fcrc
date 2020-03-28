<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fort_Collins_Running_Club
 */

get_header();
?>

    <?php $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>
    <?php $hero = get_field('home_hero'); ?>

    <header id="masthead-grad" class="relative site-header bg-no-repeat bg-cover bg-center mb-5 pt-20 pb-40" style="margin-left: -1.5rem; margin-right: -1.5rem;background-image: url('<?php echo $backgroundImg[0]; ?>')">
        <?php if( $hero): ?>
        <div class="mb-20">
            <h1 class="text-shadow entry-title page-title text-4xl pl-10 md:pl-20 text-white relative z-20 md:w-1/2 xl:w-1/3 leading-none mb-8">
                <?php echo $hero['hero_headline']; ?>
            </h1>
            <a class="bg-green hover:bg-darkGreen text-white ml-10 md:ml-20 text-xl py-3 px-8 rounded md:w-1/5 mx-auto text-center mt-5 relative z-20" href="<?php echo $hero['hero_link']; ?>"><?php echo $hero['hero_link_label']; ?></a>
</div>
        <?php endif; ?>
    </header>

	<div id="primary" class="content-area">

		<main id="main" class="site-main">
        <?php
        // vars
        $next_upcoming_race = get_field('next_upcoming_race');	
        if( $next_upcoming_race ): ?>
            <div class="md:flex md:flex-row text-lg items-center mb-5 bg-lightGreen md:h-16">
                <div class="bg-dark text-white md:w-auto h-16 px-5 md:px-12 text-center flex items-center leading-none">Next Upcoming Race</div>
                <div class="md:flex md:flex-row items-center md:leading-none py-5 px-5 md:p-0">
                    <div class="md:pr-5 md:pl-12 text-2xl font-medium"><?php echo $next_upcoming_race['next_upcoming_race_title']; ?></div>
                    <div class="hidden md:flex">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" fill="#2ECF62"/>
                        </svg>
                    </div>
                    <div class="md:px-5"><?php echo $next_upcoming_race['next_upcoming_race_subtitle']; ?></div>
                    <div class="hidden md:flex">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" fill="#2ECF62"/>
                        </svg>
                    </div>
                    <div class="md:px-5"><?php echo $next_upcoming_race['next_upcoming_race_date']; ?></div>
                </div>
                <a class="hidden md:flex bg-green md:h-16 px-5 justify-center ml-auto" href="<?php echo get_permalink(); ?>"><img src="/wp-content/themes/fcrc/images/arrow-white.svg" /></a>
            </div>
        <?php endif; ?>
        <h2 class="text-4xl pt-10 pb-5 text-center">Latests News & Updates</h2>
        <div class=" h-1 w-20 bg-green m-auto mb-10"></div>

            <div class="md:flex md:flex-row">
                <?php 
                    // the query
                    $the_query = new WP_Query( array(
                        'posts_per_page' => 3,
                    )); 
                    ?>

                    <?php if ( $the_query->have_posts() ) : ?>
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                            <?php get_template_part( 'template-parts/content-teaser', get_post_type() ); ?>
                        <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>

                    <?php else : ?>
                    <p><?php __('No News'); ?></p>
                <?php endif; ?>
            </div>
            <a class="bg-green hover:bg-darkGreen text-white text-xl py-3 px-8 rounded items-center md:w-1/5 mx-auto text-center block mt-10 btn" href="/blog/">All News Posts</a>
            <?php the_content(); ?>
		</main><!-- #main -->

	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
