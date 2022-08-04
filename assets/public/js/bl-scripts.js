// Public JavaScript Object

const bl_public_object = {

    data: {},
    init: function(){

        bl_public_object.data = {
            form: jQuery( '#bl-search-books' ),
        };

        jQuery( bl_public_object.data.form ).find( 'input[type="range"]' ).change( bl_public_object.handle_range_update );
        jQuery( bl_public_object.data.form ).find( 'input[type="submit"]' ).click( bl_public_object.handle_search );

    },

    handle_range_update: function(){

        let current_max_value = jQuery( this ).val();
        let max_value_element = jQuery( 'span.price-max-range' );

        jQuery( max_value_element ).text( current_max_value );
        
    },

    handle_search: function( event ){

        event.preventDefault();
        let form = bl_public_object.data.form;
        let options = {
            nonce:      jQuery( form ).find( 'input[name="bl_search_nonce"]' ).val(),
            book_name:  jQuery( form ).find( 'input[name="book_name"]' ).val(),
            author:     jQuery( form ).find( 'select#book_author :selected' ).val(),
            publisher:  jQuery( form ).find( 'select#book_publisher :selected' ).val(),
            rating:     jQuery( form ).find( 'select#book_rating :selected' ).val(),
            min_price:  jQuery( form ).find( 'input[type="range"]').prop( 'min' ),
            max_price:  jQuery( form ).find( 'input[type="range"]').val(),
        };

        jQuery.ajax({
            type:       'post',
            url:        blJSOBJ.ajaxurl,
            data:       { action: 'library_search', options },
            beforeSend: bl_public_object.beforeSend,
            success:    bl_public_object.success,
        });
    
    },

    beforeSend: function( xhr, settings ){

        let search_result_div = jQuery( '#bl-search-result' );
        
        jQuery( search_result_div ).empty();
        jQuery( search_result_div ).css({
            'background-image'  : 'url(' + blJSOBJ.loader_icon_url + ')',
        });
        jQuery( search_result_div ).addClass( 'bl-loader' );

    },

    success: function( response ){

        let search_result_div = jQuery( '#bl-search-result' );

        jQuery( search_result_div ).removeClass( 'bl-loader' );
        jQuery( search_result_div ).css( 'background-image', 'none' );
        jQuery( search_result_div ).html( response );

    },

};

jQuery( document ).ready( bl_public_object.init );