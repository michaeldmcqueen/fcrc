<?php namespace TierPricingTable\Frontend;

use TierPricingTable\Freemius;
use TierPricingTable\ProductManager;
use TierPricingTable\Settings\Settings;
use Premmerce\SDK\V2\FileManager\FileManager;
use TierPricingTable\PriceManager;
use TierPricingTable\TierPricingTablePlugin;
use WP_Post;
use WC_Product;

/**
 * Class Frontend
 *
 * @package TierPricingTable\Frontend
 */
class Frontend {

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var Settings
	 */
	private $settings;

	/**s
	 * @var CatalogPriceManager
	 */
	private $catalogPriceManager;

	/**
	 * @var CartPriceManager
	 */
	private $cartPriceManager;

	/**
	 * @var Freemius
	 */
	private $licence;

	/**
	 * Frontend constructor.
	 *
	 * @param FileManager $fileManager
	 * @param Freemius $licence
	 * @param Settings $settings
	 */
	public function __construct( FileManager $fileManager, Freemius $licence, Settings $settings ) {
		$this->fileManager = $fileManager;
		$this->settings    = $settings;
		$this->licence     = $licence;

		$this->initManagers();

		// Render price table
		add_action( $this->settings->get( 'position_hook', 'woocommerce_before_add_to_cart_button' ), [
			$this,
			'displayPricingTable'
		], - 999 );

		// Wrap price
		add_action( 'woocommerce_get_price_html', [ $this, 'wrapPrice' ], 10, 2 );

		// Get table for variation
		add_action( 'wc_ajax_get_price_table', [ $this, 'getPriceTableVariation' ], 10, 1 );

		// Enqueue frontend assets
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ], 10, 1 );

		// Render tooltip near product price if selected display type is "tooltip"
		if ( $this->settings->get( 'display',
				'yes' ) === 'yes' && $this->settings->get( 'display_type' ) === 'tooltip' ) {
			add_filter( 'woocommerce_get_price_html', [ $this, 'renderTooltip' ], 10, 2 );
		}

		if ( tpt_fs()->is__premium_only() ) {
			// Render summary block for the product
			if ( 'yes' === $this->settings->get( 'display_summary', 'yes' ) ) {
				add_action( $this->settings->get( 'summary_position_hook', 'woocommerce_after_add_to_cart_button' ),
					array( $this, 'renderSummary' ), 10 );
			}
		}
	}

	public function renderSummary() {

		global $post;

		if ( ! $post ) {
			return;
		}

		$product = wc_get_product( $post->ID );

		if ( ! $product ) {
			return;
		}

		$type = $this->settings->get( 'summary_type', 'table' );

		$this->fileManager->includeTemplate( 'frontend/summary-' . $type . '.php', array(
			'needHide'   => $product->is_type( 'variable' ),
			'totalLabel' => $this->settings->get( 'summary_total_label', 'Total:' ),
			'eachLabel'  => $this->settings->get( 'summary_each_label', 'Each:' ),
			'title'      => $this->settings->get( 'summary_title', '' ),
		) );
	}

	/**
	 *
	 */
	protected function initManagers() {
		$this->cartPriceManager = new CartPriceManager( $this->settings );

		if ( tpt_fs()->is__premium_only() ) {
			$this->catalogPriceManager = new CatalogPriceManager( $this->settings );
		}

	}

	/**
	 *  Display table at frontend
	 */
	public function displayPricingTable() {

		global $post;

		if ( ! $post ) {
			return;
		}

		$product = wc_get_product( $post->ID );

		if ( $product ) {
			if ( $product->is_type( 'simple' ) ) {
				$this->renderPricingTable( $product->get_id() );
			} elseif ( $product->is_type( 'variable' ) ) {
				echo '<div data-variation-price-rules-table></div>';
			}
		}
	}

	/**
	 * Wrap product price for managing it by JS
	 *
	 * @param string $price_html
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public function wrapPrice( $price_html, $product ) {

		if ( is_single() && ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) ) {
			return '<span data-tiered-price-wrapper>' . $price_html . '</span>';
		}

		return $price_html;
	}

	/**
	 * Render tooltip near product price if selected display type is "tooltip"
	 *
	 * @param string $price
	 * @param WC_Product $_product
	 *
	 * @return string
	 */
	public function renderTooltip( $price, $_product ) {

		if ( is_product() ) {

			$page_product_id = get_queried_object_id();

			if ( $_product->is_type( 'variation' ) && $_product->get_parent_id() === $page_product_id
			     || ( is_product() && $_product->is_type( 'simple' ) && $page_product_id === $_product->get_id() ) ) {

				$rules = PriceManager::getPriceRules( $_product->get_id() );

				if ( ! empty( $rules ) ) {
					return $price . $this->fileManager->renderTemplate( 'frontend/tooltip.php', [
								'color' => $this->settings->get( 'tooltip_color', '#cc99c2' ),
								'size'  => $this->settings->get( 'tooltip_size', 15 ) . 'px',
							]
						);

				}
			}
		}

		return $price;
	}

	/**
	 * Enqueue assets at simple product and variation product page.
	 *
	 * @global WP_Post $post .
	 */
	public function enqueueAssets() {
		global $post;

		if ( is_product() ) {
			$product = wc_get_product( $post->ID );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-tooltip' );

			wp_enqueue_script( 'tier-pricing-table-front-js',
				$this->fileManager->locateAsset( 'frontend/product-tier-pricing-table.js' ), 'jquery',
				TierPricingTablePlugin::VERSION );

			wp_enqueue_style( 'tier-pricing-table-front-css',
				$this->fileManager->locateAsset( 'frontend/main.css' ), null, TierPricingTablePlugin::VERSION );

			wp_localize_script( 'tier-pricing-table-front-js', 'tieredPricingTable',
				[
					'product_type'     => $product->get_type(),
					'settings'         => $this->settings->getAll(),
					'is_premium'       => $this->licence->isFree() ? 'no' : 'yes',
					'currency_options' => [
						'currency_symbol'    => get_woocommerce_currency_symbol(),
						'decimal_separator'  => wc_get_price_decimal_separator(),
						'thousand_separator' => wc_get_price_thousand_separator(),
						'decimals'           => wc_get_price_decimals(),
						'price_format'       => get_woocommerce_price_format(),
					]
				] );
		}
	}

	/**
	 * Fired when user choose some variation. Render price rules table for it if it exists
	 * @global WP_Post $post .
	 */
	public function getPriceTableVariation() {
		$product_id = isset( $_POST['variation_id'] ) ? $_POST['variation_id'] : false;

		$product = wc_get_product( $product_id );

		if ( $product && $product->is_type( 'variation' ) ) {
			$this->renderPricingTable( $product->get_parent_id(), $product->get_id() );
		}
	}

	/**
	 * Main function for rendering pricing table for product
	 *
	 * @param int $product_id
	 * @param int $variation_id
	 */
	public function renderPricingTable( $product_id, $variation_id = null ) {

		$product = wc_get_product( $product_id );

		$product_id = ! is_null( $variation_id ) ? $variation_id : $product->get_id();

		// Exit if product is not valid
		if ( ! $product || ! ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) ) {
			return;
		}

		$rules = PriceManager::getPriceRules( $product_id );

		$real_price   = ! is_null( $variation_id ) ? wc_get_product( $variation_id )->get_price() : $product->get_price();
		$product_name = ! is_null( $variation_id ) ? wc_get_product( $variation_id )->get_name() : $product->get_name();

		if ( ! empty( $rules ) ) {

			$template = 'percentage' === PriceManager::getPricingType( $product_id ) ? 'price-table-percentage.php' : 'price-table-fixed.php';

			$this->fileManager->includeTemplate( 'frontend/' . $template, array(
				'price_rules'  => $rules,
				'real_price'   => $real_price,
				'product_name' => $product_name,
				'product_id'   => $product_id,
				'minimum'      => PriceManager::getProductQtyMin( $product_id, 'view' ),
				'settings'     => $this->settings->getAll()
			) );
		}
	}
}