<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Stats' ) ) :
class NDFW_Stats {

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
        add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
        add_action( 'admin_init', array( $this, 'create_stats' ) );
        //add_action( 'woocommerce_payment_complete', array( $this, 'update_purchases' ), 10, 1 );
        add_action( 'woocommerce_order_status_completed', array( $this, 'update_purchases' ), 10, 1);
	}

    public function init() {
        wp_add_dashboard_widget( 'ndfw_dashboard_status', esc_html__( 'Newsletter Discounts', 'ndfw' ), array( $this, 'status_widget' ) );
    }

    public function create_stats() {

        $stats = array(
            'discounts_used_total'  => 0,
            'discounts_used'        => 0,
            'discounts_created'     => 0,
            'popup_impressions'     => 0,
        );
        add_option( 'ndfw_stats', $stats );

    }

    public function status_widget() {

        $total_impressions = ndfw_settings()->get_setting( 'popup_impressions', 'stats', 0 );
        $total_subscribers = ndfw_settings()->get_setting( 'discounts_created', 'stats', 0 );
        $total_orders = ndfw_settings()->get_setting( 'discounts_used', 'stats', 0 );
        $total_purchases = ndfw_settings()->get_setting( 'discounts_used_total', 'stats', 0 );

        $conversion_purchases = ( $total_subscribers > 0 ) ? ( $total_orders / $total_subscribers ) * 100 : 0;
        $conversion_views = ( $total_impressions > 0 ) ? ( $total_subscribers / $total_impressions ) * 100 : 0;

        $reports[ 'total_subscribers' ] = sprintf( esc_html__( 'Subscribers: %s', 'ndfw' ), esc_attr( $total_subscribers ) );
        $reports[ 'total_impressions' ] = sprintf( esc_html__( 'Impressions: %s', 'ndfw' ), esc_attr( $total_impressions ) );
        $reports[ 'total_purchases' ] = sprintf( esc_html__( 'Purchases: %s (%s orders)', 'ndfw' ), wc_price( $total_purchases ), esc_attr( $total_orders ) );
        $reports[ 'total_orders' ] = sprintf( esc_html__( 'Orders: %s', 'ndfw' ), esc_attr( $total_orders ) );

        $reports[ 'conversion_purchases' ] = sprintf( esc_html__( 'Conversion rate: %s%%', 'ndfw' ), esc_attr( round( $conversion_purchases, 2 ) ) );
        $reports[ 'conversion_views' ] = sprintf( esc_html__( 'Subscription rate: %s%%', 'ndfw' ), esc_attr( round( $conversion_views, 2 ) ) );

        echo '<ul class="ndfw-status-list">';

        if ( current_user_can( 'view_woocommerce_reports' ) ) {
            ?>
            <li class="total_purchases">
                <a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber' ); ?>">
                    <?php printf( esc_html__( '%s how much money you made from newsletter discounts', 'ndfw' ), '<strong>' . $reports[ 'total_purchases' ] . '</strong>' ); ?>
                </a>
            </li>

            <li class="total_subscribers">
                <a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber' ); ?>">
                    <?php printf( esc_html__( '%s how many collected emails', 'ndfw' ), '<strong>' . $reports[ 'total_subscribers' ] . '</strong>' ); ?>
                </a>
            </li>

            <li class="total_impressions">
                <a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber' ); ?>">
                    <?php printf( esc_html__( '%s how many times people saw the popup', 'ndfw' ), '<strong>' . $reports[ 'total_impressions' ] . '</strong>' ); ?>
                </a>
            </li>

            <li class="conversion_purchases">
                <a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber' ); ?>">
                    <?php printf( esc_html__( '%s sales per subscriptions', 'ndfw' ), '<strong>' . $reports[ 'conversion_purchases' ] . '</strong>' ); ?>
                </a>
            </li>

            <li class="conversion_views">
                <a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber' ); ?>">
                    <?php printf( esc_html__( '%s subscriptions per impressions', 'ndfw' ), '<strong>' . $reports[ 'conversion_views' ] . '</strong>' ); ?>
                </a>
            </li>
            <?php
        }
        
        echo '</ul>';
    }

    function update_purchases( $order_id ) {

        $order = wc_get_order( $order_id );
        $coupons = $order->get_used_coupons();
        $total = $order->get_total();

        if ( ! $coupons || $total == 0 ) {
            return false;
        }

        foreach ( $coupons as $key => $coupon ) {

            $discount = new WP_Query( array( 'post_type' => 'ndfw_subscriber', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_key' => 'coupon_code', 'meta_value' => $coupon ) );

            while ( $discount->have_posts() ) : $discount->the_post();
                
                update_post_meta( get_the_id(), 'sold', 'yes' );

                $discounts_used = ndfw_settings()->get_setting( 'discounts_used', 'stats' );
                ndfw_settings()->update_setting( 'discounts_used', 'stats', intval( $discounts_used ) + 1 );

                $discounts_used_total = ndfw_settings()->get_setting( 'discounts_used_total', 'stats' );
                ndfw_settings()->update_setting( 'discounts_used_total', 'stats', $discounts_used_total + $total );

            endwhile;
        }

    }
    

}
endif;

function ndfw_stats() {
    return NDFW_Stats::instance();
}

$GLOBALS['ndfw_stats'] = ndfw_stats(); ?>