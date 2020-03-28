<?php
/**
 * The WP Fitness Theme Customizer
 * @package The WP Fitness
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function the_wp_fitness_customize_register( $wp_customize ) {	

	//add home page setting pannel
	$wp_customize->add_panel( 'the_wp_fitness_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'Theme Settings', 'the-wp-fitness' ),
	    'description' => __( 'Description of what this panel does.', 'the-wp-fitness' ),
	) );

	//layout setting
	$wp_customize->add_section( 'the_wp_fitness_theme_layout', array(
    	'title'      => __( 'Layout Settings', 'the-wp-fitness' ),
		'priority'   => 30,
		'panel' => 'the_wp_fitness_panel_id'
	) );

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('the_wp_fitness_layout',array(
	        'default' => __('One Column','the-wp-fitness'),
	        'sanitize_callback' => 'the_wp_fitness_sanitize_choices'	        
	    )
    );

	$wp_customize->add_control('the_wp_fitness_layout',
	    array(
	        'type' => 'radio',
	        'label' => __('Do you want this section','the-wp-fitness'),
	        'section' => 'the_wp_fitness_theme_layout',
	        'choices' => array(
	            'Left Sidebar' => __('Left Sidebar','the-wp-fitness'),
	            'Right Sidebar' => __('Right Sidebar','the-wp-fitness'),
	            'One Column' => __('One Column','the-wp-fitness'),
	            'Three Columns' => __('Three Columns','the-wp-fitness'),
	            'Four Columns' => __('Four Columns','the-wp-fitness'),
	            'Grid Layout' => __('Grid Layout','the-wp-fitness')
	        ),
	    )
    );

	//Topbar section
	$wp_customize->add_section('the_wp_fitness_topbar_icon',array(
		'title'	=> __('Topbar Section','the-wp-fitness'),
		'description'	=> __('Add Header Content here','the-wp-fitness'),
		'priority'	=> null,
		'panel' => 'the_wp_fitness_panel_id',
	));

	$wp_customize->add_setting('the_wp_fitness_contact_corporate',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('the_wp_fitness_contact_corporate',array(
		'label'	=> __('Add Phone Number','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_icon',
		'setting'	=> 'the_wp_fitness_contact_corporate',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('the_wp_fitness_email_corporate',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_wp_fitness_email_corporate',array(
		'label'	=> __('Add Email','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_icon',
		'setting'	=> 'the_wp_fitness_email_corporate',
		'type'		=> 'text'
	));


	//Social Icons(topbar)
	$wp_customize->add_section('the_wp_fitness_topbar_header',array(
		'title'	=> __('Social Icon Section','the-wp-fitness'),
		'description'	=> __('Add Header Content here','the-wp-fitness'),
		'priority'	=> null,
		'panel' => 'the_wp_fitness_panel_id',
	));

	$wp_customize->add_setting('the_wp_fitness_youtube_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_wp_fitness_youtube_url',array(
		'label'	=> __('Add Youtube link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_header',
		'setting'	=> 'the_wp_fitness_youtube_url',
		'type'		=> 'url'
	));

	$wp_customize->add_setting('the_wp_fitness_facebook_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_wp_fitness_facebook_url',array(
		'label'	=> __('Add Facebook link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_header',
		'setting'	=> 'the_wp_fitness_facebook_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('the_wp_fitness_twitter_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_wp_fitness_twitter_url',array(
		'label'	=> __('Add Twitter link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_header',
		'setting'	=> 'the_wp_fitness_twitter_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('the_wp_fitness_rss_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_wp_fitness_rss_url',array(
		'label'	=> __('Add RSS link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_topbar_header',
		'setting'	=> 'the_wp_fitness_rss_url',
		'type'	=> 'url'
	));


	//home page slider
	$wp_customize->add_section( 'the_wp_fitness_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'the-wp-fitness' ),
		'priority'   => 30,
		'panel' => 'the_wp_fitness_panel_id'
	) );

	for ( $count = 1; $count <= 4; $count++ ) {

		// Add color scheme setting and control.
		$wp_customize->add_setting( 'the_wp_fitness_slidersettings-page-' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		) );

		$wp_customize->add_control( 'the_wp_fitness_slidersettings-page-' . $count, array(
			'label'    => __( 'Select Slide Image Page', 'the-wp-fitness' ),
			'section'  => 'the_wp_fitness_slidersettings',
			'type'     => 'dropdown-pages'
		) );

	}

	//Trainer
	$wp_customize->add_section('the_wp_fitness_about',array(
		'title'	=> __('Trainer Section','the-wp-fitness'),
		'description'=> __('This section will appear below the slider.','the-wp-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));	
	
	$wp_customize->add_setting('the_wp_fitness_sec1_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_wp_fitness_sec1_title',array(
		'label'	=> __('Section Title','the-wp-fitness'),
		'section'=> 'the_wp_fitness_about',
		'setting'=> 'the_wp_fitness_sec1_title',
		'type'=> 'text'
	));
	
	$wp_customize->add_setting('the_wp_fitness_sec1_subtitle',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_wp_fitness_sec1_subtitle',array(
		'label'	=> __('Section Sub-Title','the-wp-fitness'),
		'section'=> 'the_wp_fitness_about',
		'setting'=> 'the_wp_fitness_sec1_subtitle',
		'type'=> 'text'
	));

	$wp_customize->add_setting('the_wp_fitness_trainer_name',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_wp_fitness_trainer_name',array(
		'label'	=> __('read more text','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_about',
		'setting'	=> 'the_wp_fitness_trainer_name',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('the_wp_fitness_trainer_link',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control('the_wp_fitness_trainer_link',array(
		'label'	=> __('read more link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_about',
		'type'		=> 'text'
	));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	foreach($categories as $category){
	if($i==0){
	$default = $category->slug;
	$i++;
	}
	$cats[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('the_wp_fitness_blogcategory_setting',array(
		'default'	=> 'select',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('the_wp_fitness_blogcategory_setting',array(
		'type'    => 'select',
		'choices' => $cats,
		'label' => __('Select Category to display Latest Post','the-wp-fitness'),
		'section' => 'the_wp_fitness_about',
	));


	//Gallery
	$wp_customize->add_section('the_wp_fitness_gallery',array(
		'title'	=> __('Gallery Section','the-wp-fitness'),
		'description'	=> __('Add Gallery sections below.','the-wp-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_gallery1_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_gallery1_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_gallery',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_gallery2_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_gallery2_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_gallery',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_gallery3_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_gallery3_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_gallery',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_gallery4_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_gallery4_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_gallery',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_gallery5_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_gallery5_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_gallery',
	));


	//About
	$wp_customize->add_section('the_wp_fitness_about1',array(
		'title'	=> __('About Section','the-wp-fitness'),
		'description'	=> __('Add About sections below.','the-wp-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));

	$post_list = get_posts();
	$i = 0;
	foreach($post_list as $post){
		$posts[$post->post_title] = $post->post_title;
	}

	$wp_customize->add_setting('the_wp_fitness_about_setting',array(
		'sanitize_callback' => 'the_wp_fitness_sanitize_choices',
	));
	$wp_customize->add_control('the_wp_fitness_about_setting',array(
		'type'    => 'select',
		'choices' => $posts,
		'label' => __('Select post','the-wp-fitness'),
		'section' => 'the_wp_fitness_about1',
	));

	$wp_customize->add_setting('the_wp_fitness_about_name',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('the_wp_fitness_about_name',array(
		'label'	=> __('read more text','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_about1',
		'setting'	=> 'the_wp_fitness_about_name',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('the_wp_fitness_about_link',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control('the_wp_fitness_about_link',array(
		'label'	=> __('read more link','the-wp-fitness'),
		'section'	=> 'the_wp_fitness_about1',
		'type'		=> 'text'
	));

	//Footer
	$wp_customize->add_section('the_wp_fitness_copy_text',array(
		'title'	=> __('Copyright Text','the-wp-fitness'),
		'description'=> __('This section will appear in the footer','the-wp-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));	
	
	$wp_customize->add_setting('the_wp_fitness_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_wp_fitness_footer_text',array(
		'label'	=> __('Text','the-wp-fitness'),
		'section'=> 'the_wp_fitness_copy_text',
		'setting'=> 'the_wp_fitness_footer_text',
		'type'=> 'text'
	));

}
add_action( 'customize_register', 'the_wp_fitness_customize_register' );	


/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class the_wp_fitness_customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'The_WP_Fitness_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new The_WP_Fitness_Customize_Section_Pro(
				$manager,
				'example_1',
				array(
					'priority'   => 9,
					'title'    => esc_html__( 'Upgrade to Pro', 'the-wp-fitness' ),
					'pro_text' => esc_html__( 'Go Pro',         'the-wp-fitness' ),
					'pro_url'  => esc_url('https://www.themesglance.com/premium/fitness-wordpress-theme/'),
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'the-wp-fitness-customize-controls', trailingslashit( get_template_directory_uri() ) . '/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'the-wp-fitness-customize-controls', trailingslashit( get_template_directory_uri() ) . '/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
the_wp_fitness_customize::get_instance();