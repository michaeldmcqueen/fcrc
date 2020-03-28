<?php namespace TierPricingTable\Integrations;

use TierPricingTable\Integrations\Plugins\MixMatch;
use TierPricingTable\Integrations\Plugins\WooCommerceProductAddons;
use TierPricingTable\Integrations\Themes\Avada;
use TierPricingTable\Integrations\Themes\Divi;
use TierPricingTable\Integrations\Themes\Electro;
use TierPricingTable\Integrations\Themes\Flatsome;
use TierPricingTable\Integrations\Themes\Merchandiser;
use TierPricingTable\Integrations\Themes\Neto;
use TierPricingTable\Integrations\Themes\OceanWp;
use TierPricingTable\Integrations\Themes\Shopkeeper;
use TierPricingTable\Integrations\Themes\TheRetailer;

class Integrations {

	private $themes = array();

	private $plugins = array();

	public function __construct() {
		$this->init();
		add_action( 'init', function () {
			// 	dd( wp_get_theme()->name );
		} );
	}

	public function init() {
		$this->themes = apply_filters( 'tier_pricing_table/integrations/themes', array(
			'Avada'        => Avada::class,
			'Divi'         => Divi::class,
			'OceanWP'      => OceanWp::class,
			'Flatsome'     => Flatsome::class,
			'Shopkeeper'   => Shopkeeper::class,
			'The Retailer' => TheRetailer::class,
			'Merchandiser' => Merchandiser::class,
			'Electro'      => Electro::class,
		) );

		$this->plugins = apply_filters( 'tier_pricing_table/integrations/plugins', array(
			'MixMatch' => MixMatch::class,
			'WooCommerceProductAddons' => WooCommerceProductAddons::class
		) );


		foreach ( $this->themes as $themeName => $theme ) {
			if ( wp_get_theme()->name === $themeName ) {
				new $theme();
			}
		}

		foreach ( $this->plugins as $pluginName => $plugin ) {
			new $plugin();
		}
	}
}
