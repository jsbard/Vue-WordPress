<?php
/**
 * Enable CORS
 *
 * @package           EnableCORS
 * @author            Dev Kabir
 * @copyright         2023 Dev Kabir
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Enable CORS
 * Plugin URI:        https://www.fiverr.com/share/7kXeLW
 * Description:       Enable Cross-Origin Resource Sharing for any or specific origin.
 * Version:           1.1.1
 * Requires at least: 4.7
 * Requires PHP:      7.1
 * Author:            Dev Kabir
 * Author URI:        https://www.fiverr.com/developerkabir
 * Text Domain:       enable-cors
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

use DevKabir\EnableCors\Admin;
use DevKabir\EnableCors\Helper;
use DevKabir\EnableCors\Plugin;

require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Define default constants
|--------------------------------------------------------------------------
*/
define( 'DevKabir\EnableCORS\NAME', 'enable-cors' );
define( 'DevKabir\EnableCORS\VERSION', '1.1.1' );
define( 'DevKabir\EnableCORS\FILE', __FILE__ );
define( 'DevKabir\EnableCORS\DIR', plugin_dir_path( __FILE__ ) );
define( 'DevKabir\EnableCORS\URL', plugin_dir_url( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
*/
register_activation_hook( __FILE__, array( Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Plugin::class, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( Plugin::class, 'uninstall' ) );
// redirect you to settings page after activation.
add_action( 'activated_plugin', array( Helper::class, 'redirect' ) );
// add links under plugin name.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( Helper::class, 'actions' ) );
// Scripts for Admin area.
add_action( 'wp_loaded', array( Admin::instance(), 'settings' ) );
add_action( 'admin_init', array( Helper::class, 'headers' ) );
add_action( 'admin_enqueue_scripts', array( Admin::instance(), 'scripts' ) );
add_action( 'wp_ajax_enable_cors_noticed', array( Admin::instance(), 'ajax' ) );
// Scripts for rest API.
add_action( 'rest_api_init', function () {
	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
	add_filter( 'rest_pre_serve_request', array( Helper::class, 'headers' ) );
} );
// Scripts for Web.
add_action( 'template_redirect', array( Helper::class, 'headers' ) );

