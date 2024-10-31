<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Ajax' ) ) :
class NDFW_Ajax {

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

		add_action( 'wp_ajax_ndfw_add_subscriber', 						array( $this, 'add_subscriber' ) );
		add_action( 'wp_ajax_nopriv_ndfw_add_subscriber', 				array( $this, 'add_subscriber' ) );

		add_action( 'wp_ajax_ndfw_check_subscriber', 					array( $this, 'check_subscriber' ) );
		add_action( 'wp_ajax_nopriv_ndfw_check_subscriber', 			array( $this, 'check_subscriber' ) );

		add_action( 'wp_ajax_ndfw_update_popup_impressions', 			array( $this, 'update_popup_impressions' ) );
		add_action( 'wp_ajax_nopriv_ndfw_update_popup_impressions', 	array( $this, 'update_popup_impressions' ) );

		add_action( 'wp_ajax_ndfw_newsletter_lists', 					array( $this, 'newsletter_lists' ) );
		add_action( 'wp_ajax_nopriv_ndfw_newsletter_lists', 			array( $this, 'newsletter_lists' ) );

	}

	function add_subscriber() {

		check_ajax_referer( 'add-subscriber', 'security' );

		if ( !isset( $_POST['form_data'] ) || empty( $_POST['form_data'] ) ) {
			wp_die( -1 );
		}

		parse_str( $_POST['form_data'], $form_data );

		$fname 	= ( isset( $form_data['ndfw_fname'] ) ) ? sanitize_text_field( $form_data['ndfw_fname'] ) : '';
		$lname 	= ( isset( $form_data['ndfw_lname'] ) ) ? sanitize_text_field( $form_data['ndfw_lname'] ) : '';
		$email 	= ( isset( $form_data['ndfw_email'] ) ) ? sanitize_text_field( $form_data['ndfw_email'] ) : '';

		if ( empty( $email ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please enter your email address.', 'ndfw' ) ) ) );
		}

		if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please enter a valid email.', 'ndfw' ) ) ) );
		}

		if ( $this->check_email( $email ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'This email is already used to activate a discount.', 'ndfw' ) ) ) );
		}

		$coupon = $this->create_discount($email);

		if ( !$coupon ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Something went wrong. Please try again.', 'ndfw' ) ) ) );
		} 

		$subscribed = $this->subscribe( $fname, $lname, $email, $coupon );

		if ( $subscribed == 'success' ) {

			$total_subscribers = ndfw_settings()->get_setting( 'discounts_created', 'stats' );
			ndfw_settings()->update_setting( 'discounts_created', 'stats', intval( $total_subscribers ) + 1 );

			$this->apply_discount( $coupon['code'] );

			wp_die( json_encode( array( 'status' => 'success' ) ) );
		}

		if ( $subscribed == 'duplicate' ) {
			wp_delete_post( $coupon['id'], true );
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'This email is already used to activate a discount.', 'ndfw' ) ) ) );
		}

		if ( $subscribed == 'fail' ) {
			wp_delete_post( $coupon['id'], true );
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Something went wrong. Please try again.', 'ndfw' ) ) ) );
		}

	}

	function check_subscriber() {

		check_ajax_referer( 'check-subscriber', 'security' );

		if ( !isset( $_POST[ 'form_data' ] ) || empty( $_POST[ 'form_data' ] ) ) {
			wp_die( -1 );
		}

		parse_str( $_POST[ 'form_data' ], $form_data );

		$fname 	= ( isset( $form_data[ 'ndfw_fname' ] ) ) ? sanitize_text_field( $form_data[ 'ndfw_fname' ] ) : '';
		$lname 	= ( isset( $form_data[ 'ndfw_lname' ] ) ) ? sanitize_text_field( $form_data[ 'ndfw_lname' ] ) : '';
		$email 	= ( isset( $form_data[ 'ndfw_email' ] ) ) ? sanitize_text_field( $form_data[ 'ndfw_email' ] ) : '';

		if ( empty( $email ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please enter your email address.', 'ndfw' ) ) ) );
		}

		if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please enter a valid email.', 'ndfw' ) ) ) );
		}

		if ( $this->check_email( $email ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'This email is already used to activate a discount.', 'ndfw' ) ) ) );
		}

		wp_die( json_encode( array( 'status' => 'success' ) ) );

	}

	function update_popup_impressions() {

		check_ajax_referer( 'update-popup-impressions', 'security' );

		$total_impressions = ndfw_settings()->get_setting( 'popup_impressions', 'stats', 0 );
		$result = ndfw_settings()->update_setting( 'popup_impressions', 'stats', intval( $total_impressions ) + 1 );
		wp_die( $result );
	}

	private function check_email( $email ) {

		$email_query = new WP_Query( array( 'post_type' => 'ndfw_subscriber', 'meta_key' => 'email', 'meta_value' => $email, 'post_status' => 'publish' ) );
		return $email_query->post_count;

	}

	private function create_discount( $email ) {

	    // GENERATE RANDOM CODE
	    $characters         = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength   = strlen($characters);
	    $randomCode         = '';
	    for ( $i = 0; $i < 10; $i++ ) {
	        $randomCode .= $characters[rand(0, $charactersLength - 1)];
	    }

	    // COUPON PARAMETERS
	    $coupon_code        = strtoupper( $randomCode );

	    $amount             = ndfw_settings()->get_setting( 'amount', 'discount', '20' );
	    $type      			= ndfw_settings()->get_setting( 'type', 'discount', 'percent' );
	    $expiry      		= ndfw_settings()->get_setting( 'expiry', 'discount', 7 );
	    $minimum      		= ndfw_settings()->get_setting( 'minimum', 'discount' );
	    $maximum      		= ndfw_settings()->get_setting( 'maximum', 'discount' );
	    $free_shipping 		= ndfw_settings()->get_setting( 'free_shipping', 'discount' );
	    $exclude_sale_items = ndfw_settings()->get_setting( 'exclude_sale_items', 'discount' );
	    $products      		= ndfw_settings()->get_setting( 'products', 'discount' );
	    $exclude_products   = ndfw_settings()->get_setting( 'exclude_products', 'discount' );
	    $categories     	= ndfw_settings()->get_setting( 'product_categories', 'discount' );
	    $exclude_categories = ndfw_settings()->get_setting( 'exclude_product_categories', 'discount' );
	    
	    $coupon             = array( 'post_title' => $coupon_code, 'post_content' => '', 'post_excerpt' => sprintf( 'This coupon was created by Newsletter Discounts plugin, for "%s".', $email ), 'post_status' => 'publish', 'post_author' => 1, 'post_type' => 'shop_coupon' ); 
	    $new_coupon_id      = wp_insert_post($coupon);

	    if( $new_coupon_id ) { 

	        update_post_meta( $new_coupon_id, 'discount_type',       $type );
	        update_post_meta( $new_coupon_id, 'coupon_amount',       $amount );
	        update_post_meta( $new_coupon_id, 'individual_use',      'yes' );
	        update_post_meta( $new_coupon_id, 'usage_limit',         '1' );
	        update_post_meta( $new_coupon_id, 'apply_before_tax',    'yes' );
	        update_post_meta( $new_coupon_id, 'free_shipping',       ( $free_shipping == 'on' ) ? 'yes' : 'no' );
	        update_post_meta( $new_coupon_id, 'exclude_sale_items',  ( $exclude_sale_items == 'on' ) ? 'yes' : 'no' );
	        update_post_meta( $new_coupon_id, 'minimum_amount',      $minimum );
	        update_post_meta( $new_coupon_id, 'maximum_amount',      $maximum );

			$date = new DateTime( 'now' );
			date_add( $date, date_interval_create_from_date_string( $expiry . ' days' ) );
	        update_post_meta( $new_coupon_id, 'expiry_date', $date->format( 'Y-m-d' ) );

	        if ( $products ) {
	        	update_post_meta( $new_coupon_id, 'product_ids', implode( ',', $products ) );
	        }

	        if ( $exclude_products ) {
	        	update_post_meta( $new_coupon_id, 'exclude_product_ids', implode( ',', $exclude_products ) );
	        }

	        if ( $categories ) {
	        	update_post_meta( $new_coupon_id, 'product_categories', implode( ',', $categories ) );
	        }

	        if ( $exclude_categories ) {
	        	update_post_meta( $new_coupon_id, 'exclude_product_categories', implode( ',', $exclude_categories ) );
	        }

	        return array( 'id' => $new_coupon_id, 'code' => $coupon_code );

	    } else {

	    	return false;

	    }

	}

	private function subscribe( $first_name, $last_name, $email, $coupon ) {

		$newsletter_service 		= ndfw_settings()->get_setting( 'service', 'newsletter' );
		$newsletter_drip_api 		= ndfw_settings()->get_setting( 'drip_api', 'newsletter' );
		$newsletter_klaviyo_api 	= ndfw_settings()->get_setting( 'klaviyo_api', 'newsletter' );
		$newsletter_mailchimp_api 	= ndfw_settings()->get_setting( 'mailchimp_api', 'newsletter' );

		if ( $newsletter_service == 'drip' && !empty( $newsletter_drip_api ) ) { 

			$status = $this->subscribe_drip( $first_name, $last_name, $email, $coupon['code'] );
			if ( $status != 'success' ) {
				return $status;
			} 

		} elseif ( $newsletter_service == 'klaviyo' && !empty( $newsletter_klaviyo_api ) ) { 

			$status = $this->subscribe_klaviyo( $first_name, $last_name, $email, $coupon['code'] );
			if ( $status != 'success' ) {
				return $status;
			} 

		} elseif ( $newsletter_service == 'mailchimp' && !empty( $newsletter_mailchimp_api ) ) { 
			
			$status = $this->subscribe_mailchimp( $first_name, $last_name, $email, $coupon['code'] );
			if ( $status != 'success' ) {
				return $status;
			} 

		} 

		$subscriber = array( 'post_title' => $email, 'post_status' => 'publish', 'post_author' => 1, 'post_type' => 'ndfw_subscriber' ); 
		$new_subscriber_id = wp_insert_post($subscriber);

		if ( $new_subscriber_id ) {

			$newsletter_service_name = '';
			switch ($newsletter_service) {
				case 'klaviyo':
					$newsletter_service_name = esc_html( 'Klaviyo' );
					break;

				case 'mailchimp':
					$newsletter_service_name = esc_html( 'MailChimp' );
					break;
				
				default:
					$newsletter_service_name = '-';
					break;
			}

			update_post_meta( $new_subscriber_id, 'coupon_id', $coupon['id'] );
			update_post_meta( $new_subscriber_id, 'coupon_code', $coupon['code'] );
			update_post_meta( $new_subscriber_id, 'first_name', $first_name );
			update_post_meta( $new_subscriber_id, 'last_name', $last_name );
			update_post_meta( $new_subscriber_id, 'email', $email );
			update_post_meta( $new_subscriber_id, 'newsletter_service', $newsletter_service_name );

			return 'success';

		} else {

			return 'fail';

		}

	}

	private function subscribe_drip( $first_name, $last_name, $email, $coupon ) {

		$api = ndfw_settings()->get_setting( 'drip_api', 'newsletter' );
		$account = ndfw_settings()->get_setting( 'drip_account', 'newsletter' );

		if ( empty( $account ) ) {
			return 'fail';
		}

        $drip    = new Drip_Api( $api );

        $parameters = array( 'account_id' => $account, 'email' => $email, 'eu_consent' => 'granted' );

        if ( !empty( $first_name ) && !empty( $last_name ) ) {
        	$parameters[ 'custom_fields' ] = array( 'first_name' => $first_name, 'last_name' => $last_name );
        }

        $result  = $drip->create_or_update_subscriber( $parameters );

        if ( $result ) {

            $status = ( isset( $result['status'] ) ) ? $result['status'] : '';
            
            if ( $status === 'active' ) {

            	return 'success';

            } else {

                return 'fail';

            }

        } else {

            return 'fail';

        }

	}

	private function subscribe_klaviyo( $first_name, $last_name, $email, $coupon ) {

		$api 	= ndfw_settings()->get_setting( 'klaviyo_api', 'newsletter' );
		$list 	= ndfw_settings()->get_setting( 'klaviyo_list', 'newsletter' );

        $klaviyo    = new Klaviyo( $api );
        $result     = $klaviyo->add_subscriber( $list, $email, $first_name, $last_name );

        if ( $result ) {

            $status = ( isset( $result['status_code'] ) ) ? $result['status_code'] : '';
            $body	= ( isset( $result['body'] ) ) ? json_decode($result['body']) : '';
            
            if ( $status === 200 ) {

            	return 'success';

            } else {

                return 'fail';

            }

        } else {

            return 'fail';

        }

	}

	private function subscribe_mailchimp( $first_name, $last_name, $email, $coupon ) {

		$api 	= ndfw_settings()->get_setting( 'mailchimp_api', 'newsletter' );
		$list 	= ndfw_settings()->get_setting( 'mailchimp_list', 'newsletter' );

	    $MailChimp = new MailChimp($api);
	    
	    $post = $MailChimp->post( 'lists/' . $list . '/members', [ 'email_address' => $email, 'status' => 'subscribed' ] );

	    if ( $post ) {
	       
	        $status = ( isset( $post['status'] ) ) ? $post['status'] : 'fail';
	        
	        if ( $status === 'subscribed' ) {

	        	$hash = $MailChimp->subscriberHash($email);
        		$update = $MailChimp->patch("lists/$list/members/$hash", [ 'merge_fields' => [ 'FNAME' => $first_name, 'LNAME' => $last_name ] ]);

	        	return 'success';

	        } elseif ( $status == '400' ) {

	            return 'success';

	        } else {

	            return 'fail';

	        }

	    } else {

	        return 'fail';

	    }

	}

	private function apply_discount( $coupon_code ) {

		WC()->session->set_customer_session_cookie(true);

	    global $woocommerce;

		if ( $woocommerce->cart->has_discount( $coupon_code ) ) {
			return false;
		}

	    return $woocommerce->cart->apply_coupon( $coupon_code );

	}

	function newsletter_lists() {

		check_ajax_referer( 'newsletter-lists', 'security' );

		$service = ( isset( $_POST[ 'service'] ) ) ? sanitize_text_field( $_POST[ 'service'] ) : '';
		$api = ( isset( $_POST[ 'api'] ) ) ? sanitize_text_field( $_POST[ 'api'] ) : '';

		if ( empty( $service ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please select a newsletter service.', 'ndfw' ) ) ) );
		}

		if ( empty( $api ) ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'Please enter your API key.', 'ndfw' ) ) ) );
		}

		$lists = array();

		if ( $service == 'drip' ) {
			$lists = ndfw_settings()->drip_accounts( $api, true );
		} elseif ( $service == 'klaviyo' ) {
			$lists = ndfw_settings()->klaviyo_lists( $api, true );
		} elseif ( $service == 'mailchimp' ) {
			$lists = ndfw_settings()->mailchimp_lists( $api, true );
		}

		if ( !$lists ) {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'We couldn not connect your account. Check your API key and try again.', 'ndfw' ) ) ) );
		}

		if ( count( $lists ) ) {
			wp_die( json_encode( array( 'status' => 'success', 'data' => $lists ) ) );
		} else {
			wp_die( json_encode( array( 'status' => 'error', 'error_message' => esc_html__( 'We did not find any lists on the account you connected', 'ndfw' ) ) ) );
		}

	}

}
endif;

function ndfw_ajax() {
    return NDFW_Ajax::instance();
}

$GLOBALS['ndfw_ajax'] = ndfw_ajax(); ?>