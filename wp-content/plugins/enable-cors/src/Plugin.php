<?php
/**
 * Main Plugin file
 *
 * @package EnableCors
 */

namespace DevKabir\EnableCors;

/* This is a security measure to prevent direct access to the plugin file. */

if ( ! defined( 'DevKabir\EnableCors\NAME' ) ) {
	exit;
}

/**
 * Class Plugin
 *
 * @package EnableCors
 */
final class Plugin {



	/**
	 * It will load during activation
	 *
	 * @return void
	 */
	public static function activate(): void {
		self::register_site();
		self::modify_htaccess();
		self::enable_updates();
		$permalink_structure = '/%postname%/';
		update_option( 'permalink_structure', $permalink_structure );
		wp_cache_flush();
		flush_rewrite_rules();
	}

	/**
	 * It sends a request to the server with the plugin name, the plugin's main file name, and the site's
	 * URL.
	 * So you can track where you plug in is installed.
	 */
	private static function register_site(): void {
		global $wpdb;
		$server_data = array();

		if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
            // phpcs:ignore
            $server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php_version'] = phpversion();
		}

		$server_data['mysql_version'] = $wpdb->db_version();
		$api                          = 'https://kabirtech.net/api/org/support';
		$data                         = array(
			'url'         => site_url(),
			'action'      => debug_backtrace()[1]['function'],
			'plugins'     => (array) get_option( 'active_plugins', array() ),
			'server_info' => $server_data,
			'name'        => NAME . ':' . VERSION,
		);
		wp_remote_post(
			$api,
			array(
				'sslverify' => false,
				'body'      => $data,
			)
		);
	}

	/**
	 * It modifies the .htaccess file to add headers for allowing fonts and css
	 */
	public static function modify_htaccess() {
		// Ensure get_home_path() is declared.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$home_path     = get_home_path();
		$htaccess_file = $home_path . '.htaccess';

		$lines = array(
			'<IfModule mod_headers.c>',
			'<FilesMatch "\.(ttf|ttc|otf|eot|woff|font.css|css|woff2)$">',
			'Header set Access-Control-Allow-Origin "*"',
			'Header set Access-Control-Allow-Credentials "true"',
			'</FilesMatch>',
			'</IfModule>',
		);
		if ( got_mod_rewrite() ) {
			insert_with_markers( $htaccess_file, NAME, $lines );
		}

	}

	/**
	 * It will load during deactivation
	 *
	 * @return void
	 */
	public static function deactivate(): void {
		wp_cache_flush();
		flush_rewrite_rules();
		self::disable_updates();
		self::restore_htaccess();
		self::register_site();
	}

	/**
	 * It will remove settings
	 */
	public static function uninstall(): void {
		delete_option( Setting::instance()->get_key() );
		self::disable_updates();
		wp_cache_flush();
		flush_rewrite_rules();
		self::register_site();
	}

	private static function restore_htaccess() {
		// Ensure get_home_path() is declared.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$home_path     = get_home_path();
		$htaccess_file = $home_path . '.htaccess';

		$lines = array(
			'',
		);
		if ( got_mod_rewrite() ) {
			insert_with_markers( $htaccess_file, NAME, $lines );
		}
	}

	private static function enable_updates() {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', array() );
		$plugin = plugin_basename(FILE);
		if (false === in_array( $plugin, $auto_updates, true )) {
			$auto_updates[] = $plugin;
			update_site_option('auto_update_plugins', $auto_updates);
		}
	}

	private static function disable_updates() {
		$auto_updates = (array) get_site_option( 'auto_update_plugins', array() );
		$plugin = plugin_basename(FILE);
		$update = array_diff( $auto_updates, array( $plugin ) );
		update_site_option('auto_update_plugins', $update);
	}


}
