<?php
//about theme info
add_action( 'admin_menu', 'the_wp_fitness_gettingstarted' );
function the_wp_fitness_gettingstarted() {    	
	add_theme_page( esc_html__('Get Started', 'the-wp-fitness'), esc_html__('Get Started', 'the-wp-fitness'), 'edit_theme_options', 'the_wp_fitness_guide', 'the_wp_fitness_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function the_wp_fitness_admin_theme_style() {
   wp_enqueue_style('custom-admin-style', get_template_directory_uri() . '/inc/getting-started/getting-started.css');
}
add_action('admin_enqueue_scripts', 'the_wp_fitness_admin_theme_style');

//guidline for about theme
function the_wp_fitness_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme( 'the-wp-fitness' );
?>
<div class="wrapper-info">
	<div class="col-left">
		<div class="intro">
			<h3><?php esc_html_e( 'Welcome to The WP Fitness Wordpress Theme', 'the-wp-fitness' ); ?></h3>
		</div>
		<div class="color_bg_blue">
			<p>Version: <?php echo esc_html($theme['Version']);?></p>
				<p class="intro_version"><?php esc_html_e( 'Congratulations! You are about to use the most easy to use and felxible WordPress theme.', 'the-wp-fitness' ); ?></p>
				<div class="blink">
					<h4><?php esc_html_e( 'Theme Created By ThemesGlance', 'the-wp-fitness' ); ?></h4>
				</div>
			<div class="intro-text"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getting-started/images/themesglance-logo.png" alt="" /></div>
			<div class="coupon-code"><?php esc_html_e( 'Get', 'the-wp-fitness' ); ?> <span><?php esc_html_e( '20% off', 'the-wp-fitness' ); ?></span> <?php esc_html_e( 'on Premium Theme with the discount code: ', 'the-wp-fitness' ); ?> <span><?php esc_html_e( '" TGYear2018 "', 'the-wp-fitness' ); ?></span></div>
		</div>
		<div class="started">
			<h3><?php esc_html_e( 'Lite Theme Info', 'the-wp-fitness' ); ?></h3>
			<p><?php esc_html_e( 'The WP Fitness is a clean and modern responsive WordPress theme that is constructed specifically for fitness enthusiasts, personal trainers, yoga trainers, weight loss geeks, and gym experts. Its a multipurpose theme that covers businesses such as health, wellness, aerobics, lifestyle, boxing, sports, workout, muscular, physiotherapy, consultancy, and health clubs. This user-friendly WP Fitness theme has a variety of features and functionalities. The Appointment form section is a well-built functionality that adds ease to the process of making an appointment to your fitness center. It includes excellent options such as WooCommerce integration, social media integration, personalization options, testimonial section, banners with Call to Action Button(CTA), and a lot more. The theme has optimized codes which help in giving faster page load time. This SEO friendly WordPress theme makes your site rise high on search engines. Being a mobile-friendly theme, it offers responsive navigation for providing the beautiful user experience. Get this beautiful, strong and stunning theme now!', 'the-wp-fitness')?></p>
			<hr>
			<h3><?php esc_html_e( 'Help Docs', 'the-wp-fitness' ); ?></h3>
			<ul>
				<p><?php esc_html_e( 'The WP Fitness', 'the-wp-fitness' ); ?> <a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'the-wp-fitness' ); ?></a></p>
			</ul>
			<hr>
			<h3><?php esc_html_e( 'Get started with Fitness Theme', 'the-wp-fitness' ); ?></h3>
			<div class="col-left-inner"> 
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getting-started/images/customizer-image.png" alt="" />
			</div>		
			<div class="col-right-inner">
				<p><?php esc_html_e( 'Go to', 'the-wp-fitness' ); ?> <a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e( 'Customizer', 'the-wp-fitness' ); ?> </a> <?php esc_html_e( 'and start customizing your website', 'the-wp-fitness' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Easily customizable ', 'the-wp-fitness' ); ?> </li>
					<li><?php esc_html_e( 'Absolutely free', 'the-wp-fitness' ); ?> </li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-right">
		<div class="col-left-area">
			<h3><?php esc_html_e('Premium Theme Information', 'the-wp-fitness'); ?></h3>
			<hr>
		</div>
		<div class="centerbold">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getting-started/images/responsive-tg.png" alt="" />
			<hr class="firsthr">
			<a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'the-wp-fitness'); ?></a>
			<a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_PRO_THEME_URL ); ?>"><?php esc_html_e('Buy Pro', 'the-wp-fitness'); ?></a>
			<a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'the-wp-fitness'); ?></a>
			<hr class="secondhr">
		</div>
		<h3><?php esc_html_e( 'PREMIUM THEME FEATURES', 'the-wp-fitness'); ?></h3>
		<ul>
		 	<li><?php esc_html_e( 'Theme options using customizer API', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Inbuilt BMI Calculator', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Responsive Design', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Advanced Color Options and Color Pallets', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( '100+ Font Family Options', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'RTL & Translation Ready', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Support to Add Custom CSS/JS', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'SEO Friendly', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Pagination Option', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Footer Customization Options', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Fully Integrated with Font Awesome Icon', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Short Codes', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Woo Commerce Compatible', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Gallery, Banner & Post Type Plugin Functionality', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Multiple Inner Page Templates', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Customizable Home Page', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Advance Social Media Feature', 'the-wp-fitness'); ?></li>
		 	<li><?php esc_html_e( 'Left and Right Sidebar', 'the-wp-fitness'); ?></li>
		</ul>
	</div>
	<div class="service">
		<div class="col-md-3">
			<h3><span class="dashicons dashicons-media-document"></span> <?php esc_html_e('Get Support', 'the-wp-fitness'); ?></h3>
			<ol>
				<li>
				<a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'the-wp-fitness'); ?></a>
				</li>
			</ol>
		</div>
		<div class="col-md-3">
			<h3><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php esc_html_e('Getting Started', 'the-wp-fitness'); ?></h3>
			<ol>
				<li> <?php esc_html_e('Start', 'the-wp-fitness'); ?> <a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'the-wp-fitness'); ?></a> <?php esc_html_e('your website.', 'the-wp-fitness'); ?></li>
			</ol>
		</div>
		<div class="col-md-3">
			<h3><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e('Rate This Theme', 'the-wp-fitness'); ?></h3>
			<ol>
				<li>
				<a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_REVIEW ); ?>" target="_blank"><?php esc_html_e('Rate it here', 'the-wp-fitness'); ?></a>
				</li>
			</ol>
		</div>
		<div class="col-md-3">
			<h3><span class="dashicons dashicons-editor-help"></span> <?php esc_html_e( 'Help Docs', 'the-wp-fitness' ); ?></h3>
			<ol>
				<li><?php esc_html_e( 'The WP Fitness Lite', 'the-wp-fitness' ); ?> <a href="<?php echo esc_url( THE_WP_FITNESS_THEMESGLANCE_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'the-wp-fitness' ); ?></a></li>
			</ol>
		</div>
	</div>
</div>
<?php } ?>