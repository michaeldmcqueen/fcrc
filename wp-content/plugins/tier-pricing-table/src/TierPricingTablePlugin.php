<?php namespace TierPricingTable;

use Premmerce\SDK\V2\Notifications\AdminNotifier;
use TierPricingTable\Settings\Settings;
use Premmerce\SDK\V2\FileManager\FileManager;
use TierPricingTable\Admin\Admin;
use TierPricingTable\Frontend\Frontend;
use TierPricingTable\BackgroundProcessing\Updater\Updater;

/**
 * Class TierPricingTablePlugin
 *
 * @package TierPricingTable
 */
class TierPricingTablePlugin {

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var Settings
	 */
	private $settings;

	/**
	 * @var AdminNotifier
	 */
	private $notifier;

	/**
	 * @var Freemius
	 */
	private $licence;

	/**
	 * @var Integrations\Integrations
	 */
	private $integrations;

	const VERSION = '2.3.4';

	const DB_VERSION = '2.0';

	/**
	 * TierPricingTablePlugin constructor.
	 *
	 * @param string $mainFile
	 */
	public function __construct( $mainFile ) {
		$this->licence     = new Freemius( $mainFile );
		$this->fileManager = new FileManager( $mainFile, 'tier-pricing-table' );
		$this->settings    = new Settings( $this->fileManager );
		$this->notifier    = new AdminNotifier();

		$this->integrations = new Integrations\Integrations();

		add_action( 'init', [ $this, 'loadTextDomain' ], - 999 );
		add_action( 'admin_init', [ $this, 'checkRequirePlugins' ] );

		add_filter( 'plugin_action_links_' . plugin_basename( $this->fileManager->getMainFile() ),
			[ $this, 'addPluginAction' ], 10, 4 );
	}

	/**
	 * Add setting to plugin actions at plugins list
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	public function addPluginAction( $actions ) {
		$actions[] = '<a href="' . $this->settings->getLink() . '">' . __( 'Settings', 'tier-pricing-table' ) . '</a>';

		if ( ! tpt_fs()->is_anonymous() && tpt_fs()->is_installed_on_site() ) {
			$actions[] = '<a href="' . $this->licence->getAccountPageUrl() . '"><b style="color: green">' . __( 'Account',
					'tier-pricing-table' ) . '</b></a>';
		}

		$actions[] = '<a href="' . $this->licence->getContactUsPageUrl() . '"><b style="color: green">' . __( 'Contact Us',
				'tier-pricing-table' ) . '</b></a>';

		if ( !tpt_fs()->is_premium() ) {
			$actions[] = '<a href="' . tpt_fs()->get_upgrade_url() . '"><b style="color: red">' . __( 'GO PREMIUM',
					'tier-pricing-table' ) . '</b></a>';
		}

		return $actions;
	}

	/**
	 * Run plugin part
	 */
	public function run() {
		$valid   = count( $this->validateRequiredPlugins() ) === 0 && $this->licence->isValid();
		$updater = new Updater();

		if ( $valid && ! $updater->checkForUpdates() ) {

			if ( is_admin() ) {
				new Admin( $this->fileManager, $this->licence, $this->settings );
			} else {
				new Frontend( $this->fileManager, $this->licence, $this->settings );
			}

		} else {
			$updater->update();
		}
	}

	/**
	 * Load plugin translations
	 */
	public function loadTextDomain() {
		$name = $this->fileManager->getPluginName();
		load_plugin_textdomain( 'tier-pricing-table', false, $name . '/languages/' );
	}

	/**
	 * Validate required plugins
	 *
	 * @return array
	 */
	private function validateRequiredPlugins() {
		$plugins = [];

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		/**
		 * Check if WooCommerce is active
		 **/
		if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) ) {
			$plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
		}

		return $plugins;
	}

	/**
	 * Check required plugins and push notifications
	 */
	public function checkRequirePlugins() {
		$message = __( 'The %s plugin requires %s plugin to be active!', 'tier-pricing-table' );

		$plugins = $this->validateRequiredPlugins();

		if ( count( $plugins ) ) {
			foreach ( $plugins as $plugin ) {
				$error = sprintf( $message, 'Tiered Pricing Table', $plugin );
				$this->notifier->push( $error, AdminNotifier::ERROR, false );
			}
		}
	}

	/**
	 * Fired during plugin uninstall
	 */
	public static function uninstall() {
		delete_post_meta_by_key( '_price_rules' );

		delete_option( Settings::SETTINGS_PREFIX . 'display' );
		delete_option( Settings::SETTINGS_PREFIX . 'position_hook' );
		delete_option( Settings::SETTINGS_PREFIX . 'head_quantity_text' );
		delete_option( Settings::SETTINGS_PREFIX . 'head_price_text' );
		delete_option( Settings::SETTINGS_PREFIX . 'display_type' );
		delete_option( Settings::SETTINGS_PREFIX . 'selected_quantity_color' );
		delete_option( Settings::SETTINGS_PREFIX . 'table_title' );
		delete_option( Settings::SETTINGS_PREFIX . 'table_css_class' );
		delete_option( Settings::SETTINGS_PREFIX . 'tooltip_size' );

		// Premium
		delete_option( Settings::SETTINGS_PREFIX . 'show_discount_in_cart' );
		delete_option( Settings::SETTINGS_PREFIX . 'tiered_price_at_catalog' );
		delete_option( Settings::SETTINGS_PREFIX . 'show_discount_column' );
		delete_option( Settings::SETTINGS_PREFIX . 'tiered_price_at_catalog_type' );
		delete_option( Settings::SETTINGS_PREFIX . 'lowest_prefix' );
		delete_option( Settings::SETTINGS_PREFIX . 'head_discount_text' );
		delete_option( Settings::SETTINGS_PREFIX . 'clickable_table_rows' );
		delete_option( Settings::SETTINGS_PREFIX . 'show_total_price' );
		delete_option( Settings::SETTINGS_PREFIX . 'tiered_price_at_product_page' );

		delete_option( Updater::DB_OPTION );
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		set_transient( 'tiered_pricing_table_activated', true, 100 );
	}

	public function deactivate() {

	}
}