<?php

defined( 'ABSPATH' ) || exit;

/**
 * Utility functions.
 *
 * @since      1.0.0
 * @package    BookLibrary
 * @subpackage BookLibrary/includes
 */

class BookLibraryHelper {

    // prevent initialization 
    private function __construct(){}

	/**
	 * Validate Decimal Field.
	 *
	 * @param  string $value Posted value.
	 * @return float
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function validate_decimal_field( $value ){

		$filter_options = [
            'options' => [
                'default' => 0,
                'min_range' => 0
            ]
        ];

		return filter_var( $value, FILTER_VALIDATE_FLOAT, $filter_options );

	}

	/**
	 * Return library taxonomy terms
	 * 
	 * @param 	string	$term_slug Term slug
	 * @return	Array	Taxonomy information
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function get_library_terms( $term_slug ){

		return get_terms( [ 
			'taxonomy'		=> $term_slug,
			'hide_empty'	=> true,
		] );

	}

	/**
	 * Filter array
	 * 
	 * @param 	Array $array
	 * @return 	Array $array
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function filter_array( $array ){

		$links = [];
		if( ! empty( $array ) ){

            foreach( $array as $array_item ){
                $link = sprintf( '<a href="%s">%s</a>', esc_url( $array_item['url'] ), esc_html( $array_item['name'] ) );
                array_push( $links, $link );
            }
            
        }

		if( sizeof( $links ) > 1 ){
			$links = implode( ', ', $links );
		} elseif( sizeof( $links ) == 1 ){
			$links = implode( '', $links );
		}

		return $links;
		
	}

	/**
	 * Return taxonomies associated with the post
	 * 
	 * @param 	int 			$post_id 
	 * @param 	string			$taxonomy_slug
	 * @param	boolean			$link
	 * @return	Array | string
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function get_terms( $post_id, $taxonomy, $link = null ){

		$terms		= get_the_terms( $post_id, $taxonomy );
		$taxonomy	= [];
		$taxonomies = '';

		if( $link ){

			$term_links = [];
			foreach ( $terms as $term ) {

 				$term_link = get_term_link( $term );
				if ( is_wp_error( $term_link ) ) {
					continue;
				}
				$term_links[$term->slug] = [
					'name'	=> $term->name,
					'url'	=> $term_link
				];
			}

			return $term_links;
		}

		if( is_array( $terms ) && !empty( $terms ) ){
			foreach( $terms as $term ){
				array_push( $taxonomy, $term->name );
			}
		}

		if( sizeof( $taxonomy ) > 1 ){
			$taxonomies = join( ', ', $taxonomy );
		} else {
			$taxonomies = join( '', $taxonomy );
		}

		return $taxonomies;

	}

	/**
	 * Return library metadata values
	 * 
	 * @param 	string	$meta_key Term slug
	 * @return	Array	Metadata information
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function get_library_metadata( $meta_key ){

		$args = [
			'post_type'			=> 'book',
			'posts_per_page'	=> -1,
			'orderby'   		=> 'meta_value_num',
    		'meta_key'			=> $meta_key,
		];

		$price = [];

		$query = new WP_Query( $args );
		if( $query->have_posts() ){
			while( $query->have_posts() ){
				$query->the_post();

				$price_value = get_post_meta( get_the_ID(), $meta_key, true );
				if( ( $meta_key == '_bl_book_price' ) && $price_value ){
					array_push( $price, $price_value );
				}
			}

		}

		return $price;

	}

	/**
	 * Returns minimum and maximum value from an array
	 * 
	 * @param 	Array $array Array to process
	 * @return	Array Array with minimum and maximum value.
	 * 
	 * @since    1.0.0
     * @access   public
	 */
	public static function get_min_and_max( $array ){
		
		$default_min	= 1;
		$default_max	= 100;
		$min_price 		= ( !empty( $array ) ) ? floor( min( $array ) ) : $default_min;
		$max_price 		= ( !empty( $array ) ) ? ceil( max( $array ) ) : $default_max;

		return [
			'min' => apply_filters( 'bl_min_price', $min_price ),
			'max' => apply_filters( 'bl_max_price', $max_price )
		];

	}

