<?php

namespace DevKabir\EnableCors;

/**
 * Singleton trait.
 */
trait SingletonTrait {



	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function __construct() {
	}

	/**
	 * Get class instance.
	 *
	 * @return self Instance.
	 */
	final public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent serializing.
	 */
	final public function __wakeup() {
		die( esc_html__( 'Serializing instances of this class is forbidden.', 'enable-cors' ) );
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {
		die( esc_html__( 'Cloning instances of this class is forbidden.', 'enable-cors' ) );
	}
}
