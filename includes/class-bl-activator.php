<?php

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class BL_Activator {

	/**
	 * @since    1.0.0
     * @access   public
	 */
	public static function activate() {
		
		// check PHP and WP version.
		$php_version	= phpversion();
		$wp_version		= get_bloginfo( 'version' );

		if ( version_compare( $php_version, '7.2', '<' ) || version_compare( $wp_version, '5.8', '<' ) ) {

			$message = '';

			if( version_compare( $php_version, '7.2', '<' ) ){
				$message .= __( 'update php version to 7.2 or higher.', 'book-library' );
			}

			if( version_compare( $wp_version, '5.8', '<' ) ){
				$message .= __( ' update WordPress version to 5.8 or higher.', 'book-library' );
			}

			printf( '<p>%s</p>', $message );
			die();
			
		}
		
	}

}