	/**
	 * Add post meta information with the post content
	 * 
	 * @param 	string	$the_content
	 * @param 	string	$rate_title
	 * @param 	int		$rating
	 * @param 	string 	$price_title
	 * @param 	string 	$currency_symbol
	 * @param 	int 	$price
	 * @param 	string $publisher_title
	 * @param 	string $publisher_links
	 * @param 	string $author_title
	 * @param 	string $author_links
	 * 
	 * @return	string
	 * 
	 * @since	1.0.0
	 * @access	public
	 */
	public static function add_meta_info_to_content( $the_content, $rate_title, $rating, $price_title, $currency_symbol, $price, $publisher_title, $publisher_links, $author_title, $author_links ){

		$meta_info = sprintf(
            '<div class="bl-meta-info">
                <p><strong>%s:</strong> %.1f</p>
                <p><strong>%s:</strong> <span>%s</span>%.2f</p>
                <p><strong>%s:</strong> %s</p>
                <p><strong>%s:</strong> %s</p>
            </div>',
            $rate_title,
            esc_html( $rating ),
            $price_title,
            $currency_symbol,
            esc_html( $price ),
            $publisher_title,
            $publisher_links,
            $author_title,
            $author_links
        );

		$the_content .= $meta_info;
		return $the_content;
		
	}

	/**
	 * Build query from given arguments
	 * 
	 * @param 	string $book_title 
	 * @param 	string $author_title 
	 * @param 	string $publisher 
	 * @param 	string $rating 
	 * @param 	string $price_min 
	 * @param 	string $price_max 
	 * @return 	Array
	 * 
	 * @since    1.0.0
     * @access   public
	 */

	public static function build_query(  $book_title = null, $author_title = null, $publisher = null, $rating = null, $price_min = null, $price_max = null ){

		$args = [
            'post_type'			=> 'book',
			'posts_per_page'	=> -1,
        ];

        if( $book_title ){
            $args['search_title'] = $book_title;
        }

        if( $author_title  ){
            $args['tax_query'] = [
                [
                    'taxonomy' => 'book-author',
                    'field'    => 'slug',
                    'terms'    => $author_title,
                ]
            ];
        }

        if( $publisher ){
            $args['tax_query'] = [
                [
                    'taxonomy' => 'publisher',
                    'field'    => 'slug',
                    'terms'    => $publisher,
                ]
            ];
        }

		if( $author_title && $publisher ){
			if( array_key_exists( 'tax_query', $args) ){
				unset( $args['tax_query'] );
				$args['tax_query'] = [
					'relation' => 'OR',
					[
						'taxonomy' => 'book-author',
						'field'    => 'slug',
						'terms'    => $author_title,
					],
					[
						'taxonomy' => 'publisher',
						'field'    => 'slug',
						'terms'    => $publisher,
					]
				];
			}
		}

		if( $rating ){
			$args['meta_query'] = [
				[
					'key'     => '_bl_book_rating',
					'value'   => $rating,
					'compare' => '>=',
				]
			];
		}

		if( $price_min && $price_max ){
			if( array_key_exists( 'meta_query', $args) ){
				unset( $args['meta_query'] );
				$args['meta_query'] = [
					[
						'key'	=> '_bl_book_price',
						'value'	=> [ $price_min, $price_max ],
						'type'	=> 'NUMERIC',
						'compare'	=> 'BETWEEN'
					]
				];
			} elseif( ! $rating && $price_min && $price_max ) {
				$args['meta_query'] = [
					[
						'key'	=> '_bl_book_price',
						'value'	=> [ $price_min, $price_max ],
						'type'	=> 'NUMERIC',
						'compare'	=> 'BETWEEN'
					]
				];
			}
		}

		if( $rating && $price_min && $price_max ){
			if( array_key_exists( 'meta_query', $args) ){
				unset( $args['meta_query'] );
				$args['meta_query'] = [
					'relation'		=> 'AND',
					[
						'key'		=> '_bl_book_rating',
						'value'		=> $rating,
						'compare'	=> '>='
					],
					[
						'key'	=> '_bl_book_price',
						'value'	=> [ $price_min, $price_max ],
						'type'	=> 'NUMERIC',
						'compare'	=> 'BETWEEN'
					]
				];
			}
		}

		return $args;

	}

