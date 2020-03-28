<?php namespace TierPricingTable\Frontend;

use TierPricingTable\Settings\Settings;
use TierPricingTable\PriceManager;
use WC_Product;

/**
 * Class CartPriceManager
 *
 * @package TierPricingTable
 */
class CartPriceManager {

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
		$this->hooks();
	}

	protected function hooks() {
		// Calculate product price in cart by pricing rules
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'calculateTotals' ], 10, 3 );
		add_action( 'woocommerce_before_mini_cart_contents', [ $this, 'miniCartSubTotal' ], 10, 3 );
		add_filter( 'woocommerce_cart_item_price', [ $this, 'calculateItemPrice' ], 10, 2 );
	}

	/**
	 * Calculate price by quantity rules
	 *
	 * @param WC_Cart $cart
	 */
	public function calculateTotals( $cart ) {

		if ( ! empty( $cart->cart_contents ) ) {
			foreach ( $cart->cart_contents as $key => $cart_item ) {

				$needPriceRecalculation = apply_filters( 'tier_pricing_table/cart/need_price_recalculation', true, $cart_item );

				if ( $cart_item['data'] instanceof WC_Product && $needPriceRecalculation ) {

					$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];

					$new_price = PriceManager::getPriceByRules( $this->getTotalProductCount($cart_item), $product_id, 'cart' );

					if ( $new_price !== false ) {
						$cart_item['data']->set_price( $new_price );
					}
				}
			}
		}
	}

	/**
	 * Get total product count depend on user's settings
	 *
	 * @param array $cart_item
	 *
	 * @return int
	 */
	public function getTotalProductCount( $cart_item ) {

		if ( $this->settings->get( 'summarize_variations', 'no' ) !== 'yes' ) {
			return $cart_item['quantity'];
		}

		$count = 0;

		foreach ( wc()->cart->cart_contents as $cart_content ) {
			if ( $cart_content['product_id'] == $cart_item['product_id'] ) {
				$count += $cart_content['quantity'];
			}
		}

		return (int) apply_filters( 'tier_pricing_table/cart/total_product_count', $count );
	}


	public function miniCartSubTotal() {
		$cart = wc()->cart;
		$cart->calculate_totals();
	}

	/**
	 * Calculate price in mini cart
	 *
	 * @param string $price
	 * @param array $cart_item
	 *
	 * @return string
	 */
	public function calculateItemPrice( $price, $cart_item ) {

		$needPriceRecalculation = apply_filters( 'tier_pricing_table/cart/need_price_recalculation/item', true, $cart_item );

		if ( $cart_item['data'] instanceof WC_Product && $needPriceRecalculation ) {

			$new_price = PriceManager::getPriceByRules( $this->getTotalProductCount($cart_item), $cart_item['data']->get_id(), 'cart' );

			// To get real product price
			$product = wc_get_product( $cart_item['data']->get_id() );

			if ( $new_price !== false ) {
				if ( $this->settings->getAll()['show_discount_in_cart'] === 'yes' && tpt_fs()->can_use_premium_code() ) {
					return '<del> ' . wc_price( $product->get_price() ) . ' </del> <ins> ' . wc_price( $new_price ) . ' </ins>';
				}

				return wc_price( $new_price );
			}
		}

		return $price;
	}
}