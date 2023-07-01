<?php

namespace DevKabir\EnableCors;

if ( ! defined( 'DevKabir\EnableCors\NAME' ) ) {
	exit;
}

/**
 * It helps plugin to run properly.
 *
 * @package EnableCORS
 */
final class Helper {


	/**
	 * This PHP function adds a "Settings" link to an array of actions.
	 *
	 * @param array $actions collections.
	 *
	 * @return array
	 */
	public static function actions( array $actions ): array {
		$actions[] = sprintf( '<a href="%s">%s</a>', esc_url( get_admin_url( null, 'admin.php?page=enable-cors' ) ), esc_attr__( 'Settings', 'enable-cors' ) );

		return $actions;
	}

	/**
	 * It redirects to the admin page if the plugin is enabled.
	 *
	 * @param string $plugin activated plugin name.
	 */
	public static function redirect( string $plugin ) {
		if ( plugin_basename( FILE ) === $plugin ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . NAME ) );
			exit();
		}
	}

	/**
	 * It sets headers for Cross-Origin Resource Sharing (CORS) based on options set in the
	 * plugin's settings.
	 *
	 * @return void If the `` variable is empty, the function will return nothing (void).
	 */
	public static function headers(): void {
		$options = Setting::instance()->get_option();
		if ( empty( $options ) ) {
			return;
		}
		$options = $options[ Admin::PACK ];
		if ( array_key_exists( Admin::ENABLE, $options ) && '1' === $options[ Admin::ENABLE ] ) {
			$enable_cors_for = self::get_origin();
			header( 'Access-Control-Allow-Origin: ' . $enable_cors_for );
			if ( array_key_exists( Admin::ALLOWED_HTTP_METHODS, $options ) ) {
				header( 'Access-Control-Allow-Methods: ' . $options[ Admin::ALLOWED_HTTP_METHODS ] );
			}
			if ( array_key_exists( Admin::ALLOWED_HEADERS, $options ) ) {
				header( 'Access-Control-Allow-Headers: ' . $options[ Admin::ALLOWED_HEADERS ] );
			}
			header( 'Access-Control-Allow-Credentials' );
		}

	}

	/**
	 * It gets the value of the `enable_cors_for` option, and if it's empty, it returns `*`
	 *
	 * @return string The origin of the request.
	 */
	private static function get_origin(): string {
		$enable_cors_for = '*';
		$url             = Setting::instance()->get_option()[ Admin::PACK ][ Admin::ALLOW_CORS_FOR ];
		if ( ! empty( $url ) ) {
			$enable_cors_for = self::extract_origin( $url );
		}
		if ( empty( $enable_cors_for ) ) {
			$enable_cors_for = '*';
		}

		return $enable_cors_for;
	}

	/**
	 * Extract origin from user input.
	 *
	 * @param string $url URL from user input.
	 *
	 * @return string formatted URL for header.
	 */
	private static function extract_origin( string $url ): string {
		$origin        = '';
		$parsed_domain = wp_parse_url( $url );
		if ( array_key_exists( 'scheme', $parsed_domain ) ) {
			$origin .= $parsed_domain['scheme'] . '://';
		}
		if ( array_key_exists( 'host', $parsed_domain ) ) {
			$origin .= $parsed_domain['host'];
		}
		if ( array_key_exists( 'port', $parsed_domain ) ) {
			$origin .= ':' . $parsed_domain['port'];
		}

		return $origin;
	}


}