	/**
	 * Output search form
	 * 
	 * @param  Array $args shortcode parameters.
	 * @return void
	 * 
	 * @since    1.0.0
     * @access   public
	 */

	public static function output_search_form( $args ){

		?>

			<div class='container bl-search-form-wrapper'>
				<?php
					if( isset( $args['form_title'] ) ){
						printf( '<h4>%s</h4>', $args['form_title'] );
					}
				?>
				<form action='' method='POST' class='bl-search-form' id='bl-search-books'>
					<div class="bl-col-50">
						<p>
							<label for='book_name'><?php _e( 'Book Name', 'book-library' ); ?>:</label>
							<input type='text' id='book_name' name='book_name' value='' />
						</p>
						<p>
							<label for='author_name'><?php _e( 'Author Name', 'book-library' ); ?>:</label>
							<?php
								$authors = self::get_library_terms( 'book-author' );
								if( ! empty( $authors ) ){

									printf(
										'<select name="book_author" id="book_author">
											<option value="">%s</option>',
										__( 'Select author', 'book-library' )
									);
									foreach( $authors as $author ){
										printf( '<option value="%s">%s</option>', esc_attr( $author->slug ), esc_html( $author->name ) );
									}
									printf( '</select>' );
								}
							?>
						</p>
					</div>
					<div class="bl-col-50">
						<p>
							<label for='book_publisher'><?php _e( 'Publisher', 'book-library' ); ?></label>
							<?php
								$publishers = self::get_library_terms( 'publisher' );
								if( ! empty( $publishers ) ){

									printf(
										'<select name="book_publisher" id="book_publisher">
											<option value="">%s</option>',
										__( 'Select publisher', 'book-library' )
									);
									foreach( $publishers as $publisher ){
										printf( '<option value="%s">%s</option>', esc_attr( $publisher->slug ), esc_html( $publisher->name ) );
									}
									printf( '</select>' );
								}
							?>
						</p>
						<p>
							<label for='book_rating'><?php _e( 'Rating', 'book-library' ); ?>:</label>
							<select name='book_rating' id='book_rating'>
								<option value=''><?php _e( 'Select rating', 'book-library' ); ?></option>
								<option value='1'><?php _e( '1', 'book-library' ); ?></option>
								<option value='2'><?php _e( '2', 'book-library' ); ?></option>
								<option value='3'><?php _e( '3', 'book-library' ); ?></option>
								<option value='4'><?php _e( '4', 'book-library' ); ?></option>
								<option value='5'><?php _e( '5', 'book-library' ); ?></option>
							</select>
						</p>
					</div>
					<div class="bl-col-50">
						<p class='bl-range'>
							<?php
								$prices = self::get_library_metadata( '_bl_book_price' );
								$range  = self::get_min_and_max( $prices );
							?>
							<label for='book_price'><?php _e( 'Price', 'book-library' ); ?>:</label>
							<span class='price-min-range'><?php echo esc_html( $range['min'] ); ?></span>
							<input type='range' min='<?php echo esc_attr( $range['min'] ); ?>' max='<?php echo esc_attr( $range['max'] ); ?>' value='<?php echo esc_attr( $range['max'] ); ?>' class='price_range' id='book_price' />
							<span class='price-max-range'><?php echo esc_html( $range['max'] ); ?></span>
						</p>
					</div>
					<div class="bl-col-100 bl-submit">
						<?php wp_nonce_field( 'bl_search_action', 'bl_search_nonce' ); ?>
						<p>
							<input type='submit' name='library_search_button' id='library_search_button' value='<?php printf( '%s', esc_attr__( 'Search', 'book-library' ) ); ?>' />
						</p>
					</div>
				</form>

				<!-- output div -->
				<div id='bl-search-result'>
					<?php
						
					?>
				</div>
			</div>

		<?php

	}
    
}