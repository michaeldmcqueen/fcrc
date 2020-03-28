<?php namespace TierPricingTable\Frontend;

use TierPricingTable\Settings\Settings;
use WC_Product;
use TierPricingTable\PriceManager;
use WC_Product_Variable;

/**
 * Class CatalogPriceManager
 *
 * @package TierPricingTable
 */
class CatalogPriceManager {

	/**
	 * @var Settings
	 */
	private $settings;

	/**
	 * CatalogPriceManager constructor.
	 *
	 * @param Settings $settings
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;

		if ( $this->isEnable() && ! is_admin() ) {
			add_filter( 'woocommerce_get_price_html', [ $this, 'formatPrice' ], 999, 2 );
		}
	}

	/**
	 * Change logic showing prince at catalog for product with tiered price rules
	 *
	 * @param string $priceHtml
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public function formatPrice( $priceHtml, $product ) {

		$product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();

		$formatCondition = $this->settings->get( 'tiered_price_at_product_page',
				'no' ) === 'yes' || get_queried_object_id() != $product_id;

		if ( $formatCondition ) {
			$displayPriceType = $this->getDisplayType();

			if ( $product instanceof WC_Product_Variable && $this->useForVariable() === 'yes' ) {
				$price = $this->formatPriceForVariableProduct( $product );

				if ( $price ) {
					return $price;
				}
			}

			if ( in_array( $product->get_type(), [ 'simple', 'variation' ] ) ) {
				$rules = PriceManager::getPriceRules( $product->get_id() );

				if ( ! empty( $rules ) ) {
					if ( $displayPriceType === 'range' ) {
						return $this->getRange( $rules, $product );
					} else {
						return $this->getLowestPrice( $rules, $product );
					}
				}
			}


		}

		return $priceHtml;
	}

	/**
	 * Format price for variable product. Range uses lowest and high prices from all variations
	 *
	 * @param WC_Product_Variable $product
	 *
	 * @return bool|string
	 */
	protected function formatPriceForVariableProduct( WC_Product_Variable $product ) {

		$prices = $product->get_variation_prices( true );

		$maxPrice  = end( $prices['price'] );
		$minPrices = [];

		foreach ( $product->get_available_variations() as $variation ) {
			$rules = PriceManager::getPriceRules( $variation['variation_id'] );
			if ( ! empty( $rules ) ) {

				$minPrices[] = $this->getLowestPrice( $rules, wc_get_product( $variation['variation_id'] ), false );
			}

		}


		if ( ! empty( $minPrices ) ) {
			if ( $this->getDisplayType() === 'range' ) {
				return wc_price( min( $minPrices ) ) . ' - ' . wc_price( $maxPrice );
			} else {
				return $this->getLowestPrefix() . ' ' . wc_price( min( $minPrices ) );
			}
		}

		return false;
	}

	/**
	 * Get range from lowest to highest price from price rules
	 *
	 * @param array $rules
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	protected function getRange( $rules, $product ) {

		$lowest = array_pop( $rules );

		$highest_html = wc_price( PriceManager::getPriceWithTaxes( $product->get_price(), $product ) );

		if ( PriceManager::getPricingType( $product->get_id() ) === 'percentage' ) {

			$price = PriceManager::getPriceByPercentDiscount( $product->get_price(), $lowest );

			$lowest_html = wc_price( PriceManager::getPriceWithTaxes( $price, $product ) );

			return $lowest_html . ' - ' . $highest_html;
		}

		$lowest_html = wc_price( PriceManager::getPriceWithTaxes( $lowest, $product ) );

		return $lowest_html . ' - ' . $highest_html;
	}

	/**
	 * Get lowest price from price rules
	 *
	 * @param array $rules
	 * @param WC_Product $product
	 *
	 * @param bool $html
	 *
	 * @return string|float
	 */
	protected function getLowestPrice( $rules, $product, $html = true ) {
		if ( PriceManager::getPricingType( $product->get_id() ) === 'percentage' ) {
			$lowest = PriceManager::getPriceByPercentDiscount( $product->get_price(),
				array_pop( $rules ) );
		} else {
			$lowest = array_pop( $rules );
		}

		if ( ! $html ) {
			return PriceManager::getPriceWithTaxes( $lowest, $product, 'shop' );
		}

		return $this->getLowestPrefix() . ' ' . wc_price( PriceManager::getPriceWithTaxes( $lowest, $product ) );
	}

	public function getLowestPrefix() {
		return $this->settings->get( 'lowest_prefix', __( 'From', 'tier-pricing-table' ) );
	}

	public function isEnable() {
		return $this->settings->get( 'tiered_price_at_catalog', 'yes' ) === 'yes';
	}

	public function getDisplayType() {
		return $this->settings->get( 'tiered_price_at_catalog_type', 'range' );
	}

	public function useForVariable() {
		return $this->settings->get( 'tiered_price_at_catalog_for_variable', 'yes' );
	}

}