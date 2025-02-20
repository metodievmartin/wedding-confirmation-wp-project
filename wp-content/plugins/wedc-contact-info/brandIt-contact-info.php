<?php

/*
 * Plugin Name: Wedding Confirmation Contact Info
 * Description: Enables users to add and update their business contact information (email, phone, address, etc.) from the admin dashboard.
 * Version: 1.0
 * Author: Martin Metodiev
 * Author URI: https://github.com/metodievmartin
 * Text Domain: contact-info-domain
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BrandIt_Contact_Info {

	// ========== Constants ==========

	// Admin Menu Page Slugs
	protected const MAIN_MENU_PAGE_SLUG = 'business-contact-info';

	// Database Option Names
	protected const OPTION_CONTACT_EMAIL_ADDRESS = 'bci_contact_email_address';
	protected const OPTION_CONTACT_PHONE_NUMBER = 'bci_contact_phone_number';
	protected const OPTION_CONTACT_ADDRESS = 'bci_contact_address';

	// Nonce Fields
	protected const NONCE_SAVE_CONTACT_INFO = 'bci_save_contact_info_nonce';
	protected const NONCE_SAVE_CONTACT_INFO_ACTION = 'bci_save_contact_info';

	// ========== Static Methods ==========

	public static function fetch_contact_phone_number_option() {
		return get_option( self::OPTION_CONTACT_PHONE_NUMBER, '' );
	}

	public static function fetch_contact_address_option() {
		return get_option( self::OPTION_CONTACT_ADDRESS, '' );
	}

	public static function fetch_contact_email_option() {
		return get_option( self::OPTION_CONTACT_EMAIL_ADDRESS, '' );
	}

	// ========== Constructor ==========

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_to_admin_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'load_languages' ) );
	}

	// ========== Getters ==========

	protected function get_contact_address() {
		return get_option( self::OPTION_CONTACT_ADDRESS, '' );
	}

	protected function get_contact_phone_number() {
		return get_option( self::OPTION_CONTACT_PHONE_NUMBER, '' );
	}

	protected function get_contact_email() {
		return get_option( self::OPTION_CONTACT_EMAIL_ADDRESS, '' );
	}

	// ========== Setup Methods (Hook callbacks) ==========

	function add_to_admin_menu() {
		$main_menu_page_hook = add_menu_page(
			esc_html__( 'Contact Info', 'contact-info-domain' ),
			esc_html__( 'Contact Info', 'contact-info-domain' ),
			'edit_pages',
			self::MAIN_MENU_PAGE_SLUG,
			array( $this, 'contact_info_admin_page_html' ),
			'dashicons-email',
			26
		);

		// loads additional styles
		add_action( "load-{$main_menu_page_hook}", array( $this, 'load_main_menu_page_assets' ) );
	}

	function load_main_menu_page_assets() {
		wp_enqueue_style(
			'word-filter-admin-styles',
			plugin_dir_url( __FILE__ ) . 'styles/contact-info-admin.css'
		);
	}

	function load_languages() {
		load_plugin_textdomain(
			'contact-info-domain',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	// ========== Handlers & Processing Logic ==========

	protected function handle_form_submit() {
		// verifies the Nonce and also checks the current user has the necessary permissions
		if (
			! isset( $_POST[ self::NONCE_SAVE_CONTACT_INFO ] )
			|| ! wp_verify_nonce( $_POST[ self::NONCE_SAVE_CONTACT_INFO ], self::NONCE_SAVE_CONTACT_INFO_ACTION )
			|| ! current_user_can( 'edit_pages' )
		) {
			$this->render_error_message( __( 'Sorry, you are not allowed to manage contact forms.', 'contact-info-domain' ) );

			return;
		}

		if ( isset( $_POST[ self::OPTION_CONTACT_EMAIL_ADDRESS ] ) ) {
			update_option( self::OPTION_CONTACT_EMAIL_ADDRESS, sanitize_email( $_POST[ self::OPTION_CONTACT_EMAIL_ADDRESS ] ) );
		}

		if ( isset( $_POST[ self::OPTION_CONTACT_ADDRESS ] ) ) {
			update_option( self::OPTION_CONTACT_ADDRESS, sanitize_text_field( $_POST[ self::OPTION_CONTACT_ADDRESS ] ) );
		}

		if ( isset( $_POST[ self::OPTION_CONTACT_PHONE_NUMBER ] ) ) {
			update_option( self::OPTION_CONTACT_PHONE_NUMBER, sanitize_text_field( $_POST[ self::OPTION_CONTACT_PHONE_NUMBER ] ) );
		}

		$this->render_success_message( __( 'Your contact info has been saved.', 'contact-info-domain' ) );
	}

	// ========== HTML Generators ==========

	function contact_info_admin_page_html() {
		?>

        <div class="wrap my-contact-info-settings">
            <h1><?php echo esc_html__( 'Your Contact Information', 'contact-info-domain' ); ?></h1>

			<?php

			if ( isset( $_POST['just_submitted'] ) == 'true' ) {
				$this->handle_form_submit();
			}

			?>

            <form method="post" class="my-contact-info-form">
                <input type="hidden" name="just_submitted" value="true">

				<?php wp_nonce_field( self::NONCE_SAVE_CONTACT_INFO_ACTION, self::NONCE_SAVE_CONTACT_INFO ) ?>

                <!-------- EMAIL FIELD -------->
                <div class="form-group">
                    <div class="form-floating">
                        <input type="email"
                               name="<?php echo self::OPTION_CONTACT_EMAIL_ADDRESS ?>"
                               class="form-control" id="<?php echo self::OPTION_CONTACT_EMAIL_ADDRESS ?>"
                               value="<?php echo esc_attr( $this->get_contact_email() ); ?>"
                               placeholder="name@example.com">
                        <label for="<?php echo self::OPTION_CONTACT_EMAIL_ADDRESS ?>">
							<?php esc_html_e( 'Email', 'contact-info-domain' ); ?>
                        </label>
                    </div>
                    <p class="description">
						<?php esc_html_e( 'Enter your business email address that will be used for contact purposes.', 'contact-info-domain' ) ?>
                    </p>
                </div>

                <!-------- ADDRESS FIELD -------->
                <div class="form-group">
                    <div class="form-floating">
                        <input type="text"
                               name="<?php echo self::OPTION_CONTACT_ADDRESS ?>"
                               class="form-control" id="<?php echo self::OPTION_CONTACT_ADDRESS ?>"
                               value="<?php echo esc_attr( $this->get_contact_address() ); ?>"
                               placeholder="123 Street, New York, USA">
                        <label for="<?php echo self::OPTION_CONTACT_ADDRESS ?>">
							<?php esc_html_e( 'Address', 'contact-info-domain' ); ?>
                        </label>
                    </div>
                    <p class="description">
						<?php esc_html_e( 'Provide the primary address where you can be reached or where your business is located.', 'contact-info-domain' ) ?>
                    </p>
                </div>

                <!-------- PHONE NUMBER FIELD -------->
                <div class="form-group">
                    <div class="form-floating">
                        <input type="tel"
                               name="<?php echo self::OPTION_CONTACT_PHONE_NUMBER ?>"
                               class="form-control" id="<?php echo self::OPTION_CONTACT_PHONE_NUMBER ?>"
                               value="<?php echo esc_attr( $this->get_contact_phone_number() ); ?>"
                               placeholder="+359891234567"
                               pattern="^\+?[0-9]{10,15}$"
                               title="Valid formats are +359891234567 or 0891234567">
                        <label for="<?php echo self::OPTION_CONTACT_PHONE_NUMBER ?>">
							<?php esc_html_e( 'Phone Number', 'contact-info-domain' ); ?>
                        </label>
                    </div>
                    <p class="description">
						<?php esc_html_e( 'Enter your business contact phone number in the correct format, e.g., +359891234567 or 0891234567.', 'contact-info-domain' ) ?>
                    </p>
                </div>

                <input type="submit" id="submit" class="button button-primary"
                       value="<?php esc_html_e( 'Save Changes', 'contact-info-domain' ) ?>">
            </form>
        </div>

		<?php
	}

	// ========== Helpers ==========

	protected function render_success_message( $message ) {
		$this->display_admin_notice_html( 'updated', $message );
	}

	protected function render_error_message( $message ) {
		$this->display_admin_notice_html( 'error', $message );
	}

	protected function display_admin_notice_html( $type, $message ) {
		?>

        <div class="<?php echo esc_attr( $type ); ?> notice is-dismissible">
            <p><?php echo esc_html( $message ); ?></p>
        </div>

		<?php
	}
}

$my_word_filter_plugin = new BrandIt_Contact_Info();

// Exposed functionality

/**
 * Get the contact phone number stored in the database.
 *
 * This function retrieves the business's contact phone number option.
 * It can be used throughout the theme to display the phone number dynamically.
 *
 * @return string The contact phone number, or an empty string if not set.
 */
function bci_get_contact_phone_number() {
	return BrandIt_Contact_Info::fetch_contact_phone_number_option();
}

/**
 * Get the contact email address stored in the database.
 *
 * This function retrieves the business's contact email address option.
 * It can be used throughout the theme to display the email address dynamically.
 *
 * @return string The contact email address, or an empty string if not set.
 */
function bci_get_contact_email() {
	return BrandIt_Contact_Info::fetch_contact_email_option();
}

/**
 * Get the contact address stored in the database.
 *
 * This function retrieves the business's contact address option.
 * It can be used throughout the theme to display the address dynamically.
 *
 * @return string The contact address, or an empty string if not set.
 */
function bci_get_contact_address() {
	return BrandIt_Contact_Info::fetch_contact_address_option();
}