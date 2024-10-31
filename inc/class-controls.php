<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( class_exists( 'Skyrocket_Customize_Alpha_Color_Control' ) ) :
class NDFW_Customize_Alpha_Color_Control extends Skyrocket_Customize_Alpha_Color_Control {

}
endif;

if ( class_exists( 'Skyrocket_Slider_Custom_Control' ) ) :
class NDFW_Customize_Slider_Control extends Skyrocket_Slider_Custom_Control {

}
endif;

if ( class_exists( 'Skyrocket_Text_Radio_Button_Custom_Control' ) ) :
class NDFW_Customize_Text_Radio_Button_Control extends Skyrocket_Text_Radio_Button_Custom_Control {

}
endif;

if ( class_exists( 'Skyrocket_Image_Radio_Button_Custom_Control' ) ) :
class NDFW_Image_Radio_Button_Custom_Control extends Skyrocket_Image_Radio_Button_Custom_Control {

}
endif; ?>