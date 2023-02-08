<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

function register_essential_custom_widgets( $widgets_manager ) {

    require_once( __DIR__ . '/widgets/card-widget.php' );  // include the widget file

    $widgets_manager->register( new \Football_Team_Card_Widget() );  // register the widget

}
add_action( 'elementor/widgets/register', 'register_essential_custom_widgets' );
