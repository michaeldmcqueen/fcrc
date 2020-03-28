<?php
/**
 * Theme functions.
 */

add_action( 'wp_enqueue_scripts', 'core_fitness_enqueue_styles' );
	function core_fitness_enqueue_styles() {
    	$parent_style = 'the-wp-fitness-style'; // Style handle of parent theme.
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'core-fitness-style', get_stylesheet_uri(), array( $parent_style ) );
}

function core_fitness_customizer ( $wp_customize ) {
	
	//Trending Product
	$wp_customize->add_section('core_fitness_products',array(
		'title'	=> __('New Products','core-fitness'),
		'description'=> __('This section will appear below the slider.','core-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));	
	
	$wp_customize->add_setting('core_fitness_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('core_fitness_title',array(
		'label'	=> __('Section Title','core-fitness'),
		'section'=> 'core_fitness_products',
		'setting'=> 'core_fitness_title',
		'type'=> 'text'
	));	

	for ( $count = 0; $count <= 0; $count++ ) {

		$wp_customize->add_setting( 'core_fitness_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		));
		$wp_customize->add_control( 'core_fitness_page' . $count, array(
			'label'    => __( 'Select Page', 'core-fitness' ),
			'section'  => 'core_fitness_products',
			'type'     => 'dropdown-pages'
		));
	}

	//Footer
	$wp_customize->add_section('core_fitness_footer_section',array(
		'title'	=> __('Footer Text','core-fitness'),
		'description'=> __('Add the Copyright Text Here','core-fitness'),
		'panel' => 'the_wp_fitness_panel_id',
	));

	$wp_customize->add_setting('core_fitness_footer_copy',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('core_fitness_footer_copy',array(
		'label'	=> __('Copyright Text','core-fitness'),
		'section'=> 'core_fitness_footer_section',
		'setting'=> 'core_fitness_footer_copy',
		'type'=> 'text'
	));	

}

add_action( 'customize_register', 'core_fitness_customizer' );

define('core_fitness_CREDIT','http://www.themesglance.com/','core-fitness');

if ( ! function_exists( 'core_fitness_credit' ) ) {
	function core_fitness_credit(){
		echo "<a href=".esc_url(core_fitness_CREDIT)." target='_blank'>".esc_html__('ThemesGlance','core-fitness')."</a>";
	}
}
// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
	if (!function_exists('loop_columns')) {
	function loop_columns() {
	return 3; // 3 products per row
	}
}