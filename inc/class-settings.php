<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Settings' ) ) :
class NDFW_Settings {

    // THE SINGLE INSTANCE OF THE CLASS.
    protected static $_instance = null;

    // ENSURE ONLY ONE INSTANCE OF THE PLUGIN IS LOADED OR CAN BE LOADED.
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	private $options_general;
    private $options_content;

	public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'settings_defaults' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_settings_page() {
        add_submenu_page( 'edit.php?post_type=ndfw_subscriber', esc_html__( 'Newsletter discounts settings', 'ndfw' ),  esc_html__( 'Settings', 'ndfw' ) , 'manage_options', 'ndfw-settings', array( $this, 'options' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( NDFW_PLUGIN_FILE ), function ( $actions, $plugin_file, $plugin_data, $context ) {
            array_unshift( $actions, sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=ndfw_subscriber&page=ndfw-settings' ), esc_html__( 'Settings' ) ) );
            return $actions;
        }, 10, 4);
    }

    public function options() {

        $this->options_general 		= get_option( 'ndfw_general' );
        $this->options_newsletter 	= get_option( 'ndfw_newsletter' );
		$this->options_content 		= get_option( 'ndfw_content' );
		$this->options_privacy 		= get_option( 'ndfw_privacy' ); 

		$action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : 'general'; ?>

        <div class="wrap" id="ndfw_options">

            <h1><?php esc_html_e( 'Newsletter Discounts Settings', 'ndfw' ); ?></h1>
            
            <h2 class="nav-tab-wrapper">
				<a href="<?php echo admin_url( 'edit.php?post_type=ndfw_subscriber&page=ndfw-settings' ); ?>" class="nav-tab<?php if ( $action == 'general' ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'General', 'ndfw' ); ?></a>
                <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'display' ), admin_url( 'edit.php?post_type=ndfw_subscriber&page=ndfw-settings' ) ) ); ?>" class="nav-tab<?php if ( $action == 'display' ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Display', 'ndfw' ); ?></a> 
                <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'content' ), admin_url( 'edit.php?post_type=ndfw_subscriber&page=ndfw-settings' ) ) ); ?>" class="nav-tab<?php if ( $action == 'content' ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Content', 'ndfw' ); ?></a> 
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'privacy' ), admin_url( 'edit.php?post_type=ndfw_subscriber&page=ndfw-settings' ) ) ); ?>" class="nav-tab<?php if ( $action == 'privacy' ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Privacy', 'ndfw' ); ?></a> 
			</h2>
    
        	 <?php 
				
				if ( $action == 'content' ) { 

                    echo '<form method="post" action="options.php">';
					settings_fields( 'ndfw_content' );
					do_settings_sections( 'ndfw-setting-content' );
					submit_button();
                    echo '</form>';

                } elseif ( $action == 'privacy' ) {

                    echo '<form method="post" action="options.php">';
                    settings_fields( 'ndfw_privacy' );
                    do_settings_sections( 'ndfw-setting-privacy' );
                    submit_button();
                    echo '</form>';

                } elseif ( $action == 'display' ) {

                    echo '<form method="post" action="options.php">';
                    settings_fields( 'ndfw_display' );
                    do_settings_sections( 'ndfw-setting-display' );
                    submit_button();
                    echo '</form>';

				} elseif ( $action == 'general' ) { 

                    echo '<form method="post" action="options.php">';
                    settings_fields( 'ndfw_discount' );
                    do_settings_sections( 'ndfw-setting-discount' );
                    submit_button(); 
                    echo '</form>';

                    echo '<form method="post" action="options.php" id="ndfw_newsletter_form">';
                    settings_fields( 'ndfw_newsletter' );
                    do_settings_sections( 'ndfw-setting-newsletter' );
                    submit_button(); 
                    echo '</form>';

				} ?>
			
        </div> 
        <?php

	}

    public function settings_defaults() {

        $content = array(
            'popup_form_headline'       => esc_html__( 'Save 20% OFF!', 'ndfw' ),
            'popup_form_body'           => esc_html__( 'Grab a 20% discount off your next order on anything in the store', 'ndfw' ),
            'popup_form_note'           => esc_html__( 'Hurry up. This is a limited time offer!', 'ndfw' ),
            'popup_form_accept'         => esc_html__( 'Activate my discount!', 'ndfw' ),
            'popup_form_decline'        => esc_html__( 'No, I want to pay full price.', 'ndfw' ),
            'popup_form_email'          => esc_html__( 'Enter your email address', 'ndfw' ),
            'popup_form_fname'          => esc_html__( 'Your first name', 'ndfw' ),
            'popup_form_lname'          => esc_html__( 'Your last name', 'ndfw' ),
            'popup_success_headline'    => esc_html__( 'Congratulations', 'ndfw' ),
            'popup_success_body'        => esc_html__( 'Your discount has been activated. It will be applied on checkout page.', 'ndfw' ),
            'popup_success_cta_button'  => esc_html__( 'Shop Now', 'ndfw' ),
        );
        add_option( 'ndfw_content', $content );

        $privacy = array(
            'popup_consent_enabled'     => 'on',
            'popup_consent_info'        => sprintf( esc_html__( 'I agree to my personal data being stored and used to receive the newsletter.', 'ndfw' ), get_bloginfo( 'name' ) ),
            'popup_consent_marketing'   => sprintf( esc_html__( 'I agree to receive information and commercial offers about %s.', 'ndfw' ), get_bloginfo( 'name' ) ),
            'popup_consent_text'        => sprintf( esc_html__( '%s will use the information you provided on this form to be in touch with you and to provide updates and marketing. Please let us know all the way you would like to hear from us:', 'ndfw' ), get_bloginfo( 'name' ) ),
            'popup_consent_note'        => sprintf( esc_html__( 'You can change your mind at any time by clicking the unsubscribe link in the footer of any email you receive from us, by contacting us at %s. We will treat your information with respect. For more information about privacy practices please visit our privacy page. By clicking below, you agree that we may process your information in accordance with these terms.', 'ndfw' ), get_bloginfo( 'admin_email' ) ),
        );
        add_option( 'ndfw_privacy', $privacy );

        $display = array(
            'popup_reveal'              => 'after_5',
            'popup_exclude_account'     => 'on',
            'popup_exclude_chechkout'   => 'on',
            'popup_exclude_cart'        => 'on',
        );
        add_option( 'ndfw_display', $display );
        
        $discount = array(
            'amount'   => '20',
            'expiry'   => '7',
            'type'     => 'percent',
        );
        add_option( 'ndfw_discount', $discount );
        
        $timer = array(
            'status'        => 'on',
            'value_days'    => '1',
            'value_hours'   => '0',
            'value_minutes' => '0',
            'value_seconds' => '0',
            'action'        => 'reset',
        );
        add_option( 'ndfw_timer', $timer );

    }

    public function register_settings() {
        $this->register_settings_content();
        $this->register_settings_discount();
        $this->register_settings_display();
        $this->register_settings_newsletter();
        $this->register_settings_privacy();
    }

  	public function register_settings_content() { 

		register_setting( 'ndfw_content', 'ndfw_content' );

        add_settings_section( 'ndfw_section_content_popup', esc_html__( 'Popup form screen', 'ndfw' ),
            function() {
				printf( '<p>%s</p>', esc_html__('Customize the text for popup form screen.', 'ndfw') );
			},
            'ndfw-setting-content'
        );  
		
		add_settings_field( 'popup_form_headline', esc_html__( 'Headline text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_headline', 'content' );
            	printf( '<textarea id="ndfw_popup_form_headline" name="ndfw_content[popup_form_headline]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
        
        add_settings_field( 'popup_form_body', esc_html__( 'Body text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_body', 'content' );
                printf( '<textarea id="ndfw_popup_form_body" name="ndfw_content[popup_form_body]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
        
        add_settings_field( 'popup_form_note', esc_html__( 'Note text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_note', 'content' );
                printf( '<textarea id="ndfw_popup_form_note" name="ndfw_content[popup_form_note]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
		
		add_settings_field( 'popup_form_accept', esc_html__( 'Accept button', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_accept', 'content' );
            	printf( '<input type="text" id="ndfw_popup_form_accept" name="ndfw_content[popup_form_accept]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
		
		add_settings_field( 'popup_form_decline', esc_html__( 'Decline button', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_decline', 'content' );
            	printf( '<input type="text" id="ndfw_popup_form_decline" name="ndfw_content[popup_form_decline]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
        
        add_settings_field( 'popup_form_email', esc_html__( 'Email input', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_email', 'content' );
                printf( '<input type="text" id="ndfw_popup_form_email" name="ndfw_content[popup_form_email]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
        
        add_settings_field( 'popup_form_fname', esc_html__( 'First name input', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_fname', 'content' );
                printf( '<input type="text" id="ndfw_popup_form_fname" name="ndfw_content[popup_form_fname]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );
		
        add_settings_field( 'popup_form_lname', esc_html__( 'Last name input', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_form_lname', 'content' );
                printf( '<input type="text" id="ndfw_popup_form_lname" name="ndfw_content[popup_form_lname]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup'        
        );

        add_settings_section( 'ndfw_section_content_popup_success', esc_html__( 'Popup success screen', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__('Customize the text for popup success screen.', 'ndfw') );
            },
            'ndfw-setting-content'
        );  
        
        add_settings_field( 'popup_success_headline', esc_html__( 'Headline text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_success_headline', 'content' );
                printf( '<textarea id="ndfw_popup_success_headline" name="ndfw_content[popup_success_headline]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup_success'        
        );
        
        add_settings_field( 'popup_success_body', esc_html__( 'Body text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_success_body', 'content' );
                printf( '<textarea id="ndfw_popup_success_body" name="ndfw_content[popup_success_body]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup_success'        
        );
        
        add_settings_field( 'popup_success_cta_button', esc_html__( 'Button text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_success_cta_button', 'content' );
                printf( '<input type="text" id="ndfw_popup_success_cta_button" name="ndfw_content[popup_success_cta_button]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-content',
            'ndfw_section_content_popup_success'        
        );

	}

    public function register_settings_discount() {
 
        register_setting( 'ndfw_discount', 'ndfw_discount' );

        add_settings_section( 'ndfw_section_discount', esc_html__( 'Discount Offer', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__( 'These settings will be used to create coupon codes for your subscribers.', 'ndfw' ) );
            },
            'ndfw-setting-discount'
        );

        add_settings_field( 'discount_type', esc_html__( 'Discount type', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'type', 'discount' );
                echo '<select id="ndfw_discount_type" name="ndfw_discount[type]" class="regular-text">';
                printf( '<option value="%s" %s>%s</option>', 'fixed_cart', ( $value == 'fixed_cart' ) ? 'selected' : '', esc_html__( 'Fixed cart discount', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'fixed_product', ( $value == 'fixed_product' ) ? 'selected' : '', esc_html__( 'Fixed product discount', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'percent', ( $value == 'percent' ) ? 'selected' : '', esc_html__( 'Percentage discount', 'ndfw' ) );
                echo '</select>';
            },
            'ndfw-setting-discount', 
            'ndfw_section_discount'
        );

        add_settings_field( 'discount_amount', esc_html__( 'Discount amount', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'amount', 'discount', '20' );
                printf( '<input type="number" step="1" min="0" id="ndfw_discount_amount" name="ndfw_discount[amount]" class="regular-text" value="%s" required />', esc_html($value) );
            },
            'ndfw-setting-discount', 
            'ndfw_section_discount'
        );

    }

    public function register_settings_display() {

        register_setting( 'ndfw_display', 'ndfw_display' );

        add_settings_section( 'ndfw_section_display', esc_html__( 'Popup', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__( 'Customize display settings for the form popup.', 'ndfw' ) );
            },
            'ndfw-setting-display'
        );

        add_settings_field( 'display_popup_form', esc_html__( 'Form', 'ndfw' ),
            function() {

                $label = esc_html__( 'Show first name and last name inputs.', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_form_hide_name', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_form_hide_name]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                printf( '<p class="description">%s</p>', esc_html( 'You can ask your subscribers to fill their names along with their emails.', 'ndfw' ) );
            },
            'ndfw-setting-display', 
            'ndfw_section_display'
        );

        add_settings_field( 'display_popup_reveal', esc_html__( 'Popup reveal', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_reveal', 'display' );
                echo '<select id="ndfw_display_reveal" name="ndfw_display[popup_reveal]">';
                printf( '<option value="%s" %s>%s</option>', '', ( $value == '' ) ? 'selected' : '', esc_html__( 'Immediately', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'after_5', ( $value == 'after_5' ) ? 'selected' : '', esc_html__( 'After 5 seconds', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'after_20', ( $value == 'after_20' ) ? 'selected' : '', esc_html__( 'After 20 seconds', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'on_exit', ( $value == 'on_exit' ) ? 'selected' : '', esc_html__( 'On exit (exit intent)', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'scroll_mid', ( $value == 'scroll_mid' ) ? 'selected' : '', esc_html__( 'Scroll to middle of the page', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'scroll_bottom', ( $value == 'scroll_bottom' ) ? 'selected' : '', esc_html__( 'Scroll to bottom of the page', 'ndfw' ) );
                echo '</select>';

                printf( '<p class="description">%s</p>', esc_html( 'Customize when the popup will be displayed to your visitors.', 'ndfw' ) );
            },
            'ndfw-setting-display', 
            'ndfw_section_display'
        );  

        add_settings_field( 'display_popup_pages', esc_html__( 'Pages', 'ndfw' ),
            function() {

                $label = esc_html__( 'Account page', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_exclude_account', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_exclude_account]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                $label = esc_html__( 'Cart page', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_exclude_cart', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_exclude_cart]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                $label = esc_html__( 'Checkout page', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_exclude_checkout', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_exclude_checkout]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                $label = esc_html__( 'Home page', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_exclude_home', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_exclude_home]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                printf( '<p class="description">%s</p>', esc_html( 'You can hide the popup on certain pages.', 'ndfw' ) );
            },
            'ndfw-setting-display', 
            'ndfw_section_display'
        );

        add_settings_field( 'display_popup_devices', esc_html__( 'Devices', 'ndfw' ),
            function() {

                $label = esc_html__( 'Desktop', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_hide_desktop', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_hide_desktop]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                $label = esc_html__( 'Mobile', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_hide_mobile', 'display' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_display[popup_hide_mobile]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html($label) );

                printf( '<p class="description">%s</p>', esc_html( 'You can hide the popup on certain types of devices.', 'ndfw' ) );
            },
            'ndfw-setting-display', 
            'ndfw_section_display'
        );

    }

    public function register_settings_newsletter() {

        register_setting( 'ndfw_newsletter', 'ndfw_newsletter' );

        add_settings_section( 'ndfw_section_newsletter', esc_html__( 'Newsletter Service', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__( 'Connect a newsletter service to sync your subscribers with your newsletter lists.', 'ndfw' ) );
            },
            'ndfw-setting-newsletter'
        );

        add_settings_field( 'service', esc_html__( 'Newsletter Form', 'ndfw' ),
            function() {
                $service = ndfw_settings()->get_setting( 'service', 'newsletter' ); 
                echo '<select id="ndfw_newsletter_service" name="ndfw_newsletter[service]">';
                printf( '<option value="%s" %s>%s</option>', '', ( $service == '' ) ? 'selected' : '', esc_html__( 'Select newsletter service', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'drip', ( $service == 'drip' ) ? 'selected' : '', esc_html__( 'Drip', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'klaviyo', ( $service == 'klaviyo' ) ? 'selected' : '', esc_html__( 'Klaviyo', 'ndfw' ) );
                printf( '<option value="%s" %s>%s</option>', 'mailchimp', ( $service == 'mailchimp' ) ? 'selected' : '', esc_html__( 'Mailchimp', 'ndfw' ) );
                echo '</select>';
                printf( '<p class="description">%s</p>', esc_html( 'Choose a newsletter service on which your subscribers will be synced.', 'ndfw' ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter'
        );

        add_settings_field( 'drip_api', esc_html__( 'Drip API', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'drip_api', 'newsletter' ); 
                printf( '<input type="text" id="ndfw_drip_api" name="ndfw_newsletter[drip_api]" class="regular-text" value="%s" />', esc_html( $key ) );
                printf( '<button id="ndfw_drip_connect" class="button ndfw_drip_connect" style="margin: 0 8px;">%s</button>', esc_html__( 'Connect', 'ndfw' ) );
                printf( '<p class="description">%s</p>', sprintf( '<a href="%s" target="_blank">%s</a>', esc_url('https://help.drip.com/hc/en-us/articles/115003738532-Your-API-Token'), esc_html__( 'Retrieve your account API key', 'ndfw' ) ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'drip' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

        add_settings_field( 'drip_account', esc_html__( 'Drip Account', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'drip_api', 'newsletter' ); 
                $selected = ndfw_settings()->get_setting( 'drip_account', 'newsletter' );
                $accounts = $this->drip_accounts( $key );
                echo '<select id="ndfw_drip_list" name="ndfw_newsletter[drip_account]">';
                if ( $accounts ) {
                    foreach ( $accounts as $key => $account ) {
                        $account_id = ( isset( $account['id'] ) ) ? $account['id'] : '';
                        $account_name = ( isset( $account['name'] ) ) ? $account['name'] : '';
                        if ( !empty( $account_id ) && !empty( $account_name ) ) {
                            printf( '<option value="%s" %s>%s</option>', esc_html($account_id), ( $account_id == $selected ) ? 'selected' : '', esc_html($account_name) );
                        }
                    }
                } else {
                    printf( '<option value="%s" %s disabled>%s</option>', '', ( $selected == '' ) ? 'selected' : '', esc_html__( 'No accounts are link to the API key above.', 'ndfw' ) );
                }
                echo '</select>';
                printf( '<p class="description">%s</p>', esc_html( 'Select an account on which you want to post emails from your newsletter form.', 'ndfw' ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'drip' || ndfw_settings()->get_setting( 'drip_api', 'newsletter' ) == '' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

        add_settings_field( 'klaviyo_api', esc_html__( 'Klaviyo API', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'klaviyo_api', 'newsletter' ); 
                printf( '<input type="text" id="ndfw_klaviyo_api" name="ndfw_newsletter[klaviyo_api]" class="regular-text" value="%s" />', esc_html( $key ) );
                printf( '<button id="ndfw_klaviyo_connect" class="button ndfw_klaviyo_connect" style="margin: 0 8px;">%s</button>', esc_html__( 'Connect', 'ndfw' ) );
                printf( '<p class="description">%s</p>', sprintf( '<a href="%s" target="_blank">%s</a>', esc_url('https://help.klaviyo.com/hc/en-us/articles/115005062267-Manage-Your-Account-s-API-Keys'), esc_html__( 'Retrieve your account API key', 'ndfw' ) ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'klaviyo' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

        add_settings_field( 'klaviyo_list', esc_html__( 'Klaviyo List', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'klaviyo_api', 'newsletter' ); 
                $selected = ndfw_settings()->get_setting( 'klaviyo_list', 'newsletter' );
                $lists = $this->klaviyo_lists( $key );
                echo '<select id="ndfw_klaviyo_list" name="ndfw_newsletter[klaviyo_list]">';
                printf( '<option value="%s" %s disabled>%s</option>', '', ( $selected == '' ) ? 'selected' : '', esc_html__( 'Select a list', 'ndfw' ) );
                if ( $lists ) {
                    foreach ($lists as $key => $list) {
                        $list_id = ( isset( $list['id'] ) ) ? $list['id'] : '';
                        $list_name = ( isset( $list['name'] ) ) ? $list['name'] : '';
                        if ( !empty( $list_id ) && !empty( $list_name ) ) {
                            printf( '<option value="%s" %s>%s</option>', esc_html($list_id), ( $list_id == $selected ) ? 'selected' : '', esc_html($list_name) );
                        }
                    }
                }
                echo '</select>';
                printf( '<p class="description">%s</p>', esc_html( 'Select a list on which you want to post emails from your newsletter form.', 'ndfw' ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'klaviyo' || ndfw_settings()->get_setting( 'klaviyo_api', 'newsletter' ) == '' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

        add_settings_field( 'mailchimp_api', esc_html__( 'Mailchimp API', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'mailchimp_api', 'newsletter' ); 
                printf( '<input type="text" id="ndfw_mailchimp_api" name="ndfw_newsletter[mailchimp_api]" class="regular-text" value="%s" />', esc_html( $key ) );
                printf( '<button id="ndfw_mailchimp_connect" class="button ndfw_mailchimp_connect" style="margin: 0 8px;">%s</button>', esc_html__( 'Connect', 'ndfw' ) );
                printf( '<p class="description">%s</p>', sprintf( '<a href="%s" target="_blank">%s</a>', esc_url('https://kb.mailchimp.com/integrations/api-integrations/about-api-keys#Finding-or-generating-your-API-key'), esc_html__( 'Retrieve your account API key', 'ndfw' ) ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'mailchimp' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

        add_settings_field( 'mailchimp_list', esc_html__( 'Mailchimp List', 'ndfw' ),
            function() {
                $key = ndfw_settings()->get_setting( 'mailchimp_api', 'newsletter' ); 
                $selected = ndfw_settings()->get_setting( 'mailchimp_list', 'newsletter' );
                $lists = $this->mailchimp_lists( $key );
                echo '<select id="ndfw_mailchimp_list" name="ndfw_newsletter[mailchimp_list]">';
                printf( '<option value="%s" %s disabled>%s</option>', '', ( $selected == '' ) ? 'selected' : '', esc_html__( 'Select a list', 'ndfw' ) );
                if ( $lists ) {
                    foreach ($lists as $key => $list) {
                        $list_id = ( isset( $list['id'] ) ) ? $list['id'] : '';
                        $list_name = ( isset( $list['name'] ) ) ? $list['name'] : '';
                        if ( !empty( $list_id ) && !empty( $list_name ) ) {
                            printf( '<option value="%s" %s>%s</option>', esc_html($list_id), ( $list_id == $selected ) ? 'selected' : '', esc_html($list_name) );
                        }
                    }
                }
                echo '</select>';
                printf( '<p class="description">%s</p>', esc_html( 'Select a list on which you want to post emails from your newsletter form.', 'ndfw' ) );
            },
            'ndfw-setting-newsletter', 
            'ndfw_section_newsletter',
            [ 'class' => ( ndfw_settings()->get_setting( 'service', 'newsletter' ) != 'mailchimp' || ndfw_settings()->get_setting( 'mailchimp_api', 'newsletter' ) == '' ) ? 'ndfw-options-row ndfw-options-row-hidden' : 'ndfw-options-row' ] 
        );

    }

    public function register_settings_privacy() { 

        register_setting( 'ndfw_privacy', 'ndfw_privacy' );

        add_settings_section( 'ndfw_section_privacy_settings', esc_html__( 'GDPR', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__( 'Make your popup GDPR complient.', 'ndfw' ) );
            },
            'ndfw-setting-privacy'
        );

        add_settings_field( 'display_popup_form', esc_html__( 'GDPR Fields', 'ndfw' ),
            function() {

                $label = esc_html__( 'Show GDPR fields.', 'ndfw' );
                $value = ndfw_settings()->get_setting( 'popup_consent_enabled', 'privacy' );
                printf( '<fieldset><label><input type="checkbox" name="ndfw_privacy[popup_consent_enabled]" value="on" %s> %s</label></fieldset>', ( $value == 'on' ) ? 'checked' : '', esc_html( $label ) );

                printf( '<p class="description">%s</p>', esc_html( 'This will include GDPR complient fields in your form.', 'ndfw' ) );
            },
            'ndfw-setting-privacy', 
            'ndfw_section_privacy_settings'
        );

        add_settings_section( 'ndfw_section_privacy_content', esc_html__( 'GDPR fields', 'ndfw' ),
            function() {
                printf( '<p>%s</p>', esc_html__('Customize the text for popup form GDPR fields.', 'ndfw') );
            },
            'ndfw-setting-privacy'
        );
        
        add_settings_field( 'popup_consent_text', esc_html__( 'Consent text', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_consent_text', 'privacy' );
                printf( '<textarea id="ndfw_consent_text" name="ndfw_privacy[popup_consent_text]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-privacy',
            'ndfw_section_privacy_content'        
        );
        
        add_settings_field( 'popup_consent_note', esc_html__( 'Clarification', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_consent_note', 'privacy' );
                printf( '<textarea id="ndfw_consent_note" name="ndfw_privacy[popup_consent_note]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-privacy',
            'ndfw_section_privacy_content'        
        );
        
        add_settings_field( 'popup_consent_info', esc_html__( 'Info consent', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_consent_info', 'privacy' );
                printf( '<textarea id="ndfw_consent_info" name="ndfw_privacy[popup_consent_info]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-privacy',
            'ndfw_section_privacy_content'        
        );
        
        add_settings_field( 'popup_consent_marketing', esc_html__( 'Marketing consent', 'ndfw' ),
            function() {
                $value = ndfw_settings()->get_setting( 'popup_consent_marketing', 'privacy' );
                printf( '<textarea id="ndfw_consent_marketing" name="ndfw_privacy[popup_consent_marketing]" class="regular-text" required>%s</textarea>', esc_html($value) );
            },
            'ndfw-setting-privacy',
            'ndfw_section_privacy_content'        
        );

    }

    public function drip_accounts( $key ) {

        if ( empty( $key ) ) {
            return false;
        }

        $accounts_array = array();

        try {
            
            $drip        = new Drip_Api($key);
            $response    = $drip->get_accounts();
            $accounts    = $response;

            if ( !empty($accounts) ) {

                foreach ($accounts as $account) {
                    if ( isset ( $account[ 'id' ] ) && isset( $account[ 'name' ] ) ) {
                        $accounts_array[] = array( 'id' => $account[ 'id' ], 'name' => $account[ 'name' ] );
                    }
                }

            } else {

                return array();

            }

        } catch ( Exception $e ) { }

        return $accounts_array;

    }

    public function klaviyo_lists( $key ) {

        if ( empty( $key ) ) {
            return false;
        }

        $lists_array = array();

        try {

            $klaviyo        = new Klaviyo($key);
            $response       = $klaviyo->get_lists();
            $lists          = $response[ 'body' ]->data;

            if ( ! empty( $lists ) ) {

                foreach ( $lists as $list ) {
                    $lists_array[] = array( 'id' => $list->id, 'name' => $list->name );
                }

            } else {

                return array();

            }
            
        } catch ( Exception $e ) {
            
        }

        return $lists_array;

    }

    public function mailchimp_lists( $key ) {

        if ( empty( $key ) ) {
            return false;
        }

        $lists_array = array();

        try {

            $MailChimp  = new MailChimp($key);
            $lists      = $MailChimp->get('lists');

            if ( $lists ) {

                if ( isset( $lists[ 'lists' ] ) ) {

                    $lists_array = array();

                    foreach ( $lists['lists'] as $key => $list ) {
                        $lists_array[] = array( 'id' => $list[ 'id' ], 'name' => $list[ 'name' ] );
                    }

                    return $lists_array;

                } else {

                    return array();

                }

            } else {

                return false;

            }
            
        } catch ( Exception $e ) {
            
        }
        
    }

    public function get_setting( $name, $section, $default = '' ) {

        if ( empty( $name ) || empty( $section ) ) {
            return false;
        }

        $option = get_option( 'ndfw_' . $section );
        $setting = ( isset( $option[$name] ) ) ? $option[$name] : '';
        $setting = ( empty( $setting ) && !empty( $default ) ) ? $default : $setting;

        return $setting;
    }

    public function update_setting( $name, $section, $value ) {

        if ( empty( $name ) || empty( $section ) ) {
            return false;
        }

        $option = get_option( 'ndfw_' . $section );

        if( ! isset( $option[ $name ] ) ) {
            return false;
        }

        $option[ $name ] = $value;
        return update_option( 'ndfw_' . $section, $option );

    }

}
endif;

function ndfw_settings() {
    return NDFW_Settings::instance();
}

$GLOBALS['ndfw_settings'] = ndfw_settings(); ?>