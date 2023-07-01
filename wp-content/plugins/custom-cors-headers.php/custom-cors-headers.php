<?php
/*
Plugin Name: Custom CORS Headers
Description: Adds custom CORS headers to the WordPress REST API responses.
Version: 1.0
Author: WordPress Whispers
*/

// Hook into the rest_pre_serve_request action to add CORS headers
add_action( 'rest_pre_serve_request', 'custom_cors_headers' );

function custom_cors_headers() {
    // Allow requests from any origin
    header( 'Access-Control-Allow-Origin: *' );

    // Allow specific methods (GET, POST, etc.)
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );

    // Allow specific headers
    header( 'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization' );
}
