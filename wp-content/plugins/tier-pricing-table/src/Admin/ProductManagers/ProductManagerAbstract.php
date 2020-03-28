<?php namespace TierPricingTable\Admin\ProductManagers;

use Premmerce\SDK\V2\FileManager\FileManager;
use TierPricingTable\Freemius;
use TierPricingTable\Settings\Settings;

/**
 * Class ProductManagerAbstract
 *
 * @package TierPricingTable\Admin\ProductManagers
 */
abstract class ProductManagerAbstract {

	/**
	 * @var FileManager
	 */
	protected $fileManager;

	/**
	 * @var Settings
	 */
	protected $settings;

	/**
	 * @var Freemius
	 */
	protected $licence;

	/**
	 * Product Manager constructor.
	 *
	 * Register menu items and handlers
	 *
	 * @param FileManager $fileManager
	 * @param Settings $settings
	 * @param Freemius $licence
	 */
	public function __construct( FileManager $fileManager, Settings $settings, Freemius $licence ) {
		$this->fileManager = $fileManager;
		$this->settings    = $settings;
		$this->licence     = $licence;

		$this->hooks();
	}

	/**
	 * Register manager hooks
	 */
	protected abstract function hooks();
}