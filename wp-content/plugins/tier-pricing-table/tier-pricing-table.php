<?php

use TierPricingTable\TierPricingTablePlugin;

/**
 *
 * Plugin Name: WooCommerce Tiered Price Table
 * Description:       Allow set price for certain quantity of a product. Shows pricing and product summary table. Supports displaying pricing table in tooltip.
 * Version:           2.3.4
 * Author:            bycrik
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tier-pricing-table
 * Domain Path:       /languages/
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.9
 *
 * @fs_premium_only /src/Frontend/CatalogPriceManager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'licence-init.php';

call_user_func( function () {

	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	$main = new TierPricingTablePlugin( __FILE__ );

	register_activation_hook( __FILE__, [ $main, 'activate' ] );

	register_deactivation_hook( __FILE__, [ $main, 'deactivate' ] );

	tpt_fs()->add_action( 'after_uninstall', [ TierPricingTablePlugin::class, 'uninstall' ] );

	$main->run();
} );