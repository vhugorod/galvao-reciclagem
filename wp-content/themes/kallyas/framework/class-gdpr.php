<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ZnHg_GDPR {
	const __GDPR_INPUT_BASE_ID = 'hg-gdpr-checkbox_';

	function __construct() {

        // add checkboxes after login form
		add_action( 'kallyas_register_form_end', array( __CLASS__, 'addCheckboxes' ) );

		// Add theme options for GDPR texts
		add_filter( 'zn_theme_pages', array( __CLASS__, 'addThemePage' ) );
		add_filter( 'zn_theme_options', array( __CLASS__, 'addOptions' ) );

		// Validate new user form
		add_filter( 'kallyas_validate_registration_form', array( __CLASS__, 'validateRegistration' ));
	}


	/**
	 * Add text after each register form
	 */
	public static function addCheckboxes() {
		$gdpr_text = zget_option( 'after_login_texts', 'general_options', false, array() );

		// Don't proceed if the gdpr text is empty
		if ( ! is_array( $gdpr_text ) || empty( $gdpr_text ) ) {
			return false;
		}

		foreach ( $gdpr_text as $key => $textConfig ) {
			if ( empty( $textConfig['text'] ) ) {
				continue;
			}

			$inputId = self::generateId( $key); ?>
            <div class="form-group kl-fancy-form">
                <label class="znhg-gdpr-label" for="<?php echo esc_attr( $inputId ); ?>">
                    <input type="checkbox" name="<?php echo esc_attr( $inputId ); ?>" id="<?php echo esc_attr( $inputId ); ?>" value="1"/>
                    <?php echo $textConfig['text'] ?>
                </label>
            </div>
        <?php
		}
	}


	/**
	 * Generate an unique ID for a GDPR checkbox field
	 *
	 * @param string $key The string that will be attached to the unique id base
	 *
	 * @return string A unique string built from an unique base id and provided $key param
	 */
	public static function generateId( $key ) {
		return self::__GDPR_INPUT_BASE_ID . $key;
	}

	/**
	 * Add options page for the GDPR requirements
	 *
	 * @param mixed $admin_pages
	 *
	 * @return array The options pages array containing GDPR options page
	 */
	public static function addThemePage( $admin_pages) {
		$admin_pages['general_options']['submenus'][] = array(
			'slug'  => 'zn_gdpr_options',
			'title' => __( "GDPR options", 'zn_framework' ),
		);

		return $admin_pages;
	}


	/**
	 * Add options for the GDPR requirements
	 *
	 * @param mixed $options
	 *
	 * @return array The options array containing GDPR specific options
	 */
	public static function addOptions( $options) {
		$options[] = array(
			'slug'        => 'zn_gdpr_options',
			'parent'      => 'general_options',
			"name"        => __( 'GDPR Options', 'zn_framework' ),
			"description" => __( 'These options helps you be in compliance with General Data Protection Regulation.', 'zn_framework' ),
			"id"          => "gdpr_info",
			"type"        => "zn_title",
			"class"       => "zn_full zn-custom-title-large zn-top-separator",
		);

		$options[] = array(
			'slug'        => 'zn_gdpr_options',
			'parent'      => 'general_options',
			"name"        => __( 'After login form checkboxes', 'zn_framework' ),
			"description" => __( 'Using this option you can add extra information after the login forms created by the theme.', 'zn_framework' ),
			"id"          => "after_login_texts",
			"type"        => "group",
			"subelements" => array(
				array(
					"name"        => __( 'Checkbox text', 'zn_framework' ),
					"description" => __( 'Add the text that will appear next to the checkbox.', 'zn_framework' ),
					"id"          => "text",
					"type"        => "textarea",
					"class"       => "zn_full",
				),
				array(
					"name"        => __( 'Validation error text', 'zn_framework' ),
					"description" => __( "Add the text that will appear if the user doesn't check the checkbox.", 'zn_framework' ),
					"id"          => "validation_text",
					"type"        => "textarea",
					"class"       => "zn_full",
				),
			),
		);

		return $options;
	}


	/**
	 * Validate the Registration form checkboxes
	 *
	 * @param array $error_messages The error messages attached to the array so far
	 *
	 * @return array The array containing validation errors if there are present
	 */
	public static function validateRegistration( $error_messages) {
		$gdpr_text = zget_option( 'after_login_texts', 'general_options', false, array() );

		// Don't proceed if the gdpr text is empty
		if ( is_array( $gdpr_text ) && ! empty( $gdpr_text ) ) {
			foreach ( $gdpr_text as $key => $config ) {
				if ( empty( $gdpr_text['text'] ) ) {
					continue;
				}

				$inputId = self::generateId( $key);
				if ( ! isset( $_POST[$inputId] ) ) {
					$error_messages[] = $config['validation_text'];
				}
			}
		}

		return $error_messages;
	}
}

new ZnHg_GDPR();
