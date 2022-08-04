<?php

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue Script class.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */

final class BL_EnqueueScripts {

    /**
     * Setup hooks to load script/styles
     * 
     * @since    1.0.0
     * @access   public
     */
    public function __construct(){

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // not needed now but if needed, uncomment.
		// add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

    }

    /**
     * Enqueue scripts/styles in frontend
     * 
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_scripts(){
        
        // used to prevent caching
        $version = self::get_script_version();

        // css
        wp_register_style( 'bl-style', BL_ABSURL . 'assets/public/css/bl-style.css', '', $version );
        
        wp_enqueue_style( 'bl-style' );

        // js
        wp_register_script( 'bl-scripts', BL_ABSURL . 'assets/public/js/bl-scripts.js', [ 'jquery' ], $version, true );
        wp_localize_script(
            'bl-scripts',
            'blJSOBJ',
            [
                'ajaxurl'           => admin_url( 'admin-ajax.php' ),
                'loader_icon_url'   => BL_ABSURL . 'assets/images/loader_icon.gif',
            ]
        );

        wp_enqueue_script( 'bl-scripts' );

    }

    /**
     * Enqueue scripts/styles in admin
     * 
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_admin_scripts(){

        // used to prevent caching
        $version = self::get_script_version();

        wp_register_script( 'bl-admin-scripts', BL_ABSURL . 'assets/admin/js/bl-admin-scripts.js', [ 'jquery' ], $version, true );
        wp_localize_script( 'bl-admin-scripts', 'blAdminObj', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

        wp_enqueue_script( 'bl-admin-scripts' );

    }

    /**
     * Returns a unique identifier on each script/style request that is used as the Version
     * 
     * @return int
     * 
     * @since    1.0.0
     * @access   public
     */
    public static function get_script_version(){

        return BL_DEV_MODE ? time() : '';

    }

}

new BL_EnqueueScripts;