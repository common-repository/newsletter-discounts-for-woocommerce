<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW' ) ) :
class NDFW {

	public $version = '1.0';

	// THE SINGLE INSTANCE OF THE CLASS.
	protected static $_instance = null;

	// Ensure only one instance of the plugin is loaded or can be loaded.
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	private function includes() {

		// DRIP
		include_once NDFW_PLUGIN_DIR . 'libs/drip/drip.php';

		// KLAVIYO
		include_once NDFW_PLUGIN_DIR . 'libs/klaviyo/klaviyo.php';

		// MAILCHIMP
		include_once NDFW_PLUGIN_DIR . 'libs/mailchimp/mailchimp.php';

		// AJAX
		include_once NDFW_PLUGIN_DIR . 'inc/class-ajax.php';

		// FRONTEND
		include_once NDFW_PLUGIN_DIR . 'inc/class-frontend.php';

		// SETTINGS
		include_once NDFW_PLUGIN_DIR . 'inc/class-settings.php';

		// STATS
		include_once NDFW_PLUGIN_DIR . 'inc/class-stats.php';

		// SUBSCRIBERS
		include_once NDFW_PLUGIN_DIR . 'inc/class-subscriber.php';

	}


	private function init_hooks() {
		add_action( 'init', array( $this, 'textdomain' ) );
		add_action( 'init', array( $this, 'scripts' ) );
	}

	public function textdomain() {
		load_plugin_textdomain( 'ndfw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function scripts() {

		if ( is_admin() ) {
			wp_enqueue_style( 'ndfw-style-admin', $this->plugin_url() . '/assets/css/admin.min.css', ndfw()->version );
			wp_enqueue_script( 'ndfw-scripts-admin', $this->plugin_url() . '/assets/js/admin.min.js', array( 'jquery' ), ndfw()->version, true );
			wp_localize_script( 'ndfw-scripts-admin', 'ndfw_newsletter_lists', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'newsletter_lists_nonce' => wp_create_nonce( 'newsletter-lists' ) ) );
			wp_enqueue_style( 'ndfw-style-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css' );
			wp_enqueue_script( 'ndfw-scripts-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), true );
		}

		if ( ! is_admin() ) {

			wp_enqueue_style( 'ndfw-style', $this->plugin_url() . '/assets/css/style.min.css', ndfw()->version );

			wp_enqueue_script( 'animejs', $this->plugin_url() . '/assets/js/anime.min.js', array( 'jquery' ), true );
			wp_enqueue_script( 'smooth-scrollbar', $this->plugin_url() . '/assets/js/smooth-scrollbar.min.js', array( 'jquery' ), true );
			wp_enqueue_script( 'ndfw-scripts', $this->plugin_url() . '/assets/js/scripts.min.js', array( 'jquery' ), ndfw()->version, true );

			wp_localize_script( 'ndfw-scripts', 'ndfw_popup_form', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'add_subscriber_nonce' => wp_create_nonce( 'add-subscriber' ), 'check_subscriber_nonce' => wp_create_nonce( 'check-subscriber' ), 'update_popup_impressions_nonce' => wp_create_nonce( 'update-popup-impressions' ) ) );
			wp_localize_script( 'ndfw-scripts', 'ndfw_settings', $this->plugin_settings() );
			
		}

	}

	private function is_new_install() {
		return is_null( get_option( 'ndfw_version', null ) );
	}

	function plugin_url( $path = '' ) {
		$url = plugins_url( $path, NDFW_PLUGIN_FILE );
		if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
			$url = 'https:' . substr( $url, 5 );
		}
		return $url;
	}

	function plugin_settings() {
		$settings = array();
		$settings['popup_reveal'] = ndfw_settings()->get_setting( 'popup_reveal', 'display' );
		$settings['timer_action'] = ndfw_settings()->get_setting( 'action', 'timer' );
		return $settings;
	}

}
endif;

function ndfw() {
	return NDFW::instance();
}

$GLOBALS['ndfw'] = ndfw(); ?>