<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Frontend' ) ) :
class NDFW_Frontend {

	// THE SINGLE INSTANCE OF THE CLASS.
    protected static $_instance = null;

    // ENSURE ONLY ONE INSTANCE OF THE PLUGIN IS LOADED OR CAN BE LOADED.
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	function __construct() {
		add_action( 'wp_footer', array( $this, 'popup' ) );
		add_action( 'wp_footer', array( $this, 'popup' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'style' ), 90 );
		if ( $this->fonts() != false ) {
			wp_enqueue_style( 'ndfw-style-fonts', $this->fonts(), array( 'ndfw-style' ) );
		}
	}

	function popup() {

		// CHECK IF DISMISSED
		if ( ( isset( $_COOKIE['_ndfw_popup_dismissed'] ) || isset( $_COOKIE['_ndfw_discount_activated'] ) ) && ! is_customize_preview() ) {
			return false;
		}

		// CHECK EXCLUDED PAGES
		$display_account 	= ndfw_settings()->get_setting( 'popup_exclude_account', 	'display' );
		$display_cart 		= ndfw_settings()->get_setting( 'popup_exclude_cart', 		'display' );
		$display_checkout 	= ndfw_settings()->get_setting( 'popup_exclude_checkout', 	'display' );
		$display_home 		= ndfw_settings()->get_setting( 'popup_exclude_home', 		'display' );

		if ( $display_account == 'on' && is_account_page() ) {
			return false;
		}

		if ( $display_cart == 'on' && is_cart() ) {
			return false;
		}

		if ( $display_checkout == 'on' && is_checkout() ) {
			return false;
		}

		if ( $display_home == 'on' && is_front_page() ) {
			return false;
		}

		include_once NDFW_PLUGIN_DIR . '/parts/part-popup.php';
	}

	function body_classes( $classes ) {

		$display_hide_desktop 	= ndfw_settings()->get_setting( 'popup_hide_desktop', 	'display' );
		$display_hide_mobile 	= ndfw_settings()->get_setting( 'popup_hide_mobile', 	'display' );
		$display_hide_image 	= get_theme_mod( 'ndfw_popup_image_hide' );

		if ( $display_hide_desktop == 'on' ) {
			$classes[] = 'ndfw-popup-desktop-hidden';
		}

		if ( $display_hide_mobile == 'on' ) {
			$classes[] = 'ndfw-popup-mobile-hidden';
		}

		return $classes;

	}

	function form_timer() {

		$status = ndfw_settings()->get_setting( 'status', 'timer' );

		if ( $status != 'on' ) {
			return false;
		}

		$days 	 = ndfw_settings()->get_setting( 'value_days', 'timer' );
		$hours 	 = ndfw_settings()->get_setting( 'value_hours', 'timer' );
		$minutes = ndfw_settings()->get_setting( 'value_minutes', 'timer' );
		$seconds = ndfw_settings()->get_setting( 'value_seconds', 'timer' );

		$start 	 = ( $days != '0' ) ? intval( $days ) * 86400 : 0;
		$start 	+= ( $hours != '0' ) ? intval( $hours ) * 3600 : 0;
		$start 	+= ( $minutes != '0' ) ? intval( $minutes ) * 60 : 0;
		$start 	+= ( $seconds != '0' ) ? intval( $seconds ) : 0;

		$cookie_start = ( isset( $_COOKIE[ '_ndfw_timer_start' ] ) ) ? esc_attr( $_COOKIE[ '_ndfw_timer_start' ] ) : -1;
		$cookie_value = ( isset( $_COOKIE[ '_ndfw_timer_value' ] ) ) ? esc_attr( $_COOKIE[ '_ndfw_timer_value' ] ) : -1;

		if ( $cookie_value > 0 && $cookie_start == $start ) {

			$time = esc_attr( $_COOKIE[ '_ndfw_timer_value' ] );
			return $this->timer_display( $time, $start );

		} else {

			$time = $start;
			return $this->timer_display( $time, $start );

		}

	}

	function popup_classes() {	

		$popup_layout = get_theme_mod( 'ndfw_popup_image_layout', 'top' );
		$classes[] = sprintf( 'ndfw-popup-layout-%s', esc_html( $popup_layout ) );

		$display_hide_image = get_theme_mod( 'ndfw_popup_image_hide' );
		if ( $display_hide_image == true ) {
			$classes[] = 'ndfw-popup-no-image';
		}

		if ( is_customize_preview() ) {
			$classes[] = 'ndfw-popup-preview';
		}

		return implode( ' ', $classes );
	}

