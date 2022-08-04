<?php

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * Handle Shortcode.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */

final class BL_Shortcode {

    public function __construct(){
		// usage: [bl_search_library]
		add_shortcode( 'bl_search_library', [ $this, 'search_library' ] );
    }

    public function search_library( $atts, $content = NULL ){

		$defaults = [
			'form_title'	=> __( 'Search Library', 'book-library' ),
		];
		$atts = shortcode_atts( $defaults, $atts );

        ob_start();

		BookLibraryHelper::output_search_form( $atts );
        
		return ob_get_clean();
        
    }

}

new BL_Shortcode;