<?php
/**
 * The Header for our theme.
 * @package The WP Fitness
 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <div id="header">
    <div class="header-top">
      <div class="container">
        <div class="row">
          <div class="top-contact col-md-3 col-xs-12 col-sm-3">
            <?php if( get_theme_mod( 'the_wp_fitness_contact_corporate','' ) != '') { ?>
              <span class="call"><i class="fa fa-phone" aria-hidden="true"></i><?php echo esc_html( get_theme_mod('the_wp_fitness_contact_corporate',__('520-565-5896','the-wp-fitness') )); ?></span>
             <?php } ?>
          </div>   
          <div class="top-contact col-md-3 col-xs-12 col-sm-3">
            <?php if( get_theme_mod( 'the_wp_fitness_email_corporate','' ) != '') { ?>
              <span class="email_corporate"><i class="fa fa-envelope" aria-hidden="true"></i><?php echo esc_html( get_theme_mod('the_wp_fitness_email_corporate',__('example.com','the-wp-fitness')) ); ?></span>
            <?php } ?>
          </div>
          <div class="social-media col-md-6 col-sm-6 col-xs-12">
            <?php if( get_theme_mod( 'the_wp_fitness_youtube_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'the_wp_fitness_youtube_url','' ) ); ?>"><i class="fab fa-youtube" aria-hidden="true"></i></a>
            <?php } ?>
            <?php if( get_theme_mod( 'the_wp_fitness_facebook_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'the_wp_fitness_facebook_url','' ) ); ?>"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <?php } ?>
            <?php if( get_theme_mod( 'the_wp_fitness_twitter_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'the_wp_fitness_twitter_url','' ) ); ?>"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <?php } ?>
            <?php if( get_theme_mod( 'the_wp_fitness_rss_url','' ) != '') { ?>
              <a href="<?php echo esc_url( get_theme_mod( 'the_wp_fitness_rss_url','' ) ); ?>"><i class="fas fa-rss" aria-hidden="true"></i></a>
            <?php } ?>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="menu-sec">
      <div class="toggle"><a class="toggleMenu" href="#"><?php esc_html_e('Menu','the-wp-fitness'); ?></a></div>
      <div class="container">
        <div class="row">
          <div class="logo col-md-4 col-sm-4 wow bounceInDown">
              <?php if( has_custom_logo() ){ the_wp_fitness_the_custom_logo();
               }else{ ?>
              <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
              <?php $description = get_bloginfo( 'description', 'display' );
              if ( $description || is_customize_preview() ) : ?> 
                <p class="site-description"><?php echo esc_html($description); ?></p>       
              <?php endif; }?>
          </div>        
          <div class="menubox col-md-8 col-sm-8 ">
            <div class="nav">
              <?php wp_nav_menu( array('theme_location'  => 'primary') ); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
   