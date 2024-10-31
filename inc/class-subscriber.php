<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Subscriber' ) ) :
class NDFW_Subscriber {

	// THE SINGLE INSTANCE OF THE CLASS.
    protected static $_instance = null;

    // ENSURE ONLY ONE INSTANCE OF THE PLUGIN IS LOADED OR CAN BE LOADED.
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	/**
	 * CONSTRUCT
	*/
	function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'admin_menu', array( $this, 'remove_add_new_menu' ) );
		add_filter( 'manage_ndfw_subscriber_posts_columns', array( $this, 'set_subscriber_columns' ) );
		add_action( 'manage_ndfw_subscriber_posts_custom_column', array( $this, 'subscriber_columns' ), 10, 2 );
	}

	function register_post_type() {

		$labels = array( 
			'name'               	=> esc_html__( 'Subscribers', 					'ndfw' ),
			'singular_name'      	=> esc_html__( 'Subscriber', 					'ndfw' ),
			'add_new'            	=> esc_html__( 'Add New', 						'ndfw' ),
			'add_new_item'       	=> esc_html__( 'Add New Subscriber', 			'ndfw' ),
			'edit_item'          	=> esc_html__( 'Edit Subscriber', 				'ndfw' ),
			'new_item'           	=> esc_html__( 'New Subscriber', 				'ndfw' ),
			'all_items'          	=> esc_html__( 'Subscribers', 					'ndfw' ),
			'view_item'          	=> esc_html__( 'View Subscriber', 				'ndfw' ),
			'search_items'       	=> esc_html__( 'Search Subscribers', 			'ndfw' ),
			'not_found'          	=> esc_html__( 'No Subscribers Found', 			'ndfw' ),
			'not_found_in_trash' 	=> esc_html__( 'No Subscribers In The Trash', 	'ndfw' ), 
			'parent_item_colon'  	=> '',
			'menu_name'          	=> esc_html__( 'Newsletter Discounts', 'ndfw' ),
		);

		$args = array(
			'description'   		=> esc_html__( 'FAQ', 'ndfw' ),
			'has_archive'   		=> false,
			'labels'        		=> $labels,
			'menu_position' 		=> '56',
			'menu_icon'				=> 'dashicons-tickets-alt',
			'public'        		=> false,
			'show_in_menu'  		=> true,
			'show_ui'       		=> true,
			'supports'      		=> array( 'title' ),
		);

		register_post_type( 'ndfw_subscriber', $args ); 

	}

	function set_subscriber_columns($columns) {

		$title = $columns['title'];
		unset( $columns['title'] );

		$date = $columns['date'];
		unset( $columns['date'] );

	    $columns[ 'subscriber' ] = esc_html__( 'Subscriber', 'ndfw' );
	    $columns[ 'coupon' ] = esc_html__( 'Coupon', 'ndfw' );
	    $columns[ 'synced' ] = esc_html__( 'Synced to', 'ndfw' );
	    $columns[ 'sold' ] = esc_html__( 'Sold', 'ndfw' );

	    $columns[ 'date' ] = $date;

	    return $columns;
	}

	function subscriber_columns( $column, $post_id ) {
	    switch ( $column ) {
	        case 'subscriber' :
	        	$email = get_post_meta( $post_id , 'email' , true ); 
	        	$first_name = get_post_meta( $post_id , 'first_name' , true ); 
	        	$last_name = get_post_meta( $post_id , 'last_name' , true ); 
	        	if ( !empty($first_name) && !empty($last_name) ) {
	        		echo '<a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '"><strong>' . $first_name . ' ' . $last_name . '</strong> (' . $email . ')</a>';
	        	} else {
	        		echo '<a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '">' . $email . '</a>';
	        	}
	        	break;

	        case 'coupon' :
	        	$coupon_id = get_post_meta( $post_id , 'coupon_id' , true ); 
	        	$coupon_code = get_post_meta( $post_id , 'coupon_code' , true );
	        	if ( !empty($coupon_id) && !empty($coupon_code) ) {
	        		echo '<a href="' . admin_url( 'post.php?post=' . $coupon_id . '&action=edit' ) . '">' . esc_html( $coupon_code ) . '</a>';
	        	} else {
	        		echo '-';
	        	}
	        	break;

	        case 'sold' :
	        	$sold = get_post_meta( $post_id , 'sold' , true );
	        	$sold_text = ( $sold == 'yes' ) ? esc_html__( 'Yes', 'ndfw' ) : esc_html__( 'Not yet', 'ndfw' );
	        	echo '<p>' . esc_html( $sold_text ) . '</p>';
	        	break;

	        case 'synced' :
	            echo get_post_meta( $post_id , 'newsletter_service' , true ); 
	            break;
	    }
	}

	function remove_add_new_menu() {
	    remove_submenu_page( 'edit.php?post_type=ndfw_subscriber', 'post-new.php?post_type=ndfw_subscriber' );
	}

}
endif;

function ndfw_subscriber() {
    return NDFW_Subscriber::instance();
}

$GLOBALS['ndfw_subscriber'] = ndfw_subscriber(); ?>