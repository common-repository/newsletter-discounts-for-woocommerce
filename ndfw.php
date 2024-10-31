<?php
/**
 * Plugin Name: Newsletter Discounts for WooCommerce
 * Description: The easiest way to increase your store conversion by offering discounts for your visitors.
 * Version: 1.3.1
 * Author: Royalz Toolkits
 * Author URI: http://royalztoolkits.com
 * Developer: Royalz Toolkits
 * Developer http://royalztoolkits.com
 * Text Domain: ndfw
 * Domain Path: /languages
 *
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 3.0
 * WC tested up to: 3.4
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'admin_notices', function() {
		printf( '<div class="notice notice-info"><p>%s</p></div>', esc_html__( 'Newsletter discounts are not active yet. because WooCommerce is either disabled or not installed.', 'ndfw' ) );
	} );
	return false;
}


if ( ! defined( 'NDFW_PLUGIN_DIR' ) ) {
	define( 'NDFW_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
}


if ( ! defined( 'NDFW_PLUGIN_FILE' ) ) {
	define( 'NDFW_PLUGIN_FILE', __FILE__ );
}


include_once dirname( __FILE__ ) . '/inc/class-ndfw.php'; ?>