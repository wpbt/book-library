<?php

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */

final class BookLibrary {
    
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, and define the locale.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->version      = BL_VERSION;
        $this->plugin_name  = 'BookLibrary';

        $this->define_constants();
        $this->load_dependencies();

        // set locale
        add_action( 'init', [ $this, 'set_locale' ] );

    }

    /**
     * Define BookLibrary Constants.
     */
    private function define_constants() {

        define( 'BL_DEV_MODE', true ); // set this to false during production
        define( 'BL_ABSPATH', dirname( BL_PLUGIN_FILE ) . '/' );
        define( 'BL_PLUGIN_BASENAME', plugin_basename( BL_PLUGIN_FILE ) );
        define( 'BL_ABSURL', plugins_url( '/', BL_PLUGIN_FILE ) );

    }

    /**
     * Load the required dependencies for this plugin.
     * 
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        // classes
        require_once( BL_ABSPATH .'includes/class-bl-library.php' );
        require_once( BL_ABSPATH . 'includes/class-bl-enqueue.php' );
        require_once( BL_ABSPATH . 'includes/class-bl-cpt.php' );
        require_once( BL_ABSPATH . 'includes/class-bl-shortcode.php' );
        require_once( BL_ABSPATH . 'includes/class-bl-ajax.php' );
        require_once( BL_ABSPATH . 'block/index.php' );

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * @since    1.0.0
     * @access   private
     */
    public function set_locale() {
        
        load_plugin_textdomain(
            'book-library',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}