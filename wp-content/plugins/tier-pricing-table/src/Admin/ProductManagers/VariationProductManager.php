<?php namespace TierPricingTable\Admin\ProductManagers;

use TierPricingTable\PriceManager;
use WP_Post;

/**
 * Class VariationProduct
 *
 * @package TierPricingTable\Admin\Product
 */
class VariationProductManager extends ProductManagerAbstract {

	/**
	 * Register hooks
	 */
	protected function hooks() {
		add_action( 'woocommerce_variation_options_pricing', [ $this, 'renderPriceRules' ], 10, 3 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'updatePriceRules' ], 10, 3 );

		if ( tpt_fs()->is__premium_only() ) {
			add_action( 'woocommerce_save_product_variation', [ $this, 'updatePriceRulesType__premium_only' ], 10, 3 );
		}
	}

	/**
	 * Update price quantity rules for variation product
	 *
	 * @param int $variation_id
	 * @param int $loop
	 */
	public function updatePriceRules( $variation_id, $loop ) {

		if ( isset( $_POST['tiered_price_fixed_quantity'][ $loop ] ) ) {
			$amounts = $_POST['tiered_price_fixed_quantity'][ $loop ];
			$prices  = ! empty( $_POST['tiered_price_fixed_price'][ $loop ] ) ? $_POST['tiered_price_fixed_price'][ $loop ] : [];

			PriceManager::updateFixedPriceRules( $amounts, $prices, $variation_id );
		}

		if ( tpt_fs()->is__premium_only() ) {
			if ( isset( $_POST['tiered_price_percent_quantity'][ $loop ] ) ) {
				$amounts = $_POST['tiered_price_percent_quantity'][ $loop ];
				$prices  = ! empty( $_POST['tiered_price_percent_discount'][ $loop ] ) ? $_POST['tiered_price_percent_discount'][ $loop ] : [];

				PriceManager::updatePercentagePriceRules( $amounts, $prices, $variation_id );
			}


			if ( isset( $_POST['_tiered_pricing_minimum'][ $loop ] ) ) {
				$min = intval( $_POST['_tiered_pricing_minimum'][ $loop ] );
				$min = $min > 0 ? $min : 1;

				PriceManager::updateProductQtyMin( $variation_id, $min );
			}
		}

	}

	/**
	 * Update product pricing type
	 *
	 * @param int $variation_id
	 * @param int $loop
	 */
	public function updatePriceRulesType__premium_only( $variation_id, $loop ) {
		if ( ! empty( $_POST['tiered_price_rules_type'][ $loop ] ) ) {
			PriceManager::updatePriceRulesType( $variation_id, $_POST['tiered_price_rules_type'][ $loop ] );
		}
	}

	/**
	 * Render inputs for price rules on variation
	 *
	 * @param int $loop
	 * @param array $variation_data
	 * @param WP_Post $variation
	 */
	public function renderPriceRules( $loop, $variation_data, $variation ) {
		$type = PriceManager::getPricingType( $variation->ID );

		$this->fileManager->includeTemplate( 'admin/add-price-rules-variation.php', [
			'price_rules_fixed'      => PriceManager::getFixedPriceRules( $variation->ID, 'edit' ),
			'price_rules_percentage' => PriceManager::getPercentagePriceRules( $variation->ID, 'edit' ),
			'i'                      => $loop,
			'minimum'                => PriceManager::getProductQtyMin( $variation->ID, 'edit' ),
			'variation_data'         => $variation_data,
			'type'                   => $type,
			'isFree'                 => $this->licence->isFree()
		] );
	}
}