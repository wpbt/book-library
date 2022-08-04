# Welcome to my "Book Library" WordPress Plugin

## Installation Steps

1. Step 1:
   Clone repo into plugin directory: `https://github.com/wpbt/book-library.git`
2. Step 2:
   Visit admin area and activate the plugin **Book Library**.
3. Step 3:
   Add books in the library from admin area or you can use the
4. Step 4:
   Create a page/post and insert shortcode to filter the library. To inser the block type: `/book-library` in the editor and the block named **Library Shortcode** should appear. Insert it and a filter form should appear in the frontend of the page/post.

## Customizations

1. Updating currency sign:
   `add_filter( 'bl_currency_symbol', function( $symbol ){ return '€'; } );`
2. Updating form title:
   `[bl_search_library form_title="Your Custom Filter Title Here..."]`

## Deactivating the plugin

2. You will not lose any data during de-activation.

## Uninstalling the plugin

1. Unistalling the plugin will remove all the data from the database.
