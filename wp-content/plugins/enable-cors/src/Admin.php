<?php

namespace DevKabir\EnableCors;

if ( ! defined( 'DevKabir\EnableCors\NAME' ) ) {
	exit;
}

/**
 * Handles admin facing functions.
 *
 * @package EnableCORS
 */
final class Admin {



	use SingletonTrait;

	/**
	 * Settings package
	 *
	 * @var string
	 */
	public const PACK = 'freemium';
	/**
	 * Toggle cors key
	 *
	 * @var string
	 */
	public const ENABLE = 'enable';
	/**
	 * Origin key
	 *
	 * @var string
	 */
	public const ALLOW_CORS_FOR = 'enable-cors-for';
	/**
	 * Methods key
	 *
	 * @var string
	 */
	public const ALLOWED_HTTP_METHODS = 'allowed-http-methods';
	/**
	 * Headers key
	 *
	 * @var string
	 */
	public const ALLOWED_HEADERS = 'allowed-headers';
	/**
	 * Age key
	 *
	 * @var string
	 */
	public const MAX_AGE = 'max-age';


	/**
	 * It loads the settings page.
	 *
	 * @return void
	 */
	public function settings(): void {
		$setting = Setting::instance();
		$labels  = array(
			'title'                    => __( 'Basic CORS Settings', 'enable-cors' ),
			self::ENABLE               => __( 'Enable CORS', 'enable-cors' ),
			self::ALLOW_CORS_FOR       => __( 'Allow CORS for', 'enable-cors' ),
			self::ALLOWED_HTTP_METHODS => __( 'Allowed HTTP methods', 'enable-cors' ),
			self::ALLOWED_HEADERS      => __( 'Allowed Headers', 'enable-cors' ),
		);
		$fields  = array(
			self::PACK =>
				array(
					'title'  => $labels['title'],
					'fields' => array(
						$labels[ self::ENABLE ]          => array(
							'type'       => 'checkbox',
							'name'       => $setting->get_key() . '[' . self::PACK . '][' . self::ENABLE . ']',
							'value'      => 1,
							'user_input' => isset( $setting->get_option()[ self::PACK ][ self::ENABLE ] ),
						),

						$labels[ self::ALLOW_CORS_FOR ]  => array(
							'type'        => 'text',
							'name'        => $setting->get_key() . '[' . self::PACK . '][' . self::ALLOW_CORS_FOR . ']',
							'value'       => $setting->get_option()[ self::PACK ][ self::ALLOW_CORS_FOR ] ?? null,
							'placeholder' => 'https://example.com',
						),
						$labels[ self::ALLOWED_HTTP_METHODS ] => array(
							'type'        => 'text',
							'name'        => $setting->get_key() . '[' . self::PACK . '][' . self::ALLOWED_HTTP_METHODS . ']',
							'value'       => is_array( $setting->get_option() ) ? $setting->get_option()[ self::PACK ][ self::ALLOWED_HTTP_METHODS ] : '*',
							'placeholder' => 'GET, POST, ...',
						),
						$labels[ self::ALLOWED_HEADERS ] => array(
							'type'        => 'text',
							'name'        => $setting->get_key() . '[' . self::PACK . '][' . self::ALLOWED_HEADERS . ']',
							'value'       => $setting->get_option()[ self::PACK ][ self::ALLOWED_HEADERS ] ?? '*',
							'placeholder' => 'Content-Type, Content-Disposition, ...',
						),
					),
				),
		);
		$setting->set_title( __( 'Enable CORS', 'enable-cors' ) )
			->set_fields( $fields )
			->init();
	}

	/**
	 * It loads scripts for notice.
	 *
	 * @return void
	 */
	public function scripts(): void {
		wp_enqueue_script(
			NAME,
			plugins_url( 'assets/admin.js', DIR ),
			array( 'jquery' ),
			VERSION,
			true
		);
	}
}
