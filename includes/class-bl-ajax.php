<?php

// exit if called directly.
defined( 'ABSPATH' ) || exit;

/**
 * AJAX handler class.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */

final class BL_Ajax {

    /**
     * Setup necessary hooks
     * 
     * @since    1.0.0
     * @access   public
     */
    public function __construct(){
        add_action( 'wp_ajax_library_search', [ $this, 'library_search' ] );
        add_action( 'wp_ajax_nopriv_library_search', [ $this, 'library_search' ] );
        add_filter( 'posts_where', [ $this, 'posts_where' ], 10, 2 );
    }

    /**
     * Add extra parameter in wp_query object
     * 
     * @since    1.0.0
     * @access   public
     */
    public function posts_where( $where, $wp_query ){

        global $wpdb;
        if ( $title = $wp_query->get( 'search_title' ) ) {
            $where .= " OR " . $wpdb->posts . ".post_title LIKE '%" . esc_sql( $wpdb->esc_like( $title ) ) . "%'";
        }
        
        return $where;

    }

    /**
     * Perform search and send response
     * 
     * @since    1.0.0
     * @access   public
     */
    public function library_search(){

        $options    = $_POST['options'];
        $nonce      = isset( $options['nonce'] ) ? sanitize_text_field( $options['nonce'] ) : '';

        // Security check
        if ( ! wp_verify_nonce( $nonce, 'bl_search_action' ) ) {
            printf( '<p>%s</p>', __( 'Invalid search reqeust', 'book-library' ) );
            wp_die();
        }        

        $book_title     = isset( $options['book_name'] ) ? sanitize_text_field( $options['book_name'] ) : '';
        $author_title   = isset( $options['author'] ) ? sanitize_text_field( $options['author'] ) : '';
        $publisher      = isset( $options['publisher'] ) ? sanitize_text_field( $options['publisher'] ) : '';
        $rating         = isset( $options['rating'] ) ? sanitize_text_field( $options['rating'] ) : '';
        $price_min      = isset( $options['min_price'] ) ? sanitize_text_field( $options['min_price'] ) : '';
        $price_max      = isset( $options['max_price'] ) ? sanitize_text_field( $options['max_price'] ) : '';

        $query_args     = BookLibraryHelper::build_query( $book_title, $author_title, $publisher, $rating, $price_min, $price_max );
        $query          = new WP_Query( $query_args );

        if( $query->have_posts() ){

            printf( '<p>%s</p>', __( 'Search result(s):', 'book-library' ) );
            printf(
                '<table>
                    <tr>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                        <th>%s</th>
                    </tr>',
                    __( 'S.N.', 'book-library' ),
                    __( 'Book Title', 'book-library' ),
                    __( 'Price', 'book-library' ),
                    __( 'Author(s)', 'book-library' ),
                    __( 'Publisher(s)', 'book-library' ),
                    __( 'Rating', 'book-library' ),
            );

            $serial_no = 1;

            while( $query->have_posts() ){
                $query->the_post();

                $id         = get_the_ID();
                $title      = get_the_title();
                $book_link  = get_permalink();
                $book_info  = sprintf( '<a href="%s">%s</a>', esc_url( $book_link ), esc_html( $title ) );
                $price      = get_post_meta( $id, '_bl_book_price', true );
                $rating     = get_post_meta( $id, '_bl_book_rating', true );                
                $publisher  = BookLibraryHelper::get_terms( $id, 'publisher' );
                $authors    = BookLibraryHelper::get_terms( $id, 'book-author' );

                printf(
                    '<tr>
                        <td>%d</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%.1f</td>
                    </tr>',
                    $serial_no,
                    $book_info,
                    esc_html( $price ),
                    esc_html( $authors ),
                    esc_html( $publisher ),
                    esc_html( $rating )
                );
                
                $serial_no++;
            }

            printf( '</table>' );
        } else {
            printf( '<p>%s</p>', __( 'No book(s) available!', 'book-library' ) );
            printf( '<p>%s</p>', __( 'Please try using different keywords and combinations.', 'book-library' ) );
        }

        wp_die();

    }
    
}

new BL_Ajax;