<?php namespace TierPricingTable;

/**
 * Class Freemius
 * @package TierPricingTable
 */
class Freemius {

	/**
	 * @var \Freemius
	 */
	private $instance;

	/**
	 * @var string
	 */
	private $mainFile;

	/**
	 * Freemius constructor.
	 *
	 * @param $mainFile
	 */
	public function __construct( $mainFile ) {
		$this->init();
		$this->mainFile = $mainFile;

		if ( $this->isValid() ) {
			$this->hooks();
		}
	}

	/**
	 *
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'initPages' ] );
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return $this->instance instanceof \Freemius;
	}

	/**
	 *
	 */
	public function init() {
		if ( function_exists( 'tpt_fs' ) ) {
			$tpt_fs = tpt_fs();

			$tpt_fs->set_basename( true, $this->mainFile );

			$this->instance = $tpt_fs;
		}
	}

	/**
	 *
	 */
	public function initPages() {
		// Account
		add_submenu_page( null, __( 'Freemius Account', 'tier-price-table' ),
			__( 'Freemius Account', 'tier-price-table' ),
			'manage_options', 'tired-pricing-table-account', [ $this, 'renderAccountPage' ] );
		// Contact us
		add_submenu_page( null, __( 'Contact Us', 'tier-price-table' ), __( 'Contact Us', 'tier-price-table' ),
			'manage_options', 'tired-pricing-table-contact-us', [ $this, 'renderContactUsPage' ] );
	}

	/**
	 *
	 */
	public function renderAccountPage() {
		$this->instance->_account_page_load();
		$this->instance->_account_page_render();
	}

	/**
	 * @return bool
	 */
	public function isFree() {
		return ! $this->instance->is_premium();
	}

	/**
	 * @return bool
	 */
	public function isPremium() {
		return ! $this->instance->is_premium();
	}

	public function isPremiumOnly() {
		return $this->instance->is__premium_only();
	}

	/**
	 * @return string
	 */
	public function getAccountPageUrl() {
		return admin_url( 'admin.php?page=tired-pricing-table-account' );
	}

	/**
	 *
	 */
	public function renderContactUsPage() {
		$this->instance->_contact_page_render();
	}

	/**
	 * @return string
	 */
	public function getUpgradeLink() {
		return $this->instance->get_upgrade_url();
	}

	/**
	 * @return string
	 */
	public function getContactUsPageUrl() {
		return admin_url( 'admin.php?page=tired-pricing-table-contact-us' );
	}
}