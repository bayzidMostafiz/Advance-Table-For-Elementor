<?php
/*
  Plugin Name:       Advance Table For Elementor
  Plugin URI:        https://github.com/bayzidMostafiz/Advance-Table-For-Elementor.git
  Description:       An advanced, responsive table widget for Elementor with custom content support, flexbox ordering, and sticky headers.
  Version:           1.0.0
  Author:            Md. Bayzid Mostafiz
  Author URI:        https://www.linkedin.com/in/md-bayzid-mostafiz-152b80139/
  Requires at least: 6.0
  License:           GPLv2 or later
  License URI:       http://www.gnu.org/licenses/gpl-2.0.html
  Text Domain:       advance-table-for-elementor
  Requires at least: 6.0
  Tested up to:      6.8
  Elementor tested up to: 3.32.0

 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 */
final class Advance_Table_For_Elementor_Loader {

    const VERSION = '1.0.0';

    public function __construct() {
        // 1. Register Widget
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        
        // 2. Register Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_styles' ] );
    }

    /**
     * Register CSS Styles
     */
    public function register_styles() {
        wp_register_style( 
            'advance-table-css', 
            plugins_url( 'assets/css/style.css', __FILE__ ), 
            [], 
            self::VERSION 
        );
    }

    /**
     * Register the Widget
     */
    public function register_widgets( $widgets_manager ) {
        require_once( __DIR__ . '/widgets/advance-table-widget.php' );
        $widgets_manager->register( new \Advance_Table_Widget() );
    }
}

// Initialize the Plugin
new Advance_Table_For_Elementor_Loader();
