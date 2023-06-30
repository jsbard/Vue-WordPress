<?php
/*
Plugin Name: Primary Menu Endpoint
Description: Adds a custom REST API endpoint for the primary menu.
Version: 1.0
Author: WordPress Whispers
*/

// Register the custom REST API endpoint
function primary_menu_endpoint_init() {
    register_rest_route( 'primary-menu-endpoint/v1', '/primary-menu', array(
        'methods' => 'GET',
        'callback' => 'primary_menu_endpoint_callback',
    ));
}
add_action( 'rest_api_init', 'primary_menu_endpoint_init' );

// Custom endpoint callback function
function primary_menu_endpoint_callback( $request ) {
    $menu_name = 'primary-menu'; // Replace with your primary menu name

    // Retrieve the menu by name
    $menu = wp_get_nav_menu_object( $menu_name );

    if ( !$menu ) {
        return new WP_Error( 'menu_not_found', 'Primary menu not found.', array( 'status' => 404 ) );
    }

    // Get the menu items
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // Modify or format the menu items as needed

    // Return the menu items in the response
    return $menu_items;
}
