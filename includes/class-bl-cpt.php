<?php

defined( 'ABSPATH' ) || exit;

/**
 * Register 'Book' post type.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */
final class RegisterLibrary {

    /**
     * Register 'Book' post type.
     *
     * @since    1.0.0
     */
	public function __construct(){

        add_action( 'init', [ $this, 'register_library' ] );
        add_action( 'init', [ $this, 'register_library_taxonomies' ] );
        add_action( 'add_meta_boxes_book', [ $this, 'register_custom_fields' ] );
        add_action( 'save_post', [ $this, 'save_book_meta_boxes' ] );
        add_action( 'the_content', [ $this, 'add_meta_info_to_library' ] );

    }

    /**
     * Register 'Book' post type.
     * 
     * @since    1.0.0
     * @access   public
     */
    public function register_library(){

        $labels = [
            'name'                  => _x( 'Book', 'Post type general name', 'book-library' ),
            'singular_name'         => _x( 'Book', 'Post type singular name', 'book-library' ),
            'menu_name'             => _x( 'Books', 'Admin Menu text', 'book-library' ),
            'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'book-library' ),
            'add_new'               => __( 'Add New', 'book-library' ),
            'add_new_item'          => __( 'Add New Book', 'book-library' ),
            'new_item'              => __( 'New Book', 'book-library' ),
            'edit_item'             => __( 'Edit Book', 'book-library' ),
            'view_item'             => __( 'View Book', 'book-library' ),
            'all_items'             => __( 'All Books', 'book-library' ),
            'search_items'          => __( 'Search Books', 'book-library' ),
            'parent_item_colon'     => __( 'Parent Book:', 'book-library' ),
            'not_found'             => __( 'No book found.', 'book-library' ),
            'not_found_in_trash'    => __( 'No Books found in Trash.', 'book-library' ),
            'featured_image'        => _x( 'Book Cover Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'book-library' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'book-library' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'book-library' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'book-library' ),
            'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'book-library' ),
            'insert_into_item'      => _x( 'Insert into book', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'book-library' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'book-library' ),
            'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/Filter pages list". Added in 4.4', 'book-library' ),
            'items_list_navigation' => _x( 'Book list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'book-library' ),
            'items_list'            => _x( 'Book list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'book-library' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'book' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-book',
            'show_in_rest'       => true,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        ];

        register_post_type( 'book', $args );

    }

    /**
     * Register taxonomies for 'book' post type.
     * 
     * @since    1.0.0
     * @access   public
     */
    public function register_library_taxonomies(){

        $labels = array(
            'name'              => _x( 'Authors', 'taxonomy general name', 'book-library' ),
            'singular_name'     => _x( 'Author', 'taxonomy singular name', 'book-library' ),
            'search_items'      => __( 'Search Authors', 'book-library' ),
            'all_items'         => __( 'All Authors', 'book-library' ),
            'parent_item'       => __( 'Parent Author', 'book-library' ),
            'parent_item_colon' => __( 'Parent Author:', 'book-library' ),
            'edit_item'         => __( 'Edit Author', 'book-library' ),
            'update_item'       => __( 'Update Author', 'book-library' ),
            'add_new_item'      => __( 'Add New Author', 'book-library' ),
            'new_item_name'     => __( 'New Author Name', 'book-library' ),
            'menu_name'         => __( 'Authors', 'book-library' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'book-author' ),
        );
    
        register_taxonomy( 'book-author', array( 'book' ), $args );
    
        unset( $args );
        unset( $labels );
    
        $labels = array(
            'name'              => _x( 'Publishers', 'taxonomy general name', 'book-library' ),
            'singular_name'     => _x( 'Publisher', 'taxonomy singular name', 'book-library' ),
            'search_items'      => __( 'Search Publishers', 'book-library' ),
            'all_items'         => __( 'All Publishers', 'book-library' ),
            'parent_item'       => __( 'Parent Publisher', 'book-library' ),
            'parent_item_colon' => __( 'Parent Publisher:', 'book-library' ),
            'edit_item'         => __( 'Edit Publisher', 'book-library' ),
            'update_item'       => __( 'Update Publisher', 'book-library' ),
            'add_new_item'      => __( 'Add New Publisher', 'book-library' ),
            'new_item_name'     => __( 'New Publisher Name', 'book-library' ),
            'menu_name'         => __( 'Publishers', 'book-library' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'publisher' ),
        );
    
        register_taxonomy( 'publisher', array( 'book' ), $args );

    }

    /**
     * Register metaboxes for 'book' post type
     * 
     * @since   1.0.0
     * @access  public
     */
    public function register_custom_fields( $post ){

        add_meta_box( 
            'book_price',
            __( 'Book Price', 'book-library' ),
            [ $this, 'render_book_price' ],
            'book',
            'side',
            'high'
        );
        add_meta_box(
            'book_rating',
            __( 'Book Rating', 'book-library' ),
            [ $this, 'render_book_rating' ],
            'book',
            'side',
            'high'
        );
        
    }

    /**
     * Render Price Meta Box content.
     *
     * @param WP_Post $post The post object.
     * 
     * @since   1.0.0
     * @access  public
     */
    public function render_book_price( $post ) {
        
        wp_nonce_field( 'bl_book_price_action', 'bl_book_price_nonce' );
        $value = get_post_meta( $post->ID, '_bl_book_price', true );
        ?>
            <p>
                <label for="bl_book_price">
                    <?php _e( 'Enter book price', 'book-library' ); ?>
                </label>
            </p>
            <p>
                <input type="text" id="bl_book_price" name="bl_book_price" value="<?php echo esc_attr( $value ); ?>" size="25" />
            </p>
        <?php
    }

    /**
     * Render Rating Meta Box content.
     *
     * @param WP_Post $post The post object.
     * 
     * @since   1.0.0
     * @access  public
     */
    public function render_book_rating( $post ) {
        
        wp_nonce_field( 'bl_book_rating_action', 'bl_book_rating_nonce' );

        $value = get_post_meta( $post->ID, '_bl_book_rating', true );
        ?>
            <p>
                <label for="bl_book_rating">
                    <?php _e( 'Enter book rating', 'book-library' ); ?>
                </label>
            </p>
            <p>
                <input type="text" id="bl_book_rating" name="bl_book_rating" value="<?php echo esc_attr( $value ); ?>" size="25" />
            </p>
        <?php
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     * 
     * @since   1.0.0
     * @access  public
     */
    public function save_book_meta_boxes( $post_id ){
        
        if( isset( $_POST['post_type'] ) && ( $_POST['post_type'] !== 'book' ) ){
            return $post_id;
        }
        
        if ( ! isset( $_POST['bl_book_price_nonce'] ) || ! isset( $_POST['bl_book_rating_nonce'] ) ) {
            return $post_id;
        }

        $price_nonce = $_POST['bl_book_price_nonce'];
        $rating_nonce = $_POST['bl_book_rating_nonce'];

        if ( ! wp_verify_nonce( $price_nonce, 'bl_book_price_action' ) || ! wp_verify_nonce( $rating_nonce, 'bl_book_rating_action' ) ) {
            return $post_id;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return $post_id;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return $post_id;
        }

        $price = BookLibraryHelper::validate_decimal_field( $_POST['bl_book_price'] );
        $rating = BookLibraryHelper::validate_decimal_field( $_POST['bl_book_rating'] );

        update_post_meta( $post_id, '_bl_book_price', $price );
        update_post_meta( $post_id, '_bl_book_rating', $rating );

    }

    /**
     * Add meta information to single library page
     * 
     * @param   string $the_content content of the post
     * @return  string $the_content filtered content of the post
     * 
     * @since   1.0.0
     * @access  public 
     */
    public function add_meta_info_to_library( $the_content ){

        if( ! is_singular( 'book' ) ){
            return $the_content;
        }

        $id                 = get_the_ID();
        $rating             = get_post_meta( $id, '_bl_book_rating', true );
        $rating             = ( $rating ) ? $rating : __( 'Not available', 'book-library' );
        $price              = get_post_meta( $id, '_bl_book_price', true );
        $price              = ( $price ) ? $price : __( 'Not available', 'book-library' );
        $publishers         = BookLibraryHelper::get_terms( $id, 'publisher', true );
        $authors            = BookLibraryHelper::get_terms( $id, 'book-author', true );
        $publisher_links    = BookLibraryHelper::filter_array( $publishers );
        $author_links       = BookLibraryHelper::filter_array( $authors );
        $currency_symbol    = apply_filters( 'bl_currency_symbol', '$' );
        $author_title       = __( 'Author(s)', 'book-library' );
        $price_title        = __( 'Author(s)', 'book-library' );
        $publisher_title    = __( 'Publisher(s)', 'book-library' );
        $rate_title         = __( 'Rating', 'book-library' );

        return BookLibraryHelper::add_meta_info_to_content(
                                    $the_content,
                                    $rate_title,
                                    $rating,
                                    $price_title,
                                    $currency_symbol,
                                    $price,
                                    $publisher_title,
                                    $publisher_links,
                                    $author_title,
                                    $author_links
                                );

    }

}

new RegisterLibrary;