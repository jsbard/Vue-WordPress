<?php

namespace DevKabir\EnableCors;

if ( ! defined( 'DevKabir\EnableCors\NAME' ) ) {
	exit;
}

/**
 * It handles the settings page.
 *
 * @package EnableCORS
 */
final class Setting {



	use SingletonTrait;

	/**
	 * Settings option key.
	 *
	 * @var string
	 */
	private $key = NAME . '-options';

	/**
	 * Settings page title.
	 *
	 * @var string
	 */
	private $title = '';
	/**
	 * Settings option fields.
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * It will set the title of settings page.
	 *
	 * @param string $title of the page.
	 * @return Setting
	 */
	public function set_title( string $title ): Setting {
		$this->title = $title;
		return $this;
	}

	/**
	 * Get plugin settings key.
	 *
	 * @return string
	 */
	public function get_key(): string {
		return $this->key;
	}

	/**
	 * Create input for options.
	 *
	 * @param array $fields array of options.
	 *
	 * @example array(
	 *              'settings' => array(
	 *                  'title'  => 'Section title',
	 *                  'fields' => array(
	 *                      'Field 1' => array(
	 *                                      'type'  => 'text',
	 *                                      'name'  => $option_key . '[settings][field1]',
	 *                                      'value' => $options['settings']['field1'] ?? false,
	 *                                  ),
	 *                   ),
	 *                 ),
	 *          );
	 */
	public function set_fields( array $fields ): Setting {
		$this->fields = $fields;
		return $this;
	}

	/**
	 * It sanitizes the inputs.
	 *
	 * @param array $inputs The array of inputs to sanitize.
	 */
	public function sanitize( array $inputs ): array {

		foreach ( $inputs as $location => $fields ) {
			foreach ( $fields as $name => $value ) {
				$inputs[ $location ][ $name ] = sanitize_text_field( $value );
			}
		}

		return $inputs;
	}

	/**
	 * It creates a settings page for the plugin
	 */
	public function register_settings(): void {
		register_setting(
			NAME,
			$this->key,
			array(
				'sanitize_callback' => array(
					$this,
					'sanitize',
				),
			)
		);
		if ( ! empty( $this->fields ) ) {
			foreach ( $this->fields as $page => $inputs ) {
				$section = NAME . '_section_' . $page;
				add_settings_section( $section, $inputs['title'], null, NAME );
				foreach ( $inputs['fields'] as $title => $input ) {
					add_settings_field(
						NAME . '-field-' . str_replace( ' ', '-', strtolower( $title ) ),
						$title,
						function () use ($input) {

							$this->generate_input( $input );

						},
						NAME,
						$section
					);
				}
			}
		}

	}

	/**
	 * Generates an HTML input element.
	 *
	 * @param array $input An array of parameters used to generate the input.
	 */
	public function generate_input( array $input ): void {
		$input_attributes = array(
			'type'        => $input['type'],
			'class'       => 'widefat',
			'name'        => $input['name'],
			'value'       => esc_attr( $input['value'] ),
			'placeholder' => $input['placeholder'] ?? '',
		);
		if ( 'checkbox' === $input['type'] && $input['user_input'] ) {

			$input_attributes['checked'] = true;
		}
		$allowed_attributes = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'name'        => array(),
				'value'       => array(),
				'checked'     => array(),
				'placeholder' => array(),
			),
		);

		echo wp_kses( $this->build_input_tag( $input_attributes ), $allowed_attributes );

	}

	/**
	 * Builds an HTML input tag from the provided attributes.
	 *
	 * @param array $attributes An array of input attributes.
	 *
	 * @return string The generated HTML input tag.
	 */
	private function build_input_tag( array $attributes ): string {
		$input_tag = '<input';
		foreach ( $attributes as $name => $value ) {
			$input_tag .= sprintf( ' %s="%s"', $name, $value );
		}
		$input_tag .= ' />';

		return $input_tag;
	}

	/**
	 * It adds a submenu page to the WooCommerce menu.
	 */
	public function page(): void {
		add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			NAME,
			array( $this, 'render' )
		);
	}

	/**
	 * It creates a form that submits to the options.php file, which is a WordPress core file that handles saving options
	 */
	public function render(): void {
		?>
		<div class="wrap">
			<h1>
				<?php echo esc_html( $this->title ); ?>
			</h1>
			<h4>
				<?php esc_html_e( "If you require support for CORS, please visit ", 'enable-cors' ) ?>
				<a href="http://devkabir.shop/" target="_blank">
					<strong>
						<?php esc_html_e( 'here', 'enable-cors' ) ?>
					</strong>
				</a>
			</h4>
			<form method="post" action="options.php">
				<?php
				settings_fields( NAME );
				do_settings_sections( NAME );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * It checks if a certain option is empty and displays a notice if it is,
	 * otherwise it checks if certain locations exist and displays a notice if they don't.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		$option = $this->get_option();
		if ( empty( $option ) ) {
			$this->show_notice();
		}
	}

	/**
	 * It restructured options for location id.
	 *
	 * @return array|false
	 */
	public function get_option() {
		return get_option( $this->key );
	}

	private function show_notice() {
		if ( 'dismissed' !== get_user_meta( get_current_user_id(), 'settings-notice', true ) ) {
			add_action(
				'admin_notices',
				function () {
					?>
				<div class="notice notice-success is-dismissible" id="enable-cors-notice"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'le-save-kar' ) ); ?>">
					<p>
						<?php
							echo sprintf( 'Please save your <strong><a href="%s">settings</a></strong> for the <strong>Enable CORS</strong> plugin to function properly. You can watch <strong><a href="%s" target="_blank">this</a></strong> tutorial.', esc_url( get_admin_url( null, 'admin.php?page=enable-cors' ) ), esc_url( 'https://youtu.be/HAcE67gnwT8' ) );
							?>
					</p>
				</div>
				<?php
				}
			);
		}
	}


}