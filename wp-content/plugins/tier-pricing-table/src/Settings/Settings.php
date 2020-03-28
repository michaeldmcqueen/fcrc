<?php namespace TierPricingTable\Settings;

use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class Settings
 *
 * @package TierPricingTable\Settings
 */
class Settings {

	const SETTINGS_PREFIX = 'tier_pricing_table_';

	const SETTINGS_PAGE = 'tiered_pricing_table_settings';

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * Settings constructor.
	 *
	 * @param FileManager $fileManager
	 */
	public function __construct( FileManager $fileManager ) {
		$this->fileManager = $fileManager;

		$this->hooks();
	}

	/**
	 * Handle updating settings
	 */
	public function updateSettings() {
		woocommerce_update_options( $this->settings );
	}

	/**
	 * Init all settings
	 */
	public function initSettings() {
		$this->settings = array(
			[
				'title' => __( 'Tiered price table settings', 'tier-pricing-table' ),
				'desc'  => __( 'This controls how tiered pricing table will be displayed in your store',
					'tier-pricing-table' ),
				'id'    => self::SETTINGS_PREFIX . 'options',
				'type'  => 'title',
			],
			[
				'title'    => __( 'Show tiered price table', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'display',
				'type'     => 'checkbox',
				'default'  => 'yes',
				'desc'     => __( 'Display a table on frontend? Prices will change even if it is off',
					'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Display', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'display_type',
				'type'     => 'select',
				'options'  => [
					'tooltip' => __( 'Tooltip', 'tier-pricing-table' ),
					'table'   => __( 'Table', 'tier-pricing-table' ),
				],
				'desc'     => __( 'Type of displaying', 'tier-pricing-table' ),
				'desc_tip' => true,
				'default'  => 'table',
			],

			[
				'title'    => __( 'Tooltip icon color', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'tooltip_color',
				'type'     => 'color',
				'default'  => '#cc99c2',
				'desc'     => __( 'Color of icon', 'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Tooltip icon size (px)', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'tooltip_size',
				'type'     => 'number',
				'default'  => '15',
				'desc'     => __( 'Size of icon', 'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'   => __( 'Tooltip border', 'tier-pricing-table' ),
				'id'      => self::SETTINGS_PREFIX . 'tooltip_border',
				'type'    => 'checkbox',
				'default' => 'yes',
				'desc'    => __( 'Enable tooltip border', 'tier-pricing-table' ),
			],
			[
				'title'    => __( 'Table title', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'table_title',
				'type'     => 'text',
				'default'  => '',
				'desc'     => __( 'The name is being displayed above the table', 'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Table position', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'position_hook',
				'type'     => 'select',
				'options'  => [
					'woocommerce_before_add_to_cart_button'     => __( 'Above buy button', 'tier-pricing-table' ),
					'woocommerce_after_add_to_cart_button'      => __( 'Below buy button', 'tier-pricing-table' ),
					'woocommerce_before_add_to_cart_form'       => __( 'Above add to cart form', 'tier-pricing-table' ),
					'woocommerce_after_add_to_cart_form'        => __( 'Below add to cart form', 'tier-pricing-table' ),
					'woocommerce_single_product_summary'        => __( 'Above product title', 'tier-pricing-table' ),
					'woocommerce_before_single_product_summary' => __( 'Before product summary', 'tier-pricing-table' ),
					'woocommerce_after_single_product_summary'  => __( 'After product summary', 'tier-pricing-table' ),
				],
				'desc'     => __( 'Where to display the table', 'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Active price background color', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'selected_quantity_color',
				'type'     => 'color',
				'css'      => 'width:6em;',
				'default'  => '#cc99c2',
				'desc'     => __( 'Active tiered price background color', 'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Quantity text', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'head_quantity_text',
				'type'     => 'text',
				'default'  => __( 'Quantity', 'tier-pricing-table' ),
				'desc'     => __( 'Name of quantity column. Leave Price text and Quantity text blank to do not show table head',
					'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'Price text', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'head_price_text',
				'type'     => 'text',
				'default'  => __( 'Price', 'tier-pricing-table' ),
				'desc'     => __( 'Name of price column. Leave Price text and Quantity text blank to do not show table head',
					'tier-pricing-table' ),
				'desc_tip' => true,
			],
			[
				'title'    => __( 'CSS class', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'table_css_class',
				'type'     => 'text',
				'default'  => '',
				'desc'     => __( 'Add your own CSS styles for the class. The class would be added to the table.',
					'tier-pricing-table' ),
				'desc_tip' => true,
			],
			array(
				'title'    => __( 'Summarize all variation during calculation  product  variation tiered price',
					'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summarize_variations',
				'type'     => 'checkbox',
				'default'  => 'no',
				'desc'     => __( 'Add all variations quantity to calculate actual tiered price for product variation.',
					'tier-pricing-table' ),
				'desc_tip' => true,
			),
			[
				'type' => 'sectionend',
				'id'   => self::SETTINGS_PREFIX . 'options'
			],

			// Premium Here
			[
				'title'             => __( 'Advanced settings', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'premium_options',
				'type'              => 'title',
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Show Tiered Price in Cart as the Discount', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'show_discount_in_cart',
				'desc'              => __( 'Example:', 'tier-pricing-table' ) . ' <del>10$</del> 8$',
				'type'              => 'checkbox',
				'default'           => 'yes',
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => 'yes' ]
			],
			[
				'title'             => __( 'Enable price formatting on the catalog page, widgets, etc',
					'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'tiered_price_at_catalog',
				'type'              => 'checkbox',
				'default'           => 'yes',
				'desc'              => __( 'Change price formatting on the whole site (show range or minimal price)',
					'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Enable price formatting on product page ', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'tiered_price_at_product_page',
				'type'              => 'checkbox',
				'default'           => 'no',
				'desc'              => __( 'Change price for product at product page (show range or minimal price)',
					'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Enable catalog, widget price formatting for variable products',
					'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'tiered_price_at_catalog_for_variable',
				'type'              => 'checkbox',
				'default'           => 'yes',
				'desc'              => __( 'Show tiered price at the catalog for variable products. Uses the lowest and the highest prices from all variations',
					'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Display Tiered Price as', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'tiered_price_at_catalog_type',
				'type'              => 'select',
				'options'           => [
					'range'  => __( 'Range (from lowest to highest)', 'tier-pricing-table' ),
					'lowest' => __( 'Lowest price', 'tier-pricing-table' ),
				],
				'desc'              => __( 'How to Display Tiered Price at The Catalog', 'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Lowest price prefix', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'lowest_prefix',
				'type'              => 'text',
				'default'           => __( 'From', 'tier-pricing-table' ),
				'desc'              => __( 'Prefix Before Lowest Tiered Product Price at the Catalog. Example: <b>From 10$</b>',
					'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Show discount column for percentage table', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'show_discount_column',
				'type'              => 'checkbox',
				'default'           => 'yes',
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Discount column text', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'head_discount_text',
				'type'              => 'text',
				'default'           => __( 'Discount (%)', 'tier-pricing-table' ),
				'desc'              => __( 'Name of discount column', 'tier-pricing-table' ),
				'desc_tip'          => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Set tiered price on click', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'clickable_table_rows',
				'type'              => 'checkbox',
				'default'           => 'no',
				'desc'              => __( 'Change product quantity on click at table pricing row',
					'tier-pricing-table' ),
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'title'             => __( 'Show total price', 'tier-pricing-table' ),
				'id'                => self::SETTINGS_PREFIX . 'show_total_price',
				'type'              => 'checkbox',
				'default'           => 'no',
				'desc'              => __( 'Calculate and show total price at product page',
					'tier-pricing-table' ),
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			],
			[
				'type' => 'sectionend',
				'id'   => self::SETTINGS_PREFIX . 'premium_options'
			],
			// Summary section
			array(
				'title' => __( 'Summary block', 'tier-pricing-table' ),
				'id'    => self::SETTINGS_PREFIX . 'summary_section',
				'type'  => 'title',
				'desc'  => __( 'Section controls how summary block at a product page.',
					'tier-pricing-table' ),
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( 'Show summary block',
					'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'display_summary',
				'type'     => 'checkbox',
				'default'  => 'yes',
				'desc'     => __( 'Show summary block at a product page. Display information about actual unit price, product quantity and total',
					'tier-pricing-table' ),
				'desc_tip' => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( 'Summary block title', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summary_title',
				'type'     => 'text',
				'desc'     => __( 'The name is displaying above the summary block.', 'tier-pricing-table' ),
				'desc_tip' => true,
				'default'  => '',
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( 'Summary block type', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summary_type',
				'type'     => 'select',
				'options'  => array(
					'table'  => __( 'Table', 'tier-pricing-table' ),
					'inline' => __( 'Inline', 'tier-pricing-table' ),
				),
				'desc'     => __( 'Type of displaying.', 'tier-pricing-table' ),
				'desc_tip' => true,
				'default'  => 'table',
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( '"Total" label', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summary_total_label',
				'type'     => 'text',
				'default'  => __( 'Total:', 'tier-pricing-table' ),
				'desc'     => __( 'Label for the "total" line.', 'tier-pricing-table' ),
				'desc_tip' => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( '"Each" label', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summary_each_label',
				'type'     => 'text',
				'default'  => __( 'Each: ', 'tier-pricing-table' ),
				'desc'     => __( 'Label for the "each" line.', 'tier-pricing-table' ),
				'desc_tip' => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'title'    => __( 'Summary position', 'tier-pricing-table' ),
				'id'       => self::SETTINGS_PREFIX . 'summary_position_hook',
				'type'     => 'select',
				'options'  => array(
					'woocommerce_before_add_to_cart_button'     => __( 'Above buy button', 'tier-pricing-table' ),
					'woocommerce_after_add_to_cart_button'      => __( 'Below buy button', 'tier-pricing-table' ),
					'woocommerce_before_add_to_cart_form'       => __( 'Above add to cart form', 'tier-pricing-table' ),
					'woocommerce_after_add_to_cart_form'        => __( 'Below add to cart form', 'tier-pricing-table' ),
					'woocommerce_single_product_summary'        => __( 'Above product title', 'tier-pricing-table' ),
					'woocommerce_before_single_product_summary' => __( 'Before product summary', 'tier-pricing-table' ),
					'woocommerce_after_single_product_summary'  => __( 'After product summary', 'tier-pricing-table' ),
				),
				'default'  => 'woocommerce_after_add_to_cart_button',
				'desc'     => __( 'Where to display the summary block.', 'tier-pricing-table' ),
				'desc_tip' => true,
				'custom_attributes' => [ 'data-tiered-pricing-premium-setting' => true ]
			),
			array(
				'type' => 'sectionend',
				'id'   => self::SETTINGS_PREFIX . 'premium_options'
			),
		);
	}

	/**
	 * Register hooks
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'initSettings' ] );

		add_filter( 'woocommerce_settings_tabs_' . self::SETTINGS_PAGE, [ $this, 'addTieredPricingTableSettings' ] );
		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'addSettingsTab' ], 50 );
		add_action( 'woocommerce_update_options_' . self::SETTINGS_PAGE, [ $this, 'updateSettings' ] );
	}

	/**
	 * Add own settings tab
	 *
	 * @param array $settings_tabs
	 *
	 * @return mixed
	 */
	public static function addSettingsTab( $settings_tabs ) {

		$settings_tabs[ self::SETTINGS_PAGE ] = __( 'Tiered Pricing', 'tier-pricing-table' );

		return $settings_tabs;
	}

	/**
	 * Add settings to WooCommerce
	 */
	public function addTieredPricingTableSettings() {

		wp_enqueue_script( 'quantity-table-settings-js', $this->fileManager->locateAsset( 'admin/settings.js' ) );

		return woocommerce_admin_fields( $this->settings );
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function getAll() {
		return [
			'display'                              => $this->get( 'display', 'yes' ),
			'position_hook'                        => $this->get( 'position_hook',
				'woocommerce_after_add_to_cart_button' ),
			'head_quantity_text'                   => $this->get( 'head_quantity_text',
				__( 'Quantity', 'tier-pricing-table' ) ),
			'head_price_text'                      => $this->get( 'head_price_text',
				__( 'Price', 'tier-pricing-table' ) ),
			'display_type'                         => $this->get( 'display_type', 'table' ),
			'selected_quantity_color'              => $this->get( 'selected_quantity_color', '#cc99c2' ),
			'table_title'                          => $this->get( 'table_title', '' ),
			'table_css_class'                      => $this->get( 'table_css_class', '#fff' ),
			'tooltip_size'                         => $this->get( 'tooltip_size', 15 ),
			'tooltip_border'                       => $this->get( 'tooltip_border', 'yes' ),

			/* Premium */
			'show_discount_in_cart'                => $this->get( 'show_discount_in_cart', 'yes' ),
			'tiered_price_at_catalog'              => $this->get( 'tiered_price_at_catalog', 'yes' ),
			'tiered_price_at_product_page'         => $this->get( 'tiered_price_at_product_page', 'no' ),
			'tiered_price_at_catalog_for_variable' => $this->get( 'tiered_price_at_catalog_for_variable', 'yes' ),
			'tiered_price_at_catalog_type'         => $this->get( 'tiered_price_at_catalog_type', 'yes' ),
			'lowest_prefix'                        => $this->get( 'lowest_prefix', 'yes' ),
			'show_discount_column'                 => $this->get( 'show_discount_column', 'yes' ),
			'clickable_table_rows'                 => $this->get( 'clickable_table_rows', 'no' ),
			'show_total_price'                     => $this->get( 'show_total_price', 'no' ),
			'head_discount_text'                   => $this->get( 'head_discount_text',
				__( 'Discount (%)', 'tier-pricing-table' ) ),
		];
	}

	/**
	 * Get setting by name
	 *
	 * @param string $option_name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get( $option_name, $default = null ) {
		return get_option( self::SETTINGS_PREFIX . $option_name, $default );
	}

	/**
	 * Get url to settings page
	 *
	 * @return string
	 */
	public function getLink() {
		return admin_url( 'admin.php?page=wc-settings&tab=tiered_pricing_table_settings' );
	}
}