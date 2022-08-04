<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 */

// If uninstall not called from WordPress, then exit.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;


function bl_remove_plugin_data(){
    
    $args = [
        'post_type'         => 'book',
        'posts_per_page'    => -1,
        'post_status'       => 'any',
        'meta_query'        => [
            'relation'		=> 'OR',
            [ 'key'		=> '_bl_book_rating' ],
            [ 'key'	    => '_bl_book_price' ]
        ]
    ];
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $id = get_the_ID();
            wp_delete_post( $id, true );
            delete_post_meta( $id, '_bl_book_rating' );
            delete_post_meta( $id, '_bl_book_price' );

        }
    }
    
}

bl_remove_plugin_data();