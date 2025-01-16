<?php

class WCCF_Admin_Main {
	private const ADMIN_PAGE_SLUG = 'wedding-confirmations';

	private $settings_page;
	private $confirmations_page;

	public function __construct( Confirmations_DB_Service $confirmations_service, WCCF_Settings_Service $settings_service ) {
		wccf_include( 'includes/admin/pages/class-settings-page.php' );
		$this->settings_page = new WCCF_Settings_Page( $settings_service );

		wccf_include( 'includes/admin/pages/class-confirmations-page.php' );
		$this->confirmations_page = new WCCF_Confirmations_Page( $confirmations_service, self::ADMIN_PAGE_SLUG );

		$this->initialise();
	}

	public function initialise() {
		$this->settings_page->initialise();
		$this->confirmations_page->initialise();

		add_action( 'admin_menu', array( $this, 'add_admin_menu_pages' ) );
	}

	public function add_admin_menu_pages() {
		$this->confirmations_page->add_page_as_menu_page( [ 'icon_url' => 'dashicons-groups' ] );
		$this->confirmations_page->add_page_as_submenu_page( self::ADMIN_PAGE_SLUG );
		$this->settings_page->add_page_as_submenu_page( self::ADMIN_PAGE_SLUG );

		// Load global styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_global_admin_assets' ) );
	}


	function enqueue_global_admin_assets() {
		wp_enqueue_style(
			'wccf-admin-panel',
			plugin_dir_url( __FILE__ ) . 'assets/css/wccf-admin-panel.css',
		);

		wp_enqueue_script(
			'wccf-admin-panel-js',
			plugin_dir_url( __FILE__ ) . 'assets/js/index.js'
		);
	}
}