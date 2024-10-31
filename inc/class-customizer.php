<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'NDFW_Customizer' ) ) :
class NDFW_Customizer {

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
		add_action( 'customize_register', array( $this, 'add_sections' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'add_scripts' ), 30 );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_frontend_scripts' ) );
	}

	function add_frontend_scripts() {
		wp_enqueue_script( 'ndfw-scripts-customizer-preview', ndfw()->plugin_url() . '/assets/js/customizer-preview.min.js', array( 'customize-preview' ), ndfw()->version, true );
	}

	function add_scripts() {
		wp_enqueue_script( 'ndfw-scripts-customizer-control', ndfw()->plugin_url() . '/assets/js/customizer-control.min.js', array( 'jquery' ), ndfw()->version, true );
	}

	public function add_sections( $wp_customize ) {
		$wp_customize->add_panel( 'ndfw_popup', array(
			'priority'       => 250,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Newsletter Discounts Popup', 'ndfw' ),
		) );

		$this->add_popup_section( $wp_customize );
	}

	public function add_popup_section( $wp_customize ) {

		require_once NDFW_PLUGIN_DIR . 'libs/customizer-custom-controls/inc/custom-controls.php';
		require_once NDFW_PLUGIN_DIR . 'inc/class-controls.php';

		$wp_customize->add_section( 'ndfw_popup_background', array(
		    'title'      		=> esc_html__( 'Background', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 10,
		) );

		$wp_customize->add_setting( 'ndfw_popup_background_color', array(
			'default'			=> '#ffffff',
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_background_color', array(
	 	   	'label'   			=> esc_html__( 'Background color','ndfw' ),
			'section' 			=> 'ndfw_popup_background',
	   	 	'settings' 			=> 'ndfw_popup_background_color',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_background_color' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_background_overlay', array(
			'default'			=> 'rgba(0, 0, 0, 0.3)',
		) );
		$wp_customize->add_control( new NDFW_Customize_Alpha_Color_Control( $wp_customize, 'ndfw_popup_background_overlay', array(
			'label' 			=> esc_html__( 'Overlay colors', 'ndfw' ),
			'section' 			=> 'ndfw_popup_background',
	   	 	'settings' 			=> 'ndfw_popup_background_overlay',
			'show_opacity' 		=> true,
	    	'priority' 			=> 2
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_background_overlay' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_border_radius', array(
			'default'			=> 10,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_border_radius', array(
		    'label' 			=> esc_html__( 'Border radius' ),
		    'section' 			=> 'ndfw_popup_background',
	   	 	'settings' 			=> 'ndfw_popup_border_radius',
		    'input_attrs' 		=> array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	    	'priority' 			=> 3
		) ) );
		$wp_customize->get_setting('ndfw_popup_border_radius')->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_image', array(
		    'title'      		=> esc_html__( 'Image', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 10,
		) );

		$wp_customize->add_setting( 'ndfw_popup_image_main' , array(
	    	'sanitize_callback' => 'sanitize_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ndfw_popup_image_main', array(
	    	'label'    			=> 	esc_html__( 'Main image', 'ndfw' ),
	    	'section'  			=> 	'ndfw_popup_image',
	    	'settings' 			=> 	'ndfw_popup_image_main',
	    	'priority' 			=> 	2
		) ) );
		//$wp_customize->get_setting( 'ndfw_popup_image_main')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_image_success' , array(
	    	'sanitize_callback' => 'sanitize_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ndfw_popup_image_success', array(
	    	'label'    			=> 	esc_html__( 'Success image (optional)', 'ndfw' ),
	    	'section'  			=> 	'ndfw_popup_image',
	    	'settings' 			=> 	'ndfw_popup_image_success',
	    	'priority' 			=> 	3
		) ) );
		//$wp_customize->get_setting('ndfw_popup_image_success')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_image_mobile' , array(
	    	'sanitize_callback' => 'sanitize_url',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ndfw_popup_image_mobile', array(
	    	'label'    			=> esc_html__( 'Mobile image (optional)', 'ndfw' ),
	    	'section'  			=> 'ndfw_popup_image',
	    	'settings' 			=> 'ndfw_popup_image_mobile',
	    	'priority' 			=> 4
		) ) );
		//$wp_customize->get_setting('ndfw_popup_image_mobile')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_image_layout', array(
			'default' 			=> 'top',
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control( new NDFW_Image_Radio_Button_Custom_Control( $wp_customize, 'ndfw_popup_image_layout', array(
			'label' 			=> esc_html__( 'Layout', 'ndfw' ),
			'section' 			=> 'ndfw_popup_image',
	    	'settings' 			=> 'ndfw_popup_image_layout',
	    	'priority' 			=> 5,
			'choices' 			=> array(
									'left' => array(
												'image' => ndfw()->plugin_url() . '/assets/images/customizer/image-layout-left.png',
												'name' 	=> esc_html__( 'Left', 'ndfw' )
									),
									'top' => array(
												'image' => ndfw()->plugin_url() . '/assets/images/customizer/image-layout-top.png',
												'name' 	=> esc_html__( 'Top', 'ndfw' )
									),
									'right' => array(
												'image' => ndfw()->plugin_url() . '/assets/images/customizer/image-layout-right.png',
												'name' 	=> esc_html__( 'Right', 'ndfw' )
									) )
		) ) );
		//$wp_customize->get_setting('ndfw_popup_image_layout')->transport = 'postMessage';

		$wp_customize->add_setting('ndfw_popup_image_hide', array(
			'sanitize_callback' => 'sanitize_text_field',
		));
		$wp_customize->add_control('ndfw_popup_image_hide', array(
	 	   	'label'   			=> esc_html__( 'Hide image', 'ndfw' ),
			'section' 			=> 'ndfw_popup_image',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_image_hide',
	    	'priority' 			=> 6
		));	
		//$wp_customize->get_setting('ndfw_popup_image_hide')->transport = 'postMessage';


		$wp_customize->add_section( 'ndfw_popup_headline', array(
		    'title'      		=> esc_html__( 'Headline', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 20,
		) );

		$wp_customize->add_setting( 'ndfw_popup_headline_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_headline_color', array(
	 	   	'label'   			=> esc_html__( 'Text color','ndfw' ),
			'section' 			=> 'ndfw_popup_headline',
	   	 	'settings' 			=> 'ndfw_popup_headline_color',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_headline_color')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_headline_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_headline_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_headline',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_headline_family',
	    	'priority' 			=> 2
		) );
		$wp_customize->get_setting( 'ndfw_popup_headline_family')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_headline_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_headline_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_headline',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_headline_weight',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_headline_weight' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_headline_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		));
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_headline_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_headline',
	   	 	'settings' 			=> 'ndfw_popup_headline_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 4
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_headline_size' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_headline_align', array(
			'default' 			=> 'center',
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( new NDFW_Customize_Text_Radio_Button_Control( $wp_customize, 'ndfw_popup_headline_align', array(
		  	'label' 			=> esc_html__( 'Text align', 'ndfw' ),
	   	 	'section' 			=> 'ndfw_popup_headline',
	   	 	'settings' 			=> 'ndfw_popup_headline_align',
		  	'choices' 			=> array( 'left' => esc_html__( 'Left', 'ndfw' ), 'center' => esc_html__( 'Centered', 'ndfw' ), 'right' => esc_html__( 'Right', 'ndfw' ) ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_headline_align' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_headline_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_headline_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_headline',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_headline_italic',
	    	'priority' 			=> 6
		) );	
		$wp_customize->get_setting('ndfw_popup_headline_italic')->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_body', array(
		    'title'      		=> esc_html__( 'Body', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 30,
		) );

		$wp_customize->add_setting( 'ndfw_popup_body_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_body_color', array(
	 	   	'label'   			=> esc_html__( 'Text color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_body',
	   	 	'settings' 			=> 'ndfw_popup_body_color',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_body_color')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_body_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_body_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_body',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_body_family',
	    	'priority' 			=> 2
		) );
		$wp_customize->get_setting( 'ndfw_popup_body_family')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_body_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_body_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_body',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_body_weight',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_body_weight')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_body_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_body_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_body',
	   	 	'settings' 			=> 'ndfw_popup_body_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 4
		) ) );	
		$wp_customize->get_setting( 'ndfw_popup_body_size')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_body_align', array(
			'default' 			=> 'center',
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( new NDFW_Customize_Text_Radio_Button_Control( $wp_customize, 'ndfw_popup_body_align', array(
		  	'label' 			=> esc_html__( 'Text align', 'ndfw' ),
	   	 	'section' 			=> 'ndfw_popup_body',
	   	 	'settings' 			=> 'ndfw_popup_body_align',
		  	'choices' 			=> array( 'left' => esc_html__( 'Left', 'ndfw' ), 'center' => esc_html__( 'Centered', 'ndfw' ), 'right' => esc_html__( 'Right', 'ndfw' ) ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_body_align' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_body_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_body_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_body',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_body_italic',
	    	'priority' 			=> 6
		) );	
		$wp_customize->get_setting( 'ndfw_popup_body_italic' )->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_note', array(
		    'title'      		=> esc_html__( 'Note', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 70,
		) );

		$wp_customize->add_setting( 'ndfw_popup_note_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_note_color', array(
	 	   	'label'   			=> esc_html__( 'Text color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_note',
	   	 	'settings' 			=> 'ndfw_popup_note_color',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_note_color')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_note_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_note_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_note',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_note_family',
	    	'priority' 			=> 2
		) );
		$wp_customize->get_setting( 'ndfw_popup_note_family')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_note_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_note_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_note',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_note_weight',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_note_weight')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_note_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_note_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_note',
	   	 	'settings' 			=> 'ndfw_popup_note_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 4
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_note_size')->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_note_align', array(
			'default' 			=> 'center',
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( new NDFW_Customize_Text_Radio_Button_Control( $wp_customize, 'ndfw_popup_note_align', array(
		  	'label' 			=> esc_html__( 'Text align', 'ndfw' ),
	   	 	'section' 			=> 'ndfw_popup_note',
	   	 	'settings' 			=> 'ndfw_popup_note_align',
		  	'choices' 			=> array( 'left' => esc_html__( 'Left', 'ndfw' ), 'center' => esc_html__( 'Centered', 'ndfw' ), 'right' => esc_html__( 'Right', 'ndfw' ) ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_note_align' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_note_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control('ndfw_popup_note_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_note',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_note_italic',
	    	'priority' 			=> 6
		) );	
		$wp_customize->get_setting( 'ndfw_popup_note_italic' )->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_inputs', array(
		    'title'      		=> esc_html__( 'Inputs', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 40,
		) );

		$wp_customize->add_setting( 'ndfw_popup_inputs_background', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_inputs_background', array(
	 	   	'label'   			=> esc_html__( 'Background color','ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'settings' 			=> 'ndfw_popup_inputs_background',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting('ndfw_popup_inputs_background')->transport = 'postMessage';

		$wp_customize->add_setting('ndfw_popup_inputs_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_inputs_color', array(
	 	   	'label'   			=> esc_html__( 'Text color','ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'settings' 			=> 'ndfw_popup_inputs_color',
	    	'priority' 			=> 2
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_inputs_color' )->transport = 'postMessage';


		$wp_customize->add_setting( 'ndfw_popup_inputs_placeholder', array(
			//'transport' 		=> 'postMessage'
		) );
		$wp_customize->add_control( new NDFW_Customize_Alpha_Color_Control( $wp_customize, 'ndfw_popup_inputs_placeholder', array(
			'label' 			=> esc_html__( 'Placeholder color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'settings' 			=> 'ndfw_popup_inputs_placeholder',
			'show_opacity' 		=> true,
	    	'priority' 			=> 3
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_inputs_placeholder' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_inputs_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_inputs_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_inputs_family',
	    	'priority' 			=> 4
		) );
		$wp_customize->get_setting( 'ndfw_popup_inputs_family' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_inputs_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_inputs_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_inputs',
	   	 	'settings' 			=> 'ndfw_popup_inputs_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_inputs_size' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_inputs_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_inputs_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_inputs_weight',
	    	'priority' 			=> 6
		) );
		$wp_customize->get_setting( 'ndfw_popup_inputs_weight' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_inputs_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_inputs_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_inputs',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_inputs_italic',
	    	'priority' 			=> 7
		) );	
		$wp_customize->get_setting( 'ndfw_popup_inputs_italic' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_inputs_radius', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_inputs_radius', array(
		    'label' 			=> esc_html__( 'Border radius (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_inputs',
	   	 	'settings' 			=> 'ndfw_popup_inputs_radius',
		    'input_attrs' 		=> array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	    	'priority' 			=> 8
		) ) );
		$wp_customize->get_setting('ndfw_popup_inputs_radius')->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_buttons', array(
		    'title'      		=> esc_html__( 'Buttons', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 40,
		) );

		$wp_customize->add_setting( 'ndfw_popup_buttons_background', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_buttons_background', array(
	 	   	'label'   			=> esc_html__( 'Background color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_buttons',
	   	 	'settings' 			=> 'ndfw_popup_buttons_background',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting('ndfw_popup_buttons_background')->transport = 'postMessage';

		$wp_customize->add_setting('ndfw_popup_buttons_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_buttons_color', array(
	 	   	'label'   			=> esc_html__( 'Text color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_buttons',
	   	 	'settings' 			=> 'ndfw_popup_buttons_color',
	    	'priority' 			=> 2
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_buttons_color' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_buttons_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_buttons_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_buttons',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_buttons_family',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_buttons_family' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_buttons_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_buttons_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_buttons',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_buttons_weight',
	    	'priority' 			=> 4
		) );
		$wp_customize->get_setting( 'ndfw_popup_buttons_weight' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_buttons_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_buttons_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_buttons',
	   	 	'settings' 			=> 'ndfw_popup_buttons_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_buttons_size' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_buttons_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_buttons_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_buttons',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_buttons_italic',
	    	'priority' 			=> 6
		) );	
		$wp_customize->get_setting( 'ndfw_popup_buttons_italic' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_buttons_radius', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_buttons_radius', array(
		    'label' 			=> esc_html__( 'Border radius (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_buttons',
	   	 	'settings' 			=> 'ndfw_popup_buttons_radius',
		    'input_attrs' 		=> array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	    	'priority' 			=> 7
		) ) );
		$wp_customize->get_setting('ndfw_popup_buttons_radius')->transport = 'postMessage';





		$wp_customize->add_section( 'ndfw_popup_links', array(
		    'title'      		=> esc_html__( 'Links', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 50,
		) );

		$wp_customize->add_setting( 'ndfw_popup_links_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_links_color', array(
	 	   	'label'   			=> esc_html__( 'Text color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_links',
	   	 	'settings' 			=> 'ndfw_popup_links_color',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_links_color' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_links_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_links_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_links',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_links_family',
	    	'priority' 			=> 2
		) );
		$wp_customize->get_setting( 'ndfw_popup_links_family' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_links_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_links_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_links',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_links_weight',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_links_weight' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_links_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_links_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_links',
	   	 	'settings' 			=> 'ndfw_popup_links_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 4
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_links_size' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_links_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_links_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_links',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_links_italic',
	    	'priority' 			=> 5
		) );	
		$wp_customize->get_setting( 'ndfw_popup_links_italic' )->transport = 'postMessage';



		$wp_customize->add_section( 'ndfw_popup_timer', array(
		    'title'      		=> esc_html__( 'Timer', 'ndfw' ),
		    'panel' 			=> 'ndfw_popup',
		    'priority' 			=> 50,
		) );

		$wp_customize->add_setting( 'ndfw_popup_timer_background', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_timer_background', array(
	 	   	'label'   			=> esc_html__( 'Background color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_timer',
	   	 	'settings' 			=> 'ndfw_popup_timer_background',
	    	'priority' 			=> 1
		) ) );
		$wp_customize->get_setting('ndfw_popup_timer_background')->transport = 'postMessage';

		$wp_customize->add_setting('ndfw_popup_timer_color', array(
	    	'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'ndfw_popup_timer_color', array(
	 	   	'label'   			=> esc_html__( 'Text color', 'ndfw' ),
			'section' 			=> 'ndfw_popup_timer',
	   	 	'settings' 			=> 'ndfw_popup_timer_color',
	    	'priority' 			=> 2
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_timer_color' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_timer_family', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_timer_family', array(
	 	   	'label'   			=> esc_html__( 'Font family', 'ndfw' ),
			'section' 			=> 'ndfw_popup_timer',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_font_list(),
	   	 	'settings' 			=> 'ndfw_popup_timer_family',
	    	'priority' 			=> 3
		) );
		$wp_customize->get_setting( 'ndfw_popup_timer_family' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_timer_weight', array(
	    	'sanitize_callback' => array( $this, 'sanitize_choices' ),
		) );
		$wp_customize->add_control( 'ndfw_popup_timer_weight', array(
	 	   	'label'   			=> esc_html__( 'Font weight', 'ndfw' ),
			'section' 			=> 'ndfw_popup_timer',
	   	 	'type'    			=> 'select',
	   	 	'choices' 			=> $this->text_weight_list(),
	   	 	'settings' 			=> 'ndfw_popup_timer_weight',
	    	'priority' 			=> 4
		) );
		$wp_customize->get_setting( 'ndfw_popup_timer_weight' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_timer_size', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_timer_size', array(
		    'label' 			=> esc_html__( 'Font size (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_timer',
	   	 	'settings' 			=> 'ndfw_popup_timer_size',
		    'input_attrs' 		=> array( 'min' => 6, 'max' => 72, 'step' => 1 ),
	    	'priority' 			=> 5
		) ) );
		$wp_customize->get_setting( 'ndfw_popup_timer_size' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_timer_italic', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'ndfw_popup_timer_italic', array(
	 	   	'label'   			=> esc_html__( 'Italic', 'ndfw' ),
			'section' 			=> 'ndfw_popup_timer',
	   	 	'type'    			=> 'checkbox',
	   	 	'settings' 			=> 'ndfw_popup_timer_italic',
	    	'priority' 			=> 6
		) );	
		$wp_customize->get_setting( 'ndfw_popup_timer_italic' )->transport = 'postMessage';

		$wp_customize->add_setting( 'ndfw_popup_timer_radius', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new NDFW_Customize_Slider_Control( $wp_customize, 'ndfw_popup_timer_radius', array(
		    'label' 			=> esc_html__( 'Border radius (px)', 'ndfw' ),
		    'section' 			=> 'ndfw_popup_timer',
	   	 	'settings' 			=> 'ndfw_popup_timer_radius',
		    'input_attrs' 		=> array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	    	'priority' 			=> 7
		) ) );
		$wp_customize->get_setting('ndfw_popup_timer_radius')->transport = 'postMessage';

	}

	public function sanitize_choices( $input, $setting ) {
	    global $wp_customize;
	 
	    $control = $wp_customize->get_control( $setting->id );
	 
	    if ( array_key_exists( $input, $control->choices ) ) {
	        return $input;
	    } else {
	        return $setting->default;
	    }
	    return $input;
	}

	public function text_font_list( $type = 'all' ) {

		$fonts = array();

		switch ($type) {
			case 'all':
				$fonts[''] = esc_html( 'Default' );
				$fonts['ABeeZee'] = esc_html( 'ABeeZee' );
				$fonts['Abel'] = esc_html( 'Abel' );
				$fonts['Abhaya Libre'] = esc_html( 'Abhaya Libre' );
				$fonts['Abril Fatface'] = esc_html( 'Abril Fatface' );
				$fonts['Aclonica'] = esc_html( 'Aclonica' );
				$fonts['Acme'] = esc_html( 'Acme' );
				$fonts['Actor'] = esc_html( 'Actor' );
				$fonts['Adamina'] = esc_html( 'Adamina' );
				$fonts['Advent Pro'] = esc_html( 'Advent Pro' );
				$fonts['Aguafina Script'] = esc_html( 'Aguafina Script' );
				$fonts['Akronim'] = esc_html( 'Akronim' );
				$fonts['Aladin'] = esc_html( 'Aladin' );
				$fonts['Aldrich'] = esc_html( 'Aldrich' );
				$fonts['Alef'] = esc_html( 'Alef' );
				$fonts['Alegreya'] = esc_html( 'Alegreya' );
				$fonts['Alegreya SC'] = esc_html( 'Alegreya SC' );
				$fonts['Alegreya Sans'] = esc_html( 'Alegreya Sans' );
				$fonts['Alegreya Sans SC'] = esc_html( 'Alegreya Sans SC' );
				$fonts['Alex Brush'] = esc_html( 'Alex Brush' );
				$fonts['Alfa Slab One'] = esc_html( 'Alfa Slab One' );
				$fonts['Alice'] = esc_html( 'Alice' );
				$fonts['Alike'] = esc_html( 'Alike' );
				$fonts['Alike Angular'] = esc_html( 'Alike Angular' );
				$fonts['Allan'] = esc_html( 'Allan' );
				$fonts['Allerta'] = esc_html( 'Allerta' );
				$fonts['Allerta Stencil'] = esc_html( 'Allerta Stencil' );
				$fonts['Allura'] = esc_html( 'Allura' );
				$fonts['Almendra'] = esc_html( 'Almendra' );
				$fonts['Almendra Display'] = esc_html( 'Almendra Display' );
				$fonts['Almendra SC'] = esc_html( 'Almendra SC' );
				$fonts['Amarante'] = esc_html( 'Amarante' );
				$fonts['Amaranth'] = esc_html( 'Amaranth' );
				$fonts['Amatic SC'] = esc_html( 'Amatic SC' );
				$fonts['Amethysta'] = esc_html( 'Amethysta' );
				$fonts['Amiko'] = esc_html( 'Amiko' );
				$fonts['Amiri'] = esc_html( 'Amiri' );
				$fonts['Amita'] = esc_html( 'Amita' );
				$fonts['Anaheim'] = esc_html( 'Anaheim' );
				$fonts['Andada'] = esc_html( 'Andada' );
				$fonts['Andika'] = esc_html( 'Andika' );
				$fonts['Angkor'] = esc_html( 'Angkor' );
				$fonts['Annie Use Your Telescope'] = esc_html( 'Annie Use Your Telescope' );
				$fonts['Anonymous Pro'] = esc_html( 'Anonymous Pro' );
				$fonts['Antic'] = esc_html( 'Antic' );
				$fonts['Antic Didone'] = esc_html( 'Antic Didone' );
				$fonts['Antic Slab'] = esc_html( 'Antic Slab' );
				$fonts['Anton'] = esc_html( 'Anton' );
				$fonts['Arapey'] = esc_html( 'Arapey' );
				$fonts['Arbutus'] = esc_html( 'Arbutus' );
				$fonts['Arbutus Slab'] = esc_html( 'Arbutus Slab' );
				$fonts['Architects Daughter'] = esc_html( 'Architects Daughter' );
				$fonts['Archivo'] = esc_html( 'Archivo' );
				$fonts['Archivo Black'] = esc_html( 'Archivo Black' );
				$fonts['Archivo Narrow'] = esc_html( 'Archivo Narrow' );
				$fonts['Aref Ruqaa'] = esc_html( 'Aref Ruqaa' );
				$fonts['Arima Madurai'] = esc_html( 'Arima Madurai' );
				$fonts['Arimo'] = esc_html( 'Arimo' );
				$fonts['Arizonia'] = esc_html( 'Arizonia' );
				$fonts['Armata'] = esc_html( 'Armata' );
				$fonts['Arsenal'] = esc_html( 'Arsenal' );
				$fonts['Artifika'] = esc_html( 'Artifika' );
				$fonts['Arvo'] = esc_html( 'Arvo' );
				$fonts['Arya'] = esc_html( 'Arya' );
				$fonts['Asap'] = esc_html( 'Asap' );
				$fonts['Asap Condensed'] = esc_html( 'Asap Condensed' );
				$fonts['Asar'] = esc_html( 'Asar' );
				$fonts['Asset'] = esc_html( 'Asset' );
				$fonts['Assistant'] = esc_html( 'Assistant' );
				$fonts['Astloch'] = esc_html( 'Astloch' );
				$fonts['Asul'] = esc_html( 'Asul' );
				$fonts['Athiti'] = esc_html( 'Athiti' );
				$fonts['Atma'] = esc_html( 'Atma' );
				$fonts['Atomic Age'] = esc_html( 'Atomic Age' );
				$fonts['Aubrey'] = esc_html( 'Aubrey' );
				$fonts['Audiowide'] = esc_html( 'Audiowide' );
				$fonts['Autour One'] = esc_html( 'Autour One' );
				$fonts['Average'] = esc_html( 'Average' );
				$fonts['Average Sans'] = esc_html( 'Average Sans' );
				$fonts['Averia Gruesa Libre'] = esc_html( 'Averia Gruesa Libre' );
				$fonts['Averia Libre'] = esc_html( 'Averia Libre' );
				$fonts['Averia Sans Libre'] = esc_html( 'Averia Sans Libre' );
				$fonts['Averia Serif Libre'] = esc_html( 'Averia Serif Libre' );
				$fonts['Bad Script'] = esc_html( 'Bad Script' );
				$fonts['Bahiana'] = esc_html( 'Bahiana' );
				$fonts['Baloo'] = esc_html( 'Baloo' );
				$fonts['Baloo Bhai'] = esc_html( 'Baloo Bhai' );
				$fonts['Baloo Bhaijaan'] = esc_html( 'Baloo Bhaijaan' );
				$fonts['Baloo Bhaina'] = esc_html( 'Baloo Bhaina' );
				$fonts['Baloo Chettan'] = esc_html( 'Baloo Chettan' );
				$fonts['Baloo Da'] = esc_html( 'Baloo Da' );
				$fonts['Baloo Paaji'] = esc_html( 'Baloo Paaji' );
				$fonts['Baloo Tamma'] = esc_html( 'Baloo Tamma' );
				$fonts['Baloo Tammudu'] = esc_html( 'Baloo Tammudu' );
				$fonts['Baloo Thambi'] = esc_html( 'Baloo Thambi' );
				$fonts['Balthazar'] = esc_html( 'Balthazar' );
				$fonts['Bangers'] = esc_html( 'Bangers' );
				$fonts['Barrio'] = esc_html( 'Barrio' );
				$fonts['Basic'] = esc_html( 'Basic' );
				$fonts['Battambang'] = esc_html( 'Battambang' );
				$fonts['Baumans'] = esc_html( 'Baumans' );
				$fonts['Bayon'] = esc_html( 'Bayon' );
				$fonts['Belgrano'] = esc_html( 'Belgrano' );
				$fonts['Bellefair'] = esc_html( 'Bellefair' );
				$fonts['Belleza'] = esc_html( 'Belleza' );
				$fonts['BenchNine'] = esc_html( 'BenchNine' );
				$fonts['Bentham'] = esc_html( 'Bentham' );
				$fonts['Berkshire Swash'] = esc_html( 'Berkshire Swash' );
				$fonts['Bevan'] = esc_html( 'Bevan' );
				$fonts['Bigelow Rules'] = esc_html( 'Bigelow Rules' );
				$fonts['Bigshot One'] = esc_html( 'Bigshot One' );
				$fonts['Bilbo'] = esc_html( 'Bilbo' );
				$fonts['Bilbo Swash Caps'] = esc_html( 'Bilbo Swash Caps' );
				$fonts['BioRhyme'] = esc_html( 'BioRhyme' );
				$fonts['BioRhyme Expanded'] = esc_html( 'BioRhyme Expanded' );
				$fonts['Biryani'] = esc_html( 'Biryani' );
				$fonts['Bitter'] = esc_html( 'Bitter' );
				$fonts['Black Ops One'] = esc_html( 'Black Ops One' );
				$fonts['Bokor'] = esc_html( 'Bokor' );
				$fonts['Bonbon'] = esc_html( 'Bonbon' );
				$fonts['Boogaloo'] = esc_html( 'Boogaloo' );
				$fonts['Bowlby One'] = esc_html( 'Bowlby One' );
				$fonts['Bowlby One SC'] = esc_html( 'Bowlby One SC' );
				$fonts['Brawler'] = esc_html( 'Brawler' );
				$fonts['Bree Serif'] = esc_html( 'Bree Serif' );
				$fonts['Bubblegum Sans'] = esc_html( 'Bubblegum Sans' );
				$fonts['Bubbler One'] = esc_html( 'Bubbler One' );
				$fonts['Buda'] = esc_html( 'Buda' );
				$fonts['Buenard'] = esc_html( 'Buenard' );
				$fonts['Bungee'] = esc_html( 'Bungee' );
				$fonts['Bungee Hairline'] = esc_html( 'Bungee Hairline' );
				$fonts['Bungee Inline'] = esc_html( 'Bungee Inline' );
				$fonts['Bungee Outline'] = esc_html( 'Bungee Outline' );
				$fonts['Bungee Shade'] = esc_html( 'Bungee Shade' );
				$fonts['Butcherman'] = esc_html( 'Butcherman' );
				$fonts['Butterfly Kids'] = esc_html( 'Butterfly Kids' );
				$fonts['Cabin'] = esc_html( 'Cabin' );
				$fonts['Cabin Condensed'] = esc_html( 'Cabin Condensed' );
				$fonts['Cabin Sketch'] = esc_html( 'Cabin Sketch' );
				$fonts['Caesar Dressing'] = esc_html( 'Caesar Dressing' );
				$fonts['Cagliostro'] = esc_html( 'Cagliostro' );
				$fonts['Cairo'] = esc_html( 'Cairo' );
				$fonts['Calligraffitti'] = esc_html( 'Calligraffitti' );
				$fonts['Cambay'] = esc_html( 'Cambay' );
				$fonts['Cambo'] = esc_html( 'Cambo' );
				$fonts['Candal'] = esc_html( 'Candal' );
				$fonts['Cantarell'] = esc_html( 'Cantarell' );
				$fonts['Cantata One'] = esc_html( 'Cantata One' );
				$fonts['Cantora One'] = esc_html( 'Cantora One' );
				$fonts['Capriola'] = esc_html( 'Capriola' );
				$fonts['Cardo'] = esc_html( 'Cardo' );
				$fonts['Carme'] = esc_html( 'Carme' );
				$fonts['Carrois Gothic'] = esc_html( 'Carrois Gothic' );
				$fonts['Carrois Gothic SC'] = esc_html( 'Carrois Gothic SC' );
				$fonts['Carter One'] = esc_html( 'Carter One' );
				$fonts['Catamaran'] = esc_html( 'Catamaran' );
				$fonts['Caudex'] = esc_html( 'Caudex' );
				$fonts['Caveat'] = esc_html( 'Caveat' );
				$fonts['Caveat Brush'] = esc_html( 'Caveat Brush' );
				$fonts['Cedarville Cursive'] = esc_html( 'Cedarville Cursive' );
				$fonts['Ceviche One'] = esc_html( 'Ceviche One' );
				$fonts['Changa'] = esc_html( 'Changa' );
				$fonts['Changa One'] = esc_html( 'Changa One' );
				$fonts['Chango'] = esc_html( 'Chango' );
				$fonts['Chathura'] = esc_html( 'Chathura' );
				$fonts['Chau Philomene One'] = esc_html( 'Chau Philomene One' );
				$fonts['Chela One'] = esc_html( 'Chela One' );
				$fonts['Chelsea Market'] = esc_html( 'Chelsea Market' );
				$fonts['Chenla'] = esc_html( 'Chenla' );
				$fonts['Cherry Cream Soda'] = esc_html( 'Cherry Cream Soda' );
				$fonts['Cherry Swash'] = esc_html( 'Cherry Swash' );
				$fonts['Chewy'] = esc_html( 'Chewy' );
				$fonts['Chicle'] = esc_html( 'Chicle' );
				$fonts['Chivo'] = esc_html( 'Chivo' );
				$fonts['Chonburi'] = esc_html( 'Chonburi' );
				$fonts['Cinzel'] = esc_html( 'Cinzel' );
				$fonts['Cinzel Decorative'] = esc_html( 'Cinzel Decorative' );
				$fonts['Clicker Script'] = esc_html( 'Clicker Script' );
				$fonts['Coda'] = esc_html( 'Coda' );
				$fonts['Coda Caption'] = esc_html( 'Coda Caption' );
				$fonts['Codystar'] = esc_html( 'Codystar' );
				$fonts['Coiny'] = esc_html( 'Coiny' );
				$fonts['Combo'] = esc_html( 'Combo' );
				$fonts['Comfortaa'] = esc_html( 'Comfortaa' );
				$fonts['Coming Soon'] = esc_html( 'Coming Soon' );
				$fonts['Concert One'] = esc_html( 'Concert One' );
				$fonts['Condiment'] = esc_html( 'Condiment' );
				$fonts['Content'] = esc_html( 'Content' );
				$fonts['Contrail One'] = esc_html( 'Contrail One' );
				$fonts['Convergence'] = esc_html( 'Convergence' );
				$fonts['Cookie'] = esc_html( 'Cookie' );
				$fonts['Copse'] = esc_html( 'Copse' );
				$fonts['Corben'] = esc_html( 'Corben' );
				$fonts['Cormorant'] = esc_html( 'Cormorant' );
				$fonts['Cormorant Garamond'] = esc_html( 'Cormorant Garamond' );
				$fonts['Cormorant Infant'] = esc_html( 'Cormorant Infant' );
				$fonts['Cormorant SC'] = esc_html( 'Cormorant SC' );
				$fonts['Cormorant Unicase'] = esc_html( 'Cormorant Unicase' );
				$fonts['Cormorant Upright'] = esc_html( 'Cormorant Upright' );
				$fonts['Courgette'] = esc_html( 'Courgette' );
				$fonts['Cousine'] = esc_html( 'Cousine' );
				$fonts['Coustard'] = esc_html( 'Coustard' );
				$fonts['Covered By Your Grace'] = esc_html( 'Covered By Your Grace' );
				$fonts['Crafty Girls'] = esc_html( 'Crafty Girls' );
				$fonts['Creepster'] = esc_html( 'Creepster' );
				$fonts['Crete Round'] = esc_html( 'Crete Round' );
				$fonts['Crimson Text'] = esc_html( 'Crimson Text' );
				$fonts['Croissant One'] = esc_html( 'Croissant One' );
				$fonts['Crushed'] = esc_html( 'Crushed' );
				$fonts['Cuprum'] = esc_html( 'Cuprum' );
				$fonts['Cutive'] = esc_html( 'Cutive' );
				$fonts['Cutive Mono'] = esc_html( 'Cutive Mono' );
				$fonts['Damion'] = esc_html( 'Damion' );
				$fonts['Dancing Script'] = esc_html( 'Dancing Script' );
				$fonts['Dangrek'] = esc_html( 'Dangrek' );
				$fonts['David Libre'] = esc_html( 'David Libre' );
				$fonts['Dawning of a New Day'] = esc_html( 'Dawning of a New Day' );
				$fonts['Days One'] = esc_html( 'Days One' );
				$fonts['Dekko'] = esc_html( 'Dekko' );
				$fonts['Delius'] = esc_html( 'Delius' );
				$fonts['Delius Swash Caps'] = esc_html( 'Delius Swash Caps' );
				$fonts['Delius Unicase'] = esc_html( 'Delius Unicase' );
				$fonts['Della Respira'] = esc_html( 'Della Respira' );
				$fonts['Denk One'] = esc_html( 'Denk One' );
				$fonts['Devonshire'] = esc_html( 'Devonshire' );
				$fonts['Dhurjati'] = esc_html( 'Dhurjati' );
				$fonts['Didact Gothic'] = esc_html( 'Didact Gothic' );
				$fonts['Diplomata'] = esc_html( 'Diplomata' );
				$fonts['Diplomata SC'] = esc_html( 'Diplomata SC' );
				$fonts['Domine'] = esc_html( 'Domine' );
				$fonts['Donegal One'] = esc_html( 'Donegal One' );
				$fonts['Doppio One'] = esc_html( 'Doppio One' );
				$fonts['Dorsa'] = esc_html( 'Dorsa' );
				$fonts['Dosis'] = esc_html( 'Dosis' );
				$fonts['Dr Sugiyama'] = esc_html( 'Dr Sugiyama' );
				$fonts['Droid Sans'] = esc_html( 'Droid Sans' );
				$fonts['Droid Sans Mono'] = esc_html( 'Droid Sans Mono' );
				$fonts['Droid Serif'] = esc_html( 'Droid Serif' );
				$fonts['Duru Sans'] = esc_html( 'Duru Sans' );
				$fonts['Dynalight'] = esc_html( 'Dynalight' );
				$fonts['EB Garamond'] = esc_html( 'EB Garamond' );
				$fonts['Eagle Lake'] = esc_html( 'Eagle Lake' );
				$fonts['Eater'] = esc_html( 'Eater' );
				$fonts['Economica'] = esc_html( 'Economica' );
				$fonts['Eczar'] = esc_html( 'Eczar' );
				$fonts['El Messiri'] = esc_html( 'El Messiri' );
				$fonts['Electrolize'] = esc_html( 'Electrolize' );
				$fonts['Elsie'] = esc_html( 'Elsie' );
				$fonts['Elsie Swash Caps'] = esc_html( 'Elsie Swash Caps' );
				$fonts['Emblema One'] = esc_html( 'Emblema One' );
				$fonts['Emilys Candy'] = esc_html( 'Emilys Candy' );
				$fonts['Encode Sans'] = esc_html( 'Encode Sans' );
				$fonts['Encode Sans Condensed'] = esc_html( 'Encode Sans Condensed' );
				$fonts['Encode Sans Expanded'] = esc_html( 'Encode Sans Expanded' );
				$fonts['Encode Sans Semi Condensed'] = esc_html( 'Encode Sans Semi Condensed' );
				$fonts['Encode Sans Semi Expanded'] = esc_html( 'Encode Sans Semi Expanded' );
				$fonts['Engagement'] = esc_html( 'Engagement' );
				$fonts['Englebert'] = esc_html( 'Englebert' );
				$fonts['Enriqueta'] = esc_html( 'Enriqueta' );
				$fonts['Erica One'] = esc_html( 'Erica One' );
				$fonts['Esteban'] = esc_html( 'Esteban' );
				$fonts['Euphoria Script'] = esc_html( 'Euphoria Script' );
				$fonts['Ewert'] = esc_html( 'Ewert' );
				$fonts['Exo'] = esc_html( 'Exo' );
				$fonts['Exo 2'] = esc_html( 'Exo 2' );
				$fonts['Expletus Sans'] = esc_html( 'Expletus Sans' );
				$fonts['Fanwood Text'] = esc_html( 'Fanwood Text' );
				$fonts['Farsan'] = esc_html( 'Farsan' );
				$fonts['Fascinate'] = esc_html( 'Fascinate' );
				$fonts['Fascinate Inline'] = esc_html( 'Fascinate Inline' );
				$fonts['Faster One'] = esc_html( 'Faster One' );
				$fonts['Fasthand'] = esc_html( 'Fasthand' );
				$fonts['Fauna One'] = esc_html( 'Fauna One' );
				$fonts['Faustina'] = esc_html( 'Faustina' );
				$fonts['Federant'] = esc_html( 'Federant' );
				$fonts['Federo'] = esc_html( 'Federo' );
				$fonts['Felipa'] = esc_html( 'Felipa' );
				$fonts['Fenix'] = esc_html( 'Fenix' );
				$fonts['Finger Paint'] = esc_html( 'Finger Paint' );
				$fonts['Fira Mono'] = esc_html( 'Fira Mono' );
				$fonts['Fira Sans'] = esc_html( 'Fira Sans' );
				$fonts['Fira Sans Condensed'] = esc_html( 'Fira Sans Condensed' );
				$fonts['Fira Sans Extra Condensed'] = esc_html( 'Fira Sans Extra Condensed' );
				$fonts['Fjalla One'] = esc_html( 'Fjalla One' );
				$fonts['Fjord One'] = esc_html( 'Fjord One' );
				$fonts['Flamenco'] = esc_html( 'Flamenco' );
				$fonts['Flavors'] = esc_html( 'Flavors' );
				$fonts['Fondamento'] = esc_html( 'Fondamento' );
				$fonts['Fontdiner Swanky'] = esc_html( 'Fontdiner Swanky' );
				$fonts['Forum'] = esc_html( 'Forum' );
				$fonts['Francois One'] = esc_html( 'Francois One' );
				$fonts['Frank Ruhl Libre'] = esc_html( 'Frank Ruhl Libre' );
				$fonts['Freckle Face'] = esc_html( 'Freckle Face' );
				$fonts['Fredericka the Great'] = esc_html( 'Fredericka the Great' );
				$fonts['Fredoka One'] = esc_html( 'Fredoka One' );
				$fonts['Freehand'] = esc_html( 'Freehand' );
				$fonts['Fresca'] = esc_html( 'Fresca' );
				$fonts['Frijole'] = esc_html( 'Frijole' );
				$fonts['Fruktur'] = esc_html( 'Fruktur' );
				$fonts['Fugaz One'] = esc_html( 'Fugaz One' );
				$fonts['GFS Didot'] = esc_html( 'GFS Didot' );
				$fonts['GFS Neohellenic'] = esc_html( 'GFS Neohellenic' );
				$fonts['Gabriela'] = esc_html( 'Gabriela' );
				$fonts['Gafata'] = esc_html( 'Gafata' );
				$fonts['Galada'] = esc_html( 'Galada' );
				$fonts['Galdeano'] = esc_html( 'Galdeano' );
				$fonts['Galindo'] = esc_html( 'Galindo' );
				$fonts['Gentium Basic'] = esc_html( 'Gentium Basic' );
				$fonts['Gentium Book Basic'] = esc_html( 'Gentium Book Basic' );
				$fonts['Geo'] = esc_html( 'Geo' );
				$fonts['Geostar'] = esc_html( 'Geostar' );
				$fonts['Geostar Fill'] = esc_html( 'Geostar Fill' );
				$fonts['Germania One'] = esc_html( 'Germania One' );
				$fonts['Gidugu'] = esc_html( 'Gidugu' );
				$fonts['Gilda Display'] = esc_html( 'Gilda Display' );
				$fonts['Give You Glory'] = esc_html( 'Give You Glory' );
				$fonts['Glass Antiqua'] = esc_html( 'Glass Antiqua' );
				$fonts['Glegoo'] = esc_html( 'Glegoo' );
				$fonts['Gloria Hallelujah'] = esc_html( 'Gloria Hallelujah' );
				$fonts['Goblin One'] = esc_html( 'Goblin One' );
				$fonts['Gochi Hand'] = esc_html( 'Gochi Hand' );
				$fonts['Gorditas'] = esc_html( 'Gorditas' );
				$fonts['Goudy Bookletter 1911'] = esc_html( 'Goudy Bookletter 1911' );
				$fonts['Graduate'] = esc_html( 'Graduate' );
				$fonts['Grand Hotel'] = esc_html( 'Grand Hotel' );
				$fonts['Gravitas One'] = esc_html( 'Gravitas One' );
				$fonts['Great Vibes'] = esc_html( 'Great Vibes' );
				$fonts['Griffy'] = esc_html( 'Griffy' );
				$fonts['Gruppo'] = esc_html( 'Gruppo' );
				$fonts['Gudea'] = esc_html( 'Gudea' );
				$fonts['Gurajada'] = esc_html( 'Gurajada' );
				$fonts['Habibi'] = esc_html( 'Habibi' );
				$fonts['Halant'] = esc_html( 'Halant' );
				$fonts['Hammersmith One'] = esc_html( 'Hammersmith One' );
				$fonts['Hanalei'] = esc_html( 'Hanalei' );
				$fonts['Hanalei Fill'] = esc_html( 'Hanalei Fill' );
				$fonts['Handlee'] = esc_html( 'Handlee' );
				$fonts['Hanuman'] = esc_html( 'Hanuman' );
				$fonts['Happy Monkey'] = esc_html( 'Happy Monkey' );
				$fonts['Harmattan'] = esc_html( 'Harmattan' );
				$fonts['Headland One'] = esc_html( 'Headland One' );
				$fonts['Heebo'] = esc_html( 'Heebo' );
				$fonts['Henny Penny'] = esc_html( 'Henny Penny' );
				$fonts['Herr Von Muellerhoff'] = esc_html( 'Herr Von Muellerhoff' );
				$fonts['Hind'] = esc_html( 'Hind' );
				$fonts['Hind Guntur'] = esc_html( 'Hind Guntur' );
				$fonts['Hind Madurai'] = esc_html( 'Hind Madurai' );
				$fonts['Hind Siliguri'] = esc_html( 'Hind Siliguri' );
				$fonts['Hind Vadodara'] = esc_html( 'Hind Vadodara' );
				$fonts['Holtwood One SC'] = esc_html( 'Holtwood One SC' );
				$fonts['Homemade Apple'] = esc_html( 'Homemade Apple' );
				$fonts['Homenaje'] = esc_html( 'Homenaje' );
				$fonts['IM Fell DW Pica'] = esc_html( 'IM Fell DW Pica' );
				$fonts['IM Fell DW Pica SC'] = esc_html( 'IM Fell DW Pica SC' );
				$fonts['IM Fell Double Pica'] = esc_html( 'IM Fell Double Pica' );
				$fonts['IM Fell Double Pica SC'] = esc_html( 'IM Fell Double Pica SC' );
				$fonts['IM Fell English'] = esc_html( 'IM Fell English' );
				$fonts['IM Fell English SC'] = esc_html( 'IM Fell English SC' );
				$fonts['IM Fell French Canon'] = esc_html( 'IM Fell French Canon' );
				$fonts['IM Fell French Canon SC'] = esc_html( 'IM Fell French Canon SC' );
				$fonts['IM Fell Great Primer'] = esc_html( 'IM Fell Great Primer' );
				$fonts['IM Fell Great Primer SC'] = esc_html( 'IM Fell Great Primer SC' );
				$fonts['Iceberg'] = esc_html( 'Iceberg' );
				$fonts['Iceland'] = esc_html( 'Iceland' );
				$fonts['Imprima'] = esc_html( 'Imprima' );
				$fonts['Inconsolata'] = esc_html( 'Inconsolata' );
				$fonts['Inder'] = esc_html( 'Inder' );
				$fonts['Indie Flower'] = esc_html( 'Indie Flower' );
				$fonts['Inika'] = esc_html( 'Inika' );
				$fonts['Inknut Antiqua'] = esc_html( 'Inknut Antiqua' );
				$fonts['Irish Grover'] = esc_html( 'Irish Grover' );
				$fonts['Istok Web'] = esc_html( 'Istok Web' );
				$fonts['Italiana'] = esc_html( 'Italiana' );
				$fonts['Italianno'] = esc_html( 'Italianno' );
				$fonts['Itim'] = esc_html( 'Itim' );
				$fonts['Jacques Francois'] = esc_html( 'Jacques Francois' );
				$fonts['Jacques Francois Shadow'] = esc_html( 'Jacques Francois Shadow' );
				$fonts['Jaldi'] = esc_html( 'Jaldi' );
				$fonts['Jim Nightshade'] = esc_html( 'Jim Nightshade' );
				$fonts['Jockey One'] = esc_html( 'Jockey One' );
				$fonts['Jolly Lodger'] = esc_html( 'Jolly Lodger' );
				$fonts['Jomhuria'] = esc_html( 'Jomhuria' );
				$fonts['Josefin Sans'] = esc_html( 'Josefin Sans' );
				$fonts['Josefin Slab'] = esc_html( 'Josefin Slab' );
				$fonts['Joti One'] = esc_html( 'Joti One' );
				$fonts['Judson'] = esc_html( 'Judson' );
				$fonts['Julee'] = esc_html( 'Julee' );
				$fonts['Julius Sans One'] = esc_html( 'Julius Sans One' );
				$fonts['Junge'] = esc_html( 'Junge' );
				$fonts['Jura'] = esc_html( 'Jura' );
				$fonts['Just Another Hand'] = esc_html( 'Just Another Hand' );
				$fonts['Just Me Again Down Here'] = esc_html( 'Just Me Again Down Here' );
				$fonts['Kadwa'] = esc_html( 'Kadwa' );
				$fonts['Kalam'] = esc_html( 'Kalam' );
				$fonts['Kameron'] = esc_html( 'Kameron' );
				$fonts['Kanit'] = esc_html( 'Kanit' );
				$fonts['Kantumruy'] = esc_html( 'Kantumruy' );
				$fonts['Karla'] = esc_html( 'Karla' );
				$fonts['Karma'] = esc_html( 'Karma' );
				$fonts['Katibeh'] = esc_html( 'Katibeh' );
				$fonts['Kaushan Script'] = esc_html( 'Kaushan Script' );
				$fonts['Kavivanar'] = esc_html( 'Kavivanar' );
				$fonts['Kavoon'] = esc_html( 'Kavoon' );
				$fonts['Kdam Thmor'] = esc_html( 'Kdam Thmor' );
				$fonts['Keania One'] = esc_html( 'Keania One' );
				$fonts['Kelly Slab'] = esc_html( 'Kelly Slab' );
				$fonts['Kenia'] = esc_html( 'Kenia' );
				$fonts['Khand'] = esc_html( 'Khand' );
				$fonts['Khmer'] = esc_html( 'Khmer' );
				$fonts['Khula'] = esc_html( 'Khula' );
				$fonts['Kite One'] = esc_html( 'Kite One' );
				$fonts['Knewave'] = esc_html( 'Knewave' );
				$fonts['Kotta One'] = esc_html( 'Kotta One' );
				$fonts['Koulen'] = esc_html( 'Koulen' );
				$fonts['Kranky'] = esc_html( 'Kranky' );
				$fonts['Kreon'] = esc_html( 'Kreon' );
				$fonts['Kristi'] = esc_html( 'Kristi' );
				$fonts['Krona One'] = esc_html( 'Krona One' );
				$fonts['Kumar One'] = esc_html( 'Kumar One' );
				$fonts['Kumar One Outline'] = esc_html( 'Kumar One Outline' );
				$fonts['Kurale'] = esc_html( 'Kurale' );
				$fonts['La Belle Aurore'] = esc_html( 'La Belle Aurore' );
				$fonts['Laila'] = esc_html( 'Laila' );
				$fonts['Lakki Reddy'] = esc_html( 'Lakki Reddy' );
				$fonts['Lalezar'] = esc_html( 'Lalezar' );
				$fonts['Lancelot'] = esc_html( 'Lancelot' );
				$fonts['Lateef'] = esc_html( 'Lateef' );
				$fonts['Lato'] = esc_html( 'Lato' );
				$fonts['League Script'] = esc_html( 'League Script' );
				$fonts['Leckerli One'] = esc_html( 'Leckerli One' );
				$fonts['Ledger'] = esc_html( 'Ledger' );
				$fonts['Lekton'] = esc_html( 'Lekton' );
				$fonts['Lemon'] = esc_html( 'Lemon' );
				$fonts['Lemonada'] = esc_html( 'Lemonada' );
				$fonts['Libre Barcode 128'] = esc_html( 'Libre Barcode 128' );
				$fonts['Libre Barcode 128 Text'] = esc_html( 'Libre Barcode 128 Text' );
				$fonts['Libre Barcode 39'] = esc_html( 'Libre Barcode 39' );
				$fonts['Libre Barcode 39 Extended'] = esc_html( 'Libre Barcode 39 Extended' );
				$fonts['Libre Barcode 39 Extended Text'] = esc_html( 'Libre Barcode 39 Extended Text' );
				$fonts['Libre Barcode 39 Text'] = esc_html( 'Libre Barcode 39 Text' );
				$fonts['Libre Baskerville'] = esc_html( 'Libre Baskerville' );
				$fonts['Libre Franklin'] = esc_html( 'Libre Franklin' );
				$fonts['Life Savers'] = esc_html( 'Life Savers' );
				$fonts['Lilita One'] = esc_html( 'Lilita One' );
				$fonts['Lily Script One'] = esc_html( 'Lily Script One' );
				$fonts['Limelight'] = esc_html( 'Limelight' );
				$fonts['Linden Hill'] = esc_html( 'Linden Hill' );
				$fonts['Lobster'] = esc_html( 'Lobster' );
				$fonts['Lobster Two'] = esc_html( 'Lobster Two' );
				$fonts['Londrina Outline'] = esc_html( 'Londrina Outline' );
				$fonts['Londrina Shadow'] = esc_html( 'Londrina Shadow' );
				$fonts['Londrina Sketch'] = esc_html( 'Londrina Sketch' );
				$fonts['Londrina Solid'] = esc_html( 'Londrina Solid' );
				$fonts['Lora'] = esc_html( 'Lora' );
				$fonts['Love Ya Like A Sister'] = esc_html( 'Love Ya Like A Sister' );
				$fonts['Loved by the King'] = esc_html( 'Loved by the King' );
				$fonts['Lovers Quarrel'] = esc_html( 'Lovers Quarrel' );
				$fonts['Luckiest Guy'] = esc_html( 'Luckiest Guy' );
				$fonts['Lusitana'] = esc_html( 'Lusitana' );
				$fonts['Lustria'] = esc_html( 'Lustria' );
				$fonts['Macondo'] = esc_html( 'Macondo' );
				$fonts['Macondo Swash Caps'] = esc_html( 'Macondo Swash Caps' );
				$fonts['Mada'] = esc_html( 'Mada' );
				$fonts['Magra'] = esc_html( 'Magra' );
				$fonts['Maiden Orange'] = esc_html( 'Maiden Orange' );
				$fonts['Maitree'] = esc_html( 'Maitree' );
				$fonts['Mako'] = esc_html( 'Mako' );
				$fonts['Mallanna'] = esc_html( 'Mallanna' );
				$fonts['Mandali'] = esc_html( 'Mandali' );
				$fonts['Manuale'] = esc_html( 'Manuale' );
				$fonts['Marcellus'] = esc_html( 'Marcellus' );
				$fonts['Marcellus SC'] = esc_html( 'Marcellus SC' );
				$fonts['Marck Script'] = esc_html( 'Marck Script' );
				$fonts['Margarine'] = esc_html( 'Margarine' );
				$fonts['Marko One'] = esc_html( 'Marko One' );
				$fonts['Marmelad'] = esc_html( 'Marmelad' );
				$fonts['Martel'] = esc_html( 'Martel' );
				$fonts['Martel Sans'] = esc_html( 'Martel Sans' );
				$fonts['Marvel'] = esc_html( 'Marvel' );
				$fonts['Mate'] = esc_html( 'Mate' );
				$fonts['Mate SC'] = esc_html( 'Mate SC' );
				$fonts['Maven Pro'] = esc_html( 'Maven Pro' );
				$fonts['McLaren'] = esc_html( 'McLaren' );
				$fonts['Meddon'] = esc_html( 'Meddon' );
				$fonts['MedievalSharp'] = esc_html( 'MedievalSharp' );
				$fonts['Medula One'] = esc_html( 'Medula One' );
				$fonts['Meera Inimai'] = esc_html( 'Meera Inimai' );
				$fonts['Megrim'] = esc_html( 'Megrim' );
				$fonts['Meie Script'] = esc_html( 'Meie Script' );
				$fonts['Merienda'] = esc_html( 'Merienda' );
				$fonts['Merienda One'] = esc_html( 'Merienda One' );
				$fonts['Merriweather'] = esc_html( 'Merriweather' );
				$fonts['Merriweather Sans'] = esc_html( 'Merriweather Sans' );
				$fonts['Metal'] = esc_html( 'Metal' );
				$fonts['Metal Mania'] = esc_html( 'Metal Mania' );
				$fonts['Metamorphous'] = esc_html( 'Metamorphous' );
				$fonts['Metrophobic'] = esc_html( 'Metrophobic' );
				$fonts['Michroma'] = esc_html( 'Michroma' );
				$fonts['Milonga'] = esc_html( 'Milonga' );
				$fonts['Miltonian'] = esc_html( 'Miltonian' );
				$fonts['Miltonian Tattoo'] = esc_html( 'Miltonian Tattoo' );
				$fonts['Miniver'] = esc_html( 'Miniver' );
				$fonts['Miriam Libre'] = esc_html( 'Miriam Libre' );
				$fonts['Mirza'] = esc_html( 'Mirza' );
				$fonts['Miss Fajardose'] = esc_html( 'Miss Fajardose' );
				$fonts['Mitr'] = esc_html( 'Mitr' );
				$fonts['Modak'] = esc_html( 'Modak' );
				$fonts['Modern Antiqua'] = esc_html( 'Modern Antiqua' );
				$fonts['Mogra'] = esc_html( 'Mogra' );
				$fonts['Molengo'] = esc_html( 'Molengo' );
				$fonts['Molle'] = esc_html( 'Molle' );
				$fonts['Monda'] = esc_html( 'Monda' );
				$fonts['Monofett'] = esc_html( 'Monofett' );
				$fonts['Monoton'] = esc_html( 'Monoton' );
				$fonts['Monsieur La Doulaise'] = esc_html( 'Monsieur La Doulaise' );
				$fonts['Montaga'] = esc_html( 'Montaga' );
				$fonts['Montez'] = esc_html( 'Montez' );
				$fonts['Montserrat'] = esc_html( 'Montserrat' );
				$fonts['Montserrat Alternates'] = esc_html( 'Montserrat Alternates' );
				$fonts['Montserrat Subrayada'] = esc_html( 'Montserrat Subrayada' );
				$fonts['Moul'] = esc_html( 'Moul' );
				$fonts['Moulpali'] = esc_html( 'Moulpali' );
				$fonts['Mountains of Christmas'] = esc_html( 'Mountains of Christmas' );
				$fonts['Mouse Memoirs'] = esc_html( 'Mouse Memoirs' );
				$fonts['Mr Bedfort'] = esc_html( 'Mr Bedfort' );
				$fonts['Mr Dafoe'] = esc_html( 'Mr Dafoe' );
				$fonts['Mr De Haviland'] = esc_html( 'Mr De Haviland' );
				$fonts['Mrs Saint Delafield'] = esc_html( 'Mrs Saint Delafield' );
				$fonts['Mrs Sheppards'] = esc_html( 'Mrs Sheppards' );
				$fonts['Mukta'] = esc_html( 'Mukta' );
				$fonts['Mukta Mahee'] = esc_html( 'Mukta Mahee' );
				$fonts['Mukta Malar'] = esc_html( 'Mukta Malar' );
				$fonts['Mukta Vaani'] = esc_html( 'Mukta Vaani' );
				$fonts['Muli'] = esc_html( 'Muli' );
				$fonts['Mystery Quest'] = esc_html( 'Mystery Quest' );
				$fonts['NTR'] = esc_html( 'NTR' );
				$fonts['Neucha'] = esc_html( 'Neucha' );
				$fonts['Neuton'] = esc_html( 'Neuton' );
				$fonts['New Rocker'] = esc_html( 'New Rocker' );
				$fonts['News Cycle'] = esc_html( 'News Cycle' );
				$fonts['Niconne'] = esc_html( 'Niconne' );
				$fonts['Nixie One'] = esc_html( 'Nixie One' );
				$fonts['Nobile'] = esc_html( 'Nobile' );
				$fonts['Nokora'] = esc_html( 'Nokora' );
				$fonts['Norican'] = esc_html( 'Norican' );
				$fonts['Nosifer'] = esc_html( 'Nosifer' );
				$fonts['Nothing You Could Do'] = esc_html( 'Nothing You Could Do' );
				$fonts['Noticia Text'] = esc_html( 'Noticia Text' );
				$fonts['Noto Sans'] = esc_html( 'Noto Sans' );
				$fonts['Noto Serif'] = esc_html( 'Noto Serif' );
				$fonts['Nova Cut'] = esc_html( 'Nova Cut' );
				$fonts['Nova Flat'] = esc_html( 'Nova Flat' );
				$fonts['Nova Mono'] = esc_html( 'Nova Mono' );
				$fonts['Nova Oval'] = esc_html( 'Nova Oval' );
				$fonts['Nova Round'] = esc_html( 'Nova Round' );
				$fonts['Nova Script'] = esc_html( 'Nova Script' );
				$fonts['Nova Slim'] = esc_html( 'Nova Slim' );
				$fonts['Nova Square'] = esc_html( 'Nova Square' );
				$fonts['Numans'] = esc_html( 'Numans' );
				$fonts['Nunito'] = esc_html( 'Nunito' );
				$fonts['Nunito Sans'] = esc_html( 'Nunito Sans' );
				$fonts['Odor Mean Chey'] = esc_html( 'Odor Mean Chey' );
				$fonts['Offside'] = esc_html( 'Offside' );
				$fonts['Old Standard TT'] = esc_html( 'Old Standard TT' );
				$fonts['Oldenburg'] = esc_html( 'Oldenburg' );
				$fonts['Oleo Script'] = esc_html( 'Oleo Script' );
				$fonts['Oleo Script Swash Caps'] = esc_html( 'Oleo Script Swash Caps' );
				$fonts['Open Sans'] = esc_html( 'Open Sans' );
				$fonts['Open Sans Condensed'] = esc_html( 'Open Sans Condensed' );
				$fonts['Oranienbaum'] = esc_html( 'Oranienbaum' );
				$fonts['Orbitron'] = esc_html( 'Orbitron' );
				$fonts['Oregano'] = esc_html( 'Oregano' );
				$fonts['Orienta'] = esc_html( 'Orienta' );
				$fonts['Original Surfer'] = esc_html( 'Original Surfer' );
				$fonts['Oswald'] = esc_html( 'Oswald' );
				$fonts['Over the Rainbow'] = esc_html( 'Over the Rainbow' );
				$fonts['Overlock'] = esc_html( 'Overlock' );
				$fonts['Overlock SC'] = esc_html( 'Overlock SC' );
				$fonts['Overpass'] = esc_html( 'Overpass' );
				$fonts['Overpass Mono'] = esc_html( 'Overpass Mono' );
				$fonts['Ovo'] = esc_html( 'Ovo' );
				$fonts['Oxygen'] = esc_html( 'Oxygen' );
				$fonts['Oxygen Mono'] = esc_html( 'Oxygen Mono' );
				$fonts['PT Mono'] = esc_html( 'PT Mono' );
				$fonts['PT Sans'] = esc_html( 'PT Sans' );
				$fonts['PT Sans Caption'] = esc_html( 'PT Sans Caption' );
				$fonts['PT Sans Narrow'] = esc_html( 'PT Sans Narrow' );
				$fonts['PT Serif'] = esc_html( 'PT Serif' );
				$fonts['PT Serif Caption'] = esc_html( 'PT Serif Caption' );
				$fonts['Pacifico'] = esc_html( 'Pacifico' );
				$fonts['Padauk'] = esc_html( 'Padauk' );
				$fonts['Palanquin'] = esc_html( 'Palanquin' );
				$fonts['Palanquin Dark'] = esc_html( 'Palanquin Dark' );
				$fonts['Pangolin'] = esc_html( 'Pangolin' );
				$fonts['Paprika'] = esc_html( 'Paprika' );
				$fonts['Parisienne'] = esc_html( 'Parisienne' );
				$fonts['Passero One'] = esc_html( 'Passero One' );
				$fonts['Passion One'] = esc_html( 'Passion One' );
				$fonts['Pathway Gothic One'] = esc_html( 'Pathway Gothic One' );
				$fonts['Patrick Hand'] = esc_html( 'Patrick Hand' );
				$fonts['Patrick Hand SC'] = esc_html( 'Patrick Hand SC' );
				$fonts['Pattaya'] = esc_html( 'Pattaya' );
				$fonts['Patua One'] = esc_html( 'Patua One' );
				$fonts['Pavanam'] = esc_html( 'Pavanam' );
				$fonts['Paytone One'] = esc_html( 'Paytone One' );
				$fonts['Peddana'] = esc_html( 'Peddana' );
				$fonts['Peralta'] = esc_html( 'Peralta' );
				$fonts['Permanent Marker'] = esc_html( 'Permanent Marker' );
				$fonts['Petit Formal Script'] = esc_html( 'Petit Formal Script' );
				$fonts['Petrona'] = esc_html( 'Petrona' );
				$fonts['Philosopher'] = esc_html( 'Philosopher' );
				$fonts['Piedra'] = esc_html( 'Piedra' );
				$fonts['Pinyon Script'] = esc_html( 'Pinyon Script' );
				$fonts['Pirata One'] = esc_html( 'Pirata One' );
				$fonts['Plaster'] = esc_html( 'Plaster' );
				$fonts['Play'] = esc_html( 'Play' );
				$fonts['Playball'] = esc_html( 'Playball' );
				$fonts['Playfair Display'] = esc_html( 'Playfair Display' );
				$fonts['Playfair Display SC'] = esc_html( 'Playfair Display SC' );
				$fonts['Podkova'] = esc_html( 'Podkova' );
				$fonts['Poiret One'] = esc_html( 'Poiret One' );
				$fonts['Poller One'] = esc_html( 'Poller One' );
				$fonts['Poly'] = esc_html( 'Poly' );
				$fonts['Pompiere'] = esc_html( 'Pompiere' );
				$fonts['Pontano Sans'] = esc_html( 'Pontano Sans' );
				$fonts['Poppins'] = esc_html( 'Poppins' );
				$fonts['Port Lligat Sans'] = esc_html( 'Port Lligat Sans' );
				$fonts['Port Lligat Slab'] = esc_html( 'Port Lligat Slab' );
				$fonts['Pragati Narrow'] = esc_html( 'Pragati Narrow' );
				$fonts['Prata'] = esc_html( 'Prata' );
				$fonts['Preahvihear'] = esc_html( 'Preahvihear' );
				$fonts['Press Start 2P'] = esc_html( 'Press Start 2P' );
				$fonts['Pridi'] = esc_html( 'Pridi' );
				$fonts['Princess Sofia'] = esc_html( 'Princess Sofia' );
				$fonts['Prociono'] = esc_html( 'Prociono' );
				$fonts['Prompt'] = esc_html( 'Prompt' );
				$fonts['Prosto One'] = esc_html( 'Prosto One' );
				$fonts['Proza Libre'] = esc_html( 'Proza Libre' );
				$fonts['Puritan'] = esc_html( 'Puritan' );
				$fonts['Purple Purse'] = esc_html( 'Purple Purse' );
				$fonts['Quando'] = esc_html( 'Quando' );
				$fonts['Quantico'] = esc_html( 'Quantico' );
				$fonts['Quattrocento'] = esc_html( 'Quattrocento' );
				$fonts['Quattrocento Sans'] = esc_html( 'Quattrocento Sans' );
				$fonts['Questrial'] = esc_html( 'Questrial' );
				$fonts['Quicksand'] = esc_html( 'Quicksand' );
				$fonts['Quintessential'] = esc_html( 'Quintessential' );
				$fonts['Qwigley'] = esc_html( 'Qwigley' );
				$fonts['Racing Sans One'] = esc_html( 'Racing Sans One' );
				$fonts['Radley'] = esc_html( 'Radley' );
				$fonts['Rajdhani'] = esc_html( 'Rajdhani' );
				$fonts['Rakkas'] = esc_html( 'Rakkas' );
				$fonts['Raleway'] = esc_html( 'Raleway' );
				$fonts['Raleway Dots'] = esc_html( 'Raleway Dots' );
				$fonts['Ramabhadra'] = esc_html( 'Ramabhadra' );
				$fonts['Ramaraja'] = esc_html( 'Ramaraja' );
				$fonts['Rambla'] = esc_html( 'Rambla' );
				$fonts['Rammetto One'] = esc_html( 'Rammetto One' );
				$fonts['Ranchers'] = esc_html( 'Ranchers' );
				$fonts['Rancho'] = esc_html( 'Rancho' );
				$fonts['Ranga'] = esc_html( 'Ranga' );
				$fonts['Rasa'] = esc_html( 'Rasa' );
				$fonts['Rationale'] = esc_html( 'Rationale' );
				$fonts['Ravi Prakash'] = esc_html( 'Ravi Prakash' );
				$fonts['Redressed'] = esc_html( 'Redressed' );
				$fonts['Reem Kufi'] = esc_html( 'Reem Kufi' );
				$fonts['Reenie Beanie'] = esc_html( 'Reenie Beanie' );
				$fonts['Revalia'] = esc_html( 'Revalia' );
				$fonts['Rhodium Libre'] = esc_html( 'Rhodium Libre' );
				$fonts['Ribeye'] = esc_html( 'Ribeye' );
				$fonts['Ribeye Marrow'] = esc_html( 'Ribeye Marrow' );
				$fonts['Righteous'] = esc_html( 'Righteous' );
				$fonts['Risque'] = esc_html( 'Risque' );
				$fonts['Roboto'] = esc_html( 'Roboto' );
				$fonts['Roboto Condensed'] = esc_html( 'Roboto Condensed' );
				$fonts['Roboto Mono'] = esc_html( 'Roboto Mono' );
				$fonts['Roboto Slab'] = esc_html( 'Roboto Slab' );
				$fonts['Rochester'] = esc_html( 'Rochester' );
				$fonts['Rock Salt'] = esc_html( 'Rock Salt' );
				$fonts['Rokkitt'] = esc_html( 'Rokkitt' );
				$fonts['Romanesco'] = esc_html( 'Romanesco' );
				$fonts['Ropa Sans'] = esc_html( 'Ropa Sans' );
				$fonts['Rosario'] = esc_html( 'Rosario' );
				$fonts['Rosarivo'] = esc_html( 'Rosarivo' );
				$fonts['Rouge Script'] = esc_html( 'Rouge Script' );
				$fonts['Rozha One'] = esc_html( 'Rozha One' );
				$fonts['Rubik'] = esc_html( 'Rubik' );
				$fonts['Rubik Mono One'] = esc_html( 'Rubik Mono One' );
				$fonts['Ruda'] = esc_html( 'Ruda' );
				$fonts['Rufina'] = esc_html( 'Rufina' );
				$fonts['Ruge Boogie'] = esc_html( 'Ruge Boogie' );
				$fonts['Ruluko'] = esc_html( 'Ruluko' );
				$fonts['Rum Raisin'] = esc_html( 'Rum Raisin' );
				$fonts['Ruslan Display'] = esc_html( 'Ruslan Display' );
				$fonts['Russo One'] = esc_html( 'Russo One' );
				$fonts['Ruthie'] = esc_html( 'Ruthie' );
				$fonts['Rye'] = esc_html( 'Rye' );
				$fonts['Sacramento'] = esc_html( 'Sacramento' );
				$fonts['Sahitya'] = esc_html( 'Sahitya' );
				$fonts['Sail'] = esc_html( 'Sail' );
				$fonts['Saira'] = esc_html( 'Saira' );
				$fonts['Saira Condensed'] = esc_html( 'Saira Condensed' );
				$fonts['Saira Extra Condensed'] = esc_html( 'Saira Extra Condensed' );
				$fonts['Saira Semi Condensed'] = esc_html( 'Saira Semi Condensed' );
				$fonts['Salsa'] = esc_html( 'Salsa' );
				$fonts['Sanchez'] = esc_html( 'Sanchez' );
				$fonts['Sancreek'] = esc_html( 'Sancreek' );
				$fonts['Sansita'] = esc_html( 'Sansita' );
				$fonts['Sarala'] = esc_html( 'Sarala' );
				$fonts['Sarina'] = esc_html( 'Sarina' );
				$fonts['Sarpanch'] = esc_html( 'Sarpanch' );
				$fonts['Satisfy'] = esc_html( 'Satisfy' );
				$fonts['Scada'] = esc_html( 'Scada' );
				$fonts['Scheherazade'] = esc_html( 'Scheherazade' );
				$fonts['Schoolbell'] = esc_html( 'Schoolbell' );
				$fonts['Scope One'] = esc_html( 'Scope One' );
				$fonts['Seaweed Script'] = esc_html( 'Seaweed Script' );
				$fonts['Secular One'] = esc_html( 'Secular One' );
				$fonts['Sedgwick Ave'] = esc_html( 'Sedgwick Ave' );
				$fonts['Sedgwick Ave Display'] = esc_html( 'Sedgwick Ave Display' );
				$fonts['Sevillana'] = esc_html( 'Sevillana' );
				$fonts['Seymour One'] = esc_html( 'Seymour One' );
				$fonts['Shadows Into Light'] = esc_html( 'Shadows Into Light' );
				$fonts['Shadows Into Light Two'] = esc_html( 'Shadows Into Light Two' );
				$fonts['Shanti'] = esc_html( 'Shanti' );
				$fonts['Share'] = esc_html( 'Share' );
				$fonts['Share Tech'] = esc_html( 'Share Tech' );
				$fonts['Share Tech Mono'] = esc_html( 'Share Tech Mono' );
				$fonts['Shojumaru'] = esc_html( 'Shojumaru' );
				$fonts['Short Stack'] = esc_html( 'Short Stack' );
				$fonts['Shrikhand'] = esc_html( 'Shrikhand' );
				$fonts['Siemreap'] = esc_html( 'Siemreap' );
				$fonts['Sigmar One'] = esc_html( 'Sigmar One' );
				$fonts['Signika'] = esc_html( 'Signika' );
				$fonts['Signika Negative'] = esc_html( 'Signika Negative' );
				$fonts['Simonetta'] = esc_html( 'Simonetta' );
				$fonts['Sintony'] = esc_html( 'Sintony' );
				$fonts['Sirin Stencil'] = esc_html( 'Sirin Stencil' );
				$fonts['Six Caps'] = esc_html( 'Six Caps' );
				$fonts['Skranji'] = esc_html( 'Skranji' );
				$fonts['Slabo 13px'] = esc_html( 'Slabo 13px' );
				$fonts['Slabo 27px'] = esc_html( 'Slabo 27px' );
				$fonts['Slackey'] = esc_html( 'Slackey' );
				$fonts['Smokum'] = esc_html( 'Smokum' );
				$fonts['Smythe'] = esc_html( 'Smythe' );
				$fonts['Sniglet'] = esc_html( 'Sniglet' );
				$fonts['Snippet'] = esc_html( 'Snippet' );
				$fonts['Snowburst One'] = esc_html( 'Snowburst One' );
				$fonts['Sofadi One'] = esc_html( 'Sofadi One' );
				$fonts['Sofia'] = esc_html( 'Sofia' );
				$fonts['Sonsie One'] = esc_html( 'Sonsie One' );
				$fonts['Sorts Mill Goudy'] = esc_html( 'Sorts Mill Goudy' );
				$fonts['Source Code Pro'] = esc_html( 'Source Code Pro' );
				$fonts['Source Sans Pro'] = esc_html( 'Source Sans Pro' );
				$fonts['Source Serif Pro'] = esc_html( 'Source Serif Pro' );
				$fonts['Space Mono'] = esc_html( 'Space Mono' );
				$fonts['Special Elite'] = esc_html( 'Special Elite' );
				$fonts['Spectral'] = esc_html( 'Spectral' );
				$fonts['Spicy Rice'] = esc_html( 'Spicy Rice' );
				$fonts['Spinnaker'] = esc_html( 'Spinnaker' );
				$fonts['Spirax'] = esc_html( 'Spirax' );
				$fonts['Squada One'] = esc_html( 'Squada One' );
				$fonts['Sree Krushnadevaraya'] = esc_html( 'Sree Krushnadevaraya' );
				$fonts['Sriracha'] = esc_html( 'Sriracha' );
				$fonts['Stalemate'] = esc_html( 'Stalemate' );
				$fonts['Stalinist One'] = esc_html( 'Stalinist One' );
				$fonts['Stardos Stencil'] = esc_html( 'Stardos Stencil' );
				$fonts['Stint Ultra Condensed'] = esc_html( 'Stint Ultra Condensed' );
				$fonts['Stint Ultra Expanded'] = esc_html( 'Stint Ultra Expanded' );
				$fonts['Stoke'] = esc_html( 'Stoke' );
				$fonts['Strait'] = esc_html( 'Strait' );
				$fonts['Sue Ellen Francisco'] = esc_html( 'Sue Ellen Francisco' );
				$fonts['Suez One'] = esc_html( 'Suez One' );
				$fonts['Sumana'] = esc_html( 'Sumana' );
				$fonts['Sunshiney'] = esc_html( 'Sunshiney' );
				$fonts['Supermercado One'] = esc_html( 'Supermercado One' );
				$fonts['Sura'] = esc_html( 'Sura' );
				$fonts['Suranna'] = esc_html( 'Suranna' );
				$fonts['Suravaram'] = esc_html( 'Suravaram' );
				$fonts['Suwannaphum'] = esc_html( 'Suwannaphum' );
				$fonts['Swanky and Moo Moo'] = esc_html( 'Swanky and Moo Moo' );
				$fonts['Syncopate'] = esc_html( 'Syncopate' );
				$fonts['Tangerine'] = esc_html( 'Tangerine' );
				$fonts['Taprom'] = esc_html( 'Taprom' );
				$fonts['Tauri'] = esc_html( 'Tauri' );
				$fonts['Taviraj'] = esc_html( 'Taviraj' );
				$fonts['Teko'] = esc_html( 'Teko' );
				$fonts['Telex'] = esc_html( 'Telex' );
				$fonts['Tenali Ramakrishna'] = esc_html( 'Tenali Ramakrishna' );
				$fonts['Tenor Sans'] = esc_html( 'Tenor Sans' );
				$fonts['Text Me One'] = esc_html( 'Text Me One' );
				$fonts['The Girl Next Door'] = esc_html( 'The Girl Next Door' );
				$fonts['Tienne'] = esc_html( 'Tienne' );
				$fonts['Tillana'] = esc_html( 'Tillana' );
				$fonts['Timmana'] = esc_html( 'Timmana' );
				$fonts['Tinos'] = esc_html( 'Tinos' );
				$fonts['Titan One'] = esc_html( 'Titan One' );
				$fonts['Titillium Web'] = esc_html( 'Titillium Web' );
				$fonts['Trade Winds'] = esc_html( 'Trade Winds' );
				$fonts['Trirong'] = esc_html( 'Trirong' );
				$fonts['Trocchi'] = esc_html( 'Trocchi' );
				$fonts['Trochut'] = esc_html( 'Trochut' );
				$fonts['Trykker'] = esc_html( 'Trykker' );
				$fonts['Tulpen One'] = esc_html( 'Tulpen One' );
				$fonts['Ubuntu'] = esc_html( 'Ubuntu' );
				$fonts['Ubuntu Condensed'] = esc_html( 'Ubuntu Condensed' );
				$fonts['Ubuntu Mono'] = esc_html( 'Ubuntu Mono' );
				$fonts['Ultra'] = esc_html( 'Ultra' );
				$fonts['Uncial Antiqua'] = esc_html( 'Uncial Antiqua' );
				$fonts['Underdog'] = esc_html( 'Underdog' );
				$fonts['Unica One'] = esc_html( 'Unica One' );
				$fonts['UnifrakturCook'] = esc_html( 'UnifrakturCook' );
				$fonts['UnifrakturMaguntia'] = esc_html( 'UnifrakturMaguntia' );
				$fonts['Unkempt'] = esc_html( 'Unkempt' );
				$fonts['Unlock'] = esc_html( 'Unlock' );
				$fonts['Unna'] = esc_html( 'Unna' );
				$fonts['VT323'] = esc_html( 'VT323' );
				$fonts['Vampiro One'] = esc_html( 'Vampiro One' );
				$fonts['Varela'] = esc_html( 'Varela' );
				$fonts['Varela Round'] = esc_html( 'Varela Round' );
				$fonts['Vast Shadow'] = esc_html( 'Vast Shadow' );
				$fonts['Vesper Libre'] = esc_html( 'Vesper Libre' );
				$fonts['Vibur'] = esc_html( 'Vibur' );
				$fonts['Vidaloka'] = esc_html( 'Vidaloka' );
				$fonts['Viga'] = esc_html( 'Viga' );
				$fonts['Voces'] = esc_html( 'Voces' );
				$fonts['Volkhov'] = esc_html( 'Volkhov' );
				$fonts['Vollkorn'] = esc_html( 'Vollkorn' );
				$fonts['Voltaire'] = esc_html( 'Voltaire' );
				$fonts['Waiting for the Sunrise'] = esc_html( 'Waiting for the Sunrise' );
				$fonts['Wallpoet'] = esc_html( 'Wallpoet' );
				$fonts['Walter Turncoat'] = esc_html( 'Walter Turncoat' );
				$fonts['Warnes'] = esc_html( 'Warnes' );
				$fonts['Wellfleet'] = esc_html( 'Wellfleet' );
				$fonts['Wendy One'] = esc_html( 'Wendy One' );
				$fonts['Wire One'] = esc_html( 'Wire One' );
				$fonts['Work Sans'] = esc_html( 'Work Sans' );
				$fonts['Yanone Kaffeesatz'] = esc_html( 'Yanone Kaffeesatz' );
				$fonts['Yantramanav'] = esc_html( 'Yantramanav' );
				$fonts['Yatra One'] = esc_html( 'Yatra One' );
				$fonts['Yellowtail'] = esc_html( 'Yellowtail' );
				$fonts['Yeseva One'] = esc_html( 'Yeseva One' );
				$fonts['Yesteryear'] = esc_html( 'Yesteryear' );
				$fonts['Yrsa'] = esc_html( 'Yrsa' );
				$fonts['Zeyada'] = esc_html( 'Zeyada' );
				$fonts['Zilla Slab'] = esc_html( 'Zilla Slab' );
				$fonts['Zilla Slab Highlight'] = esc_html( 'Zilla Slab Highlight' );
				break;
		}
		return $fonts;
	}

	public function text_case_list() {
		return array(
				'none' 			=> esc_html__( 'Normal', 'ndfw'), 
				'capitalize' 	=> esc_html__( 'Title case', 'ndfw'), 
				'lowercase' 	=> esc_html__( 'Lower case', 'ndfw'), 
				'uppercase' 	=> esc_html__( 'Upper case', 'ndfw') 
		);
	}

	public function text_size_list() {
		return array(
				'10' => esc_html( '10' ), 
				'11' => esc_html( '11' ), 
				'12' => esc_html( '12' ), 
				'14' => esc_html( '14' ), 
				'16' => esc_html( '16' ), 
				'18' => esc_html( '18' ), 
				'20' => esc_html( '20' ), 
				'22' => esc_html( '22' ), 
				'24' => esc_html( '24' ), 
				'26' => esc_html( '26' ), 
				'28' => esc_html( '28' ), 
				'36' => esc_html( '36' ), 
				'48' => esc_html( '48' ), 
				'72' => esc_html( '72' ), 
				'96' => esc_html( '96' ), 
		);
	}

	public function text_weight_list() {
		return array(
				'' => esc_html__( 'Select font weight', 'ndfw' ), 
				'100' => esc_html__( 'Thin', 'ndfw' ),
				'200' => esc_html__( 'Extra light', 'ndfw' ),
				'300' => esc_html__( 'Light', 'ndfw' ),
				'400' => esc_html__( 'Regular', 'ndfw' ),
				'500' => esc_html__( 'Medium', 'ndfw' ),
				'600' => esc_html__( 'Semi bold', 'ndfw' ),
				'700' => esc_html__( 'Bold', 'ndfw' ),
				'800' => esc_html__( 'Extra bold', 'ndfw' ),
				'900' => esc_html__( 'Black', 'ndfw' )
		);
	}

}
endif;

function ndfw_customizer() {
    return NDFW_Customizer::instance();
}

$GLOBALS['ndfw_customizer'] = ndfw_customizer(); ?>