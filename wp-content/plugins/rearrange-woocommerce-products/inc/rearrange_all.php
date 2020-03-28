<?php
// Query Products
$params = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'orderby' 			=> 'menu_order',
			'order' 			=> 'ASC',
			'post_status'		=> array('publish')
		);
$wc_query = new WP_Query($params);
?>

<?php if ($wc_query->have_posts()) : ?>

<form id="frm_rwpp" action="<?php echo get_admin_url();?>admin-ajax.php" method='post'>
	<input type="hidden" name="action" value="save_rwpp" />
	<ul class="sortable">
		<?php $counter = 0;?>
		<?php while ($wc_query->have_posts()) : $wc_query->the_post(); ?>
		
		<li>
			<?php
			global $post;
			$product = wc_get_product( $post->ID );
			?>
			<input type="hidden" name="product_id[]" id="product_id[]" value="<?php echo $post->ID;?>">
			
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php the_title(); ?></span> <span class="dashicons dashicons-info" title="click to view/hide product info"></span></span>
					<input type="hidden" name="current_menu_order[]" id="current_menu_order[]" value="<?php echo $post->menu_order;?>">
					<input type="hidden" name="new_menu_order[]" id="new_menu_order[]" value="<?php echo $counter;?>">

					<?php include("product_details_box.php");?>
				</div>
			</div>
		</li>

		<?php $counter++;?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</ul>

	<div class="submit-btn-wrapper">
		<?php submit_button( __('Update Products', 'rwpp'), 'primary', '', '', array('id' => 'btn_save_rwpp')); ?> <div class="spinner"></div>
		<div class="response"></div>
	</div>

	<?php include("notice.php");?>

</form> 

<?php else:  ?>

<div class="notice notice-warning">
	<p><?php _e('No products found.', 'rwpp');?></p>
</div>

<?php endif; ?>