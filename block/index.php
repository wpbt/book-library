<?php
// exit if called directly.
defined( 'ABSPATH' ) || exit;

add_action( 'init', 'create_my_book_library_shortcode_block' );

function create_my_book_library_shortcode_block(){
    register_block_type(
        dirname( __FILE__ ) . '/build',
        [
            'render_callback' => 'render_book_library_shortcode_callback'
        ]
    );
}

function render_book_library_shortcode_callback( $attr, $content ){
    return wpautop( $content );
}