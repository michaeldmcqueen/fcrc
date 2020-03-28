<?php
/**
 * The WP Fitness functions and definitions
 * @package The WP Fitness
 */

/* Breadcrumb Begin */
function the_wp_fitness_the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
			echo esc_url( home_url() );
		echo '">';
			bloginfo('name');
		echo "</a> ";
		if (is_category() || is_single()) {
			the_category(',');
			if (is_single()) {
				echo "<span> ";
					the_title();
				echo "</span> ";
			}
		} elseif (is_page()) {
			the_title();
		}
	}
}


/* Theme Setup */
if ( ! function_exists( 'the_wp_fitness_setup' ) ) :

function the_wp_fitness_setup() {

	$GLOBALS['content_width'] = apply_filters( 'the_wp_fitness_content_width', 640 );
	
	load_theme_textdomain( 'the-wp-fitness', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );
	add_image_size('the-wp-fitness-homepage-thumb',240,145,true);
	
    register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'the-wp-fitness' ),
	) );

	add_theme_support( 'custom-background', array(
		'default-color' => 'f1f1f1'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array('image','video','gallery','audio',) );
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', the_wp_fitness_font_url() ) );
	
	// Theme Activation Notice
	global $pagenow;
	
	if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
		add_action( 'admin_notices', 'the_wp_fitness_activation_notice' );
	}
}
endif;
add_action( 'after_setup_theme', 'the_wp_fitness_setup' );

// Notice after Theme Activation
function the_wp_fitness_activation_notice() {
	echo '<div class="notice notice-success is-dismissible welcome">';
		echo '<h3>'. esc_html__( 'Greetings from Themeglance!!', 'the-wp-fitness' ) .'</h3>';
		echo '<p>'. esc_html__( 'A heartful thank you for choosing Themeglance. We promise to deliver only the best to you. Please proceed towards welcome section to get started.', 'the-wp-fitness' ) .'</p>';
		echo '<p><a href="'. esc_url( admin_url( 'themes.php?page=the_wp_fitness_guide' ) ) .'" class="button button-primary">'. esc_html__( 'About Theme', 'the-wp-fitness' ) .'</a></p>';
	echo '</div>';
}

/* Theme Widgets Setup */
function the_wp_fitness_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'the-wp-fitness' ),
		'description'   => __( 'Appears on blog page sidebar', 'the-wp-fitness' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'the-wp-fitness' ),
		'description'   => __( 'Appears on page sidebar', 'the-wp-fitness' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Third Column Sidebar', 'the-wp-fitness' ),
		'description'   => __( 'Appears on page sidebar', 'the-wp-fitness' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'the-wp-fitness' ),
		'description'   => __( 'Appears on footer', 'the-wp-fitness' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'the-wp-fitness' ),
		'description'   => __( 'Appears on footer', 'the-wp-fitness' ),
		'id'            => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 3', 'the-wp-fitness' ),
		'description'   => __( 'Appears on footer', 'the-wp-fitness' ),
		'id'            => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 4', 'the-wp-fitness' ),
		'description'   => __( 'Appears on footer', 'the-wp-fitness' ),
		'id'            => 'footer-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'the_wp_fitness_widgets_init' );

/* Theme Font URL */
function the_wp_fitness_font_url() {
	$font_url = '';
	$ptsans = _x('on','PT Sans font:on or off','the-wp-fitness');
	$roboto = _x('on','Roboto font:on or off','the-wp-fitness');
	$roboto_cond = _x('on','Roboto Condensed font:on or off','the-wp-fitness');
	
	if('off' !== $ptsans || 'off' !==  $roboto || 'off' !== $roboto_cond){
		$font_family = array();
		
		if('off' !== $ptsans){
			$font_family[] = 'PT Sans:300,400,600,700,800,900';
		}
		if('off' !== $roboto){
			$font_family[] = 'Roboto:400,700';
		}		
		if('off' !== $roboto_cond){
			$font_family[] = 'Roboto Condensed:400,700';
		}		
		$font_family[] = 'Montserrat:300,400,600,700,800,900';
		
		$query_args = array(
			'family'	=> urlencode(implode('|',$font_family)),
		);
		
		$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
	}		
	return $font_url;
}

/*radio button sanitization*/
 function the_wp_fitness_sanitize_choices( $input, $setting ) {
    global $wp_customize; 
    $control = $wp_customize->get_control( $setting->id ); 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

/* Theme enqueue scripts */
function the_wp_fitness_scripts() {
	wp_enqueue_style( 'the-wp-fitness-font', the_wp_fitness_font_url(), array() );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap.css' );
	wp_enqueue_style( 'the-wp-fitness-basic-style', get_stylesheet_uri() );
	wp_style_add_data( 'the-wp-fitness-style', 'rtl', 'replace' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/fontawesome-all.css' );
	wp_enqueue_style( 'css-nivo-slider', get_template_directory_uri().'/css/nivo-slider.css' );
	wp_enqueue_script( 'jquery-nivo-slider', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script( 'jquery-bootstrap-scripts', get_template_directory_uri() . '/js/bootstrap.js', array('jquery') );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_style('the-wp-fitness-ie', get_template_directory_uri().'/css/ie.css', array('the-wp-fitness-basic-style'));
	wp_style_add_data( 'the-wp-fitness-ie', 'conditional', 'IE' );
}
add_action( 'wp_enqueue_scripts', 'the_wp_fitness_scripts' );

define('THE_WP_FITNESS_THEMESGLANCE_PRO_THEME_URL','https://www.themesglance.com/premium/fitness-wordpress-theme/','the-wp-fitness');
define('THE_WP_FITNESS_THEMESGLANCE_THEME_DOC','https://www.themesglance.com/docs/wp-fitness/','the-wp-fitness');
define('THE_WP_FITNESS_THEMESGLANCE_LIVE_DEMO','https://themesglance.com/the-wp-fitness/','the-wp-fitness');
define('THE_WP_FITNESS_THEMESGLANCE_FREE_THEME_DOC','https://themesglance.com/docs/free-wp-fitness/','the-wp-fitness');
define('THE_WP_FITNESS_THEMESGLANCE_SUPPORT','https://wordpress.org/support/theme/the-wp-fitness/','the-wp-fitness');
define('THE_WP_FITNESS_THEMESGLANCE_REVIEW','https://www.themesglance.com/support/?view=forum&id=5','the-wp-fitness');

define('the_wp_fitness_CREDIT','https://www.themesglance.com/','the-wp-fitness');

if ( ! function_exists( 'the_wp_fitness_credit' ) ) {
	function the_wp_fitness_credit(){
		echo "<a href=".esc_url(the_wp_fitness_CREDIT)." target='_blank'>".esc_html__('ThemesGlance','the-wp-fitness')."</a>";
	}
}

/* Custom template tags for this theme. */
require get_template_directory() . '/inc/template-tags.php';

/* Customizer additions. */
require get_template_directory() . '/inc/customizer.php';

/* Implement the Custom Header feature. */
require get_template_directory() . '/inc/custom-header.php';

/* Implement the Custom Header feature. */
require get_template_directory() . '/inc/getting-started/getting-started.php';