	function style() {

		$style = '/* Newsletter Discounts Custom Style */';


		$background = get_theme_mod( 'ndfw_popup_background_color' );
		$style .= ( ! empty( $background ) ) ? sprintf( '.ndfw-popup-wrapper { background-color: %s; }', esc_attr( $background ) ) : '';

		$overlay = get_theme_mod( 'ndfw_popup_background_overlay' );
		$style .= ( ! empty( $overlay ) ) ? sprintf( '.ndfw-popup { background-color: %s; }', esc_attr( $overlay ) ) : '';

		$border_radius = get_theme_mod( 'ndfw_popup_border_radius' );
		$style .= ( ! empty( $border_radius ) ) ? sprintf( '.ndfw-popup-wrapper { border-radius: %spx; }', esc_attr( $border_radius ) ) : '';

		$image = get_theme_mod( 'ndfw_popup_image_main' );
		$style .= ( ! empty( $image ) ) ? sprintf( '#ndfw-popup .ndfw-popup-media-image, #ndfw-popup .ndfw-popup-content-wrapper:before { background-image: url(%s); }', esc_attr( $image ) ) : '';
		$style .= ( ! empty( $image ) ) ? sprintf( '#ndfw-popup-success .ndfw-popup-media-image, #ndfw-popup-success .ndfw-popup-content-wrapper:before { background-image: url(%s); }', esc_attr( $image ) ) : '';

		$image = get_theme_mod( 'ndfw_popup_image_success' );
		$style .= ( ! empty( $image ) ) ? sprintf( '#ndfw-popup-success .ndfw-popup-media-image, #ndfw-popup-success .ndfw-popup-content-wrapper:before { background-image: url(%s); }', esc_attr( $image ) ) : '';
		
		$image = get_theme_mod( 'ndfw_popup_image_mobile' );
		$style .= ( ! empty( $image ) ) ? sprintf( '@media only screen and (max-width: 735px) { #ndfw-popup .ndfw-popup-media-image, #ndfw-popup .ndfw-popup-content-wrapper:before { background-image: url(%s); } }', esc_attr( $image ) ) : '';

		$color = get_theme_mod( 'ndfw_popup_headline_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-headline h2 { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_headline_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-headline h2 { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_headline_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-headline h2 { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_headline_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-headline h2 { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$align = get_theme_mod( 'ndfw_popup_headline_align' );
		$style .= ( !empty( $align ) ) ? sprintf( '.ndfw-popup-headline h2 { text-align: %s; }', esc_attr( $align ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_headline_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-headline h2 { font-style: italic; }' : '';


		$color = get_theme_mod( 'ndfw_popup_body_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-body p, .ndfw-popup-consent p { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_body_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-body p, .ndfw-popup-consent p { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_body_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-body p, .ndfw-popup-consent p { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_body_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-body p, .ndfw-popup-consent p { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$align = get_theme_mod( 'ndfw_popup_body_align' );
		$style .= ( !empty( $align ) ) ? sprintf( '.ndfw-popup-body p, .ndfw-popup-consent p { text-align: %s; }', esc_attr( $align ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_body_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-body p, .ndfw-popup-consent p { font-style: italic; }' : '';


		$color = get_theme_mod( 'ndfw_popup_note_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-note p { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_note_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-note p { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_note_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-note p { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_note_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-note p { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_note_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-note p { font-style: italic; }' : '';


		$background = get_theme_mod( 'ndfw_popup_buttons_background' );
		$style .= ( !empty( $background ) ) ? sprintf( '.ndfw-popup-action button, .ndfw-popup-action button:hover { background-color: %s; } .ndfw-popup-action button:hover { opacity: 0.95; }', esc_attr( $background ) ) : '';

		$color = get_theme_mod( 'ndfw_popup_buttons_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-action button, .ndfw-popup-action button:hover { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_buttons_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-action button { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_buttons_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-action button { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_buttons_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-action button { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_buttons_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-action button { font-style: italic; }' : '';

		$radius = get_theme_mod( 'ndfw_popup_buttons_radius' );
		$style .= ( !empty( $radius ) ) ? sprintf( '.ndfw-popup-action button { border-radius: %spx; }', esc_attr( $radius ) ) : '';


		$background = get_theme_mod( 'ndfw_popup_inputs_background' );
		$style .= ( !empty( $background ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { background-color: %s; } .ndfw-popup-action button:hover { opacity: 0.95; }', esc_attr( $background ) ) : '';

		$color = get_theme_mod( 'ndfw_popup_inputs_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { color: %s; }', esc_attr( $color ) ) : '';

		$placeholder = get_theme_mod( 'ndfw_popup_inputs_placeholder' );
		$style .= ( !empty( $placeholder ) ) ? sprintf( '.ndfw-popup-form input[type="text"]::placeholder, .ndfw-popup-form input[type="email"]::placeholder { color: %s; }', esc_attr( $placeholder ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_inputs_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_inputs_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_inputs_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_inputs_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { font-style: italic; }' : '';

		$radius = get_theme_mod( 'ndfw_popup_inputs_radius' );
		$style .= ( !empty( $radius ) ) ? sprintf( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"] { border-radius: %spx; }', esc_attr( $radius ) ) : '';


		$color = get_theme_mod( 'ndfw_popup_links_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-action a { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_links_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-action a { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_links_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-action a { font-size: %spx; }', esc_attr( $size ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_links_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-action a { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_links_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-action a { font-style: italic; }' : '';



		$background = get_theme_mod( 'ndfw_popup_timer_background' );
		$style .= ( !empty( $background ) ) ? sprintf( '.ndfw-popup-timer .unit { background-color: %s; } .ndfw-popup-action button:hover { opacity: 0.95; }', esc_attr( $background ) ) : '';

		$color = get_theme_mod( 'ndfw_popup_timer_color' );
		$style .= ( !empty( $color ) ) ? sprintf( '.ndfw-popup-timer .unit { color: %s; }', esc_attr( $color ) ) : '';

		$family = get_theme_mod( 'ndfw_popup_timer_family' );
		$style .= ( !empty( $family ) ) ? sprintf( '.ndfw-popup-timer .unit { font-family: "%s"; }', esc_attr( $family ) ) : '';

		$size = get_theme_mod( 'ndfw_popup_timer_size' );
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-timer .unit-value { font-size: %spx; }', esc_attr( $size ) ) : '';
		$style .= ( !empty( $size ) ) ? sprintf( '.ndfw-popup-timer .unit-name { font-size: %spx; }', esc_attr( $size * 0.375 ) ) : '';

		$weight = get_theme_mod( 'ndfw_popup_timer_weight' );
		$style .= ( !empty( $weight ) ) ? sprintf( '.ndfw-popup-timer .unit { font-weight: %s; }', esc_attr( $weight ) ) : '';

		$italic = get_theme_mod( 'ndfw_popup_timer_italic' );
		$style .= ( $italic == true ) ? '.ndfw-popup-timer .unit { font-style: italic; }' : '';

		$radius = get_theme_mod( 'ndfw_popup_timer_radius' );
		$style .= ( !empty( $radius ) ) ? sprintf( '.ndfw-popup-timer .unit { border-radius: %spx; }', esc_attr( $radius ) ) : '';


		wp_add_inline_style( 'ndfw-style', $style );

	}

	function fonts() {

		$fonts = array();

		$family = get_theme_mod( 'ndfw_popup_headline_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$family = get_theme_mod( 'ndfw_popup_body_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$family = get_theme_mod( 'ndfw_popup_note_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$family = get_theme_mod( 'ndfw_popup_inputs_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$family = get_theme_mod( 'ndfw_popup_buttons_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$family = get_theme_mod( 'ndfw_popup_links_family' );
		if ( !empty( $family ) ) {
			$fonts[] = $family;
		}

		$fonts = array_unique( $fonts );

		foreach ( $fonts as $key => $font ) {
			$font_families[] = $font . ':100,200,300,400,500,600,700,800,900';
		}

		if ( ! empty( $font_families ) ) {

			$query_args = array( 'family' => urlencode( implode( '|', $font_families ) ), 'subset' => urlencode( 'latin,latin-ext' ) );
			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			return esc_url_raw( $fonts_url );

		} else {

			return false;

		}
	}

	private function timer_display( $seconds, $start ) {

		$dt1  = new DateTime("@0");
		$dt2  = new DateTime("@$seconds");
		$time = $dt1->diff($dt2)->format('%d:%h:%i:%s');

		$date = array( esc_html__( 'days', 'ndfw' ), esc_html__( 'hours', 'ndfw' ), esc_html__( 'minutes', 'ndfw' ), esc_html__( 'seconds', 'ndfw' ) );

		$timer = explode( ':', $time );
		$value = '<p data-start="' . $start . '" data-value="' . $seconds . '">';

		foreach ( $timer as $key => $item ) {
			$value .= '<span class="unit"><span class="unit-value" data-index="' . $key . '">';
			$value .= ( $item < 10 ) ? '0' . $item : $item;
			$value .= '</span><span class="unit-name">' . $date[$key] . '</span>';
			$value .= '</span>';
		}

		$value .= '</p>';

		return $value;

	}

}
endif;

function ndfw_frontend() {
    return NDFW_Frontend::instance();
}

$GLOBALS['ndfw_frontend'] = ndfw_frontend(); ?>