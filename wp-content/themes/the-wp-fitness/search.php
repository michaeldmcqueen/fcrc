<?php
/**
 * The template for displaying Search Results pages.
 * @package The WP Fitness
 */

get_header(); ?>

<?php
    $left_right = get_theme_mod( 'the_wp_fitness_layout','One Column');
    if($left_right == 'Right Sidebar'){ ?>
        <section id="blog_post">    
            <div class="innerlightbox">
                <div class="container"> 
                    <div class="row">       
                        <div class="col-md-8 col-sm-8">
                            <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>                
                            <?php if ( have_posts() ) :
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();
                                    get_template_part( 'template-parts/content', get_post_format() );           
                                endwhile;
                                  else :
                                    get_template_part( 'no-results' ); 
                                endif; 
                            ?>
                            <div class="navigation">
                                <?php
                                    // Previous/next page navigation.
                                    the_posts_pagination( array(
                                        'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                        'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                                    ) );
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>      
                        <div class="col-md-4 col-sm-4"><?php get_sidebar();?></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
    <?php }else if($left_right == 'Left Sidebar'){ ?>
        <section id="blog_post">
            <div class="innerlightbox">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-4"><?php get_sidebar();?></div>
                        <div class="col-md-8 col-sm-8">
                            <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                            <?php if ( have_posts() ) :
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();
                                    get_template_part( 'template-parts/content', get_post_format() );           
                                endwhile;
                                  else :
                                    get_template_part( 'no-results' ); 
                                endif; 
                            ?>
                            <div class="navigation">
                                <?php
                                    // Previous/next page navigation.
                                    the_posts_pagination( array(
                                        'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                        'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                                    ) );
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </section>
    <?php }else if($left_right == 'One Column'){ ?>
        <section id="blog_post">
            <div class="innerlightbox">
                <div class="container">                    
                    <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();
                            get_template_part( 'template-parts/content', get_post_format() );           
                        endwhile;
                          else :
                            get_template_part( 'no-results' ); 
                        endif; 
                    ?>
                    <div class="navigation">
                        <?php
                            // Previous/next page navigation.
                            the_posts_pagination( array(
                                'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                            ) );
                        ?>
                        <div class="clearfix"></div>
                    </div>                    
                </div>
            </div>
        </section>
    <?php }else if($left_right == 'Three Columns'){ ?>
        <section id="blog_post">
            <div class="innerlightbox">
                <div class="container">
                    <div class="row">
                        <div id="sidebar" class="col-md-3 col-sm-3"><?php dynamic_sidebar('sidebar-1');?></div>
                        <div class="col-md-6 col-sm-6">
                            <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                            <?php if ( have_posts() ) :
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();
                                    get_template_part( 'template-parts/content', get_post_format() );           
                                endwhile;
                                  else :
                                    get_template_part( 'no-results' ); 
                                endif; 
                            ?>
                            <div class="navigation">
                                <?php
                                    // Previous/next page navigation.
                                    the_posts_pagination( array(
                                        'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                        'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                                    ) );
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        </div> 
                        <div id="sidebar" class="col-md-3 col-sm-3"><?php dynamic_sidebar('sidebar-2');?></div>
                    </div>               
                </div>
            </div>
        </section>
    <?php }else if($left_right == 'Four Columns'){ ?>
        <section id="blog_post">
            <div class="innerlightbox">
                <div class="container">
                    <div class="row">
                        <div id="sidebar" class="col-md-3 col-sm-3"><?php dynamic_sidebar('sidebar-1');?></div>
                        <div class="col-md-3 col-sm-3">
                            <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                            <?php if ( have_posts() ) :
                                /* Start the Loop */
                                while ( have_posts() ) : the_post();
                                    get_template_part( 'template-parts/content', get_post_format() );           
                                endwhile;
                                  else :
                                    get_template_part( 'no-results' ); 
                                endif; 
                            ?>
                            <div class="navigation">
                                <?php
                                    // Previous/next page navigation.
                                    the_posts_pagination( array(
                                        'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                        'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                                    ) );
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        </div> 
                        <div id="sidebar" class="col-md-3 col-sm-3"><?php dynamic_sidebar('sidebar-2');?></div>
                        <div id="sidebar" class="col-md-3 col-sm-3"><?php dynamic_sidebar('sidebar-3');?></div>
                    </div>
                </div>
            </div>
        </section>
    <?php }else if($left_right == 'Grid Layout'){ ?>
        <section id="blog_post">
            <div class="innerlightbox">
                <div class="container"> 
                    <div class="row">       
                        <div class="col-md-8 col-sm-8">
                            <h1 class="search-title"><?php printf('Search Results for: %s', '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1> <div class="row">               
                                <?php if ( have_posts() ) :
                                    /* Start the Loop */
                                    while ( have_posts() ) : the_post();
                                        get_template_part( 'template-parts/grid-layout' );           
                                    endwhile;
                                      else :
                                        get_template_part( 'no-results' ); 
                                    endif; 
                                ?>
                            </div>
                            <div class="navigation">
                                <?php
                                    // Previous/next page navigation.
                                    the_posts_pagination( array(
                                        'prev_text'          => __( 'Previous page', 'the-wp-fitness' ),
                                        'next_text'          => __( 'Next page', 'the-wp-fitness' ),
                                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-wp-fitness' ) . ' </span>',
                                    ) );
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>      
                        <div class="col-md-4 col-sm-4"><?php get_sidebar();?></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

<?php get_footer(); ?>