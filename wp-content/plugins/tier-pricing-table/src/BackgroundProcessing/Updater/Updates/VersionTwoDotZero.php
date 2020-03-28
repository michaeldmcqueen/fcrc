<?php namespace TierPricingTable\BackgroundProcessing\Updater\Updates;

/**
 * Class VersionTwoDotZero
 *
 * @package TierPricingTable\BackgroundProcessing\Updater\Updates
 */
class VersionTwoDotZero extends VersionAbstract {

	/**
	 * Update to version
	 *
	 * @var string
	 */
	public $version = '2.0';

	/**
	 * @var string
	 */
	protected $action = 'tiered_price_table_update_two_dot_zero';

	/**
	 * Main method to run updating. Version 2.0
	 */
	public function run() {
		add_action( 'init', [ $this, 'update' ] );
	}

	/**
	 * Update
	 */
	public function update() {
		$postsToUpdate = $this->getProductsToUpdate();

		// Single post
		if ( $postsToUpdate instanceof \WP_Post ) {
			$this->updatePost( $postsToUpdate->ID );
		}

		foreach ( $postsToUpdate as $postId ) {
			$this->updatePost( $postId );
		}

		$this->complete();
	}

	/**
	 * @param int $postId
	 */
	protected function updatePost( $postId ) {

		$priceRules = get_post_meta( $postId, '_price_rules', true );

		if ( ! empty( $priceRules ) ) {
			update_post_meta( $postId, '_fixed_price_rules', $priceRules );
		}

		update_post_meta( $postId, '_price_rules_updated', 'yes' );
	}

	/**
	 * Complete updating. Delete old meta values.
	 */
	protected function complete() {
		delete_post_meta_by_key( '_price_rules_updated' );
		delete_post_meta_by_key( '_price_rules' );

		$this->setCurrentDBVersion();
	}

	/**
	 * Get list of products to update.
	 *
	 * @return array
	 */
	protected function getProductsToUpdate() {
		$args = array(
			'post_type'      => [ 'product', 'product_variation' ],
			'posts_per_page' => - 1,

			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => '_price_rules',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => '_price_rules_updated',
					'compare' => 'NOT EXISTS',
				)
			),
			'fields'     => 'ids'
		);

		$query = new \WP_Query( $args );

		return $query->get_posts();
	}
}