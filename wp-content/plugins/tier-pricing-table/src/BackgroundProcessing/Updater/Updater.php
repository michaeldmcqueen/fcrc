<?php namespace TierPricingTable\BackgroundProcessing\Updater;

use TierPricingTable\TierPricingTablePlugin;
use TierPricingTable\BackgroundProcessing\Updater\Updates\VersionTwoDotZero;

class Updater {

	const DB_OPTION = 'tiered_price_table_version';

	public function checkForUpdates() {
		return $this->compare( TierPricingTablePlugin::DB_VERSION );
	}

	private function compare( $version ) {
		$dbVersion = get_option( self::DB_OPTION, '1.1' );

		return version_compare( $dbVersion, $version, '<' );
	}

	public function update() {
		foreach ( $this->getUpdates() as $version => $callback ) {
			if ( $this->compare( $version ) ) {
				call_user_func( $callback );
			}
		}
	}

	public function getUpdates() {
		return [
			'2.0' => [ $this, 'update2_0' ],
		];
	}

	public function update2_0() {
		( new VersionTwoDotZero() )->run();
	}
}