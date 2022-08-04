<?php
/**
 * Plugin Name:         Book Library
 * Plugin URI:          #
 * Description:         A light-weight plugin for adding book library functionality to your site. 
 * Version:             1.0.0
 * Author:              Bharat Thapa
 * Author URI:          https://bharatt.com.np
 * Text Domain:         book-library
 * Domain Path:         /languages/
 * Requires at least:   5.8
 * Requires PHP:        7.2
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 */

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define plugin version.
 */
define( 'BL_VERSION', '1.0.0' );
define( 'BL_PLUGIN_FILE', __FILE__ );

if( ! class_exists( 'BookLibrary' ) ){

    /**
     * plugin activation hook.
     */
    function activate_book_library() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-bl-activator.php';
        BL_Activator::activate();
    }

    /**
     * plugin deactivation hook.
     */
    function deactivate_book_library() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-bl-deactivator.php';
        BL_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_book_library' );
    register_deactivation_hook( __FILE__, 'deactivate_book_library' );

    /**
     * load main class
     **/
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-bl.php';

    new BookLibrary;
}