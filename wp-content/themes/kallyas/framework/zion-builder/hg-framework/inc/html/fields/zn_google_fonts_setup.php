<?php

/**
 * Will handle the output of the Google Fonts setup option type
 */
class ZnHgFw_Html_Zn_Google_Fonts_Setup extends ZnHgFw_BaseFieldType {

	/**
	 * Holds a refference to the option type
	 *
	 * @var string
	 */
	public $type = 'zn_google_fonts_setup';

	/**
	 * Holds a refference to all Google Fonts
	 *
	 * @var array Google fonts array
	 */
	private $all_fonts;


	/**
	 * Main class init function called from parent constructor
	 *
	 * @see ZnHgFw_BaseFieldType::__construct()
	 *
	 * @return void
	 */
	public function init() {
		$this->all_fonts = ZNHGFW()->getComponent( 'font-manager' )->get_all_google_fonts();
		add_action( 'wp_ajax_znhgfw_html_google_font_add', array( $this, 'ajax_callback' ) );
	}


	/**
	 * Ajax callback
	 *
	 * If success, it will return an object containing the HTML markup for the dynamic options
	 *
	 * @return void
	 */
	public function ajax_callback() {
		check_ajax_referer( 'zn_framework', 'zn_ajax_nonce' );

		$option = json_decode( stripslashes( $_POST['option_schema'] ), true );
		if ( ! isset( $_POST['selected_font'] ) ) {
			wp_send_json_error(array(
				'message' => 'You need to select at least one font',
			));
		}

		wp_send_json_success(array(
			'html_markup' => $this->render_repeater_content( $option, $_POST['selected_font'] ),
		));
	}

	/**
	 * Render
	 *
	 * Will output the option
	 *
	 * @param array $option The option schema
	 * @return string The HTML markup for the option
	 */
	public function render( $option ) {
		$output = '';

		$output .= '<select class="zn_input zn_input_select">';
		$output .= '<option>Please select a font</option>';
		if ( ! empty( $this->all_fonts ) ) {
			foreach ( $this->all_fonts as $key => $font ) {
				$output .= '<option value="' . $font['family'] . '">' . $font['family'] . '</option>';
			}
		}
		$output .= '</select>';
		$output .= '<a class="button button-primary button-large zn_add_gfont" href="#">Add Font</a>';

		$output .= '<div class="zn_group_inner zn_group_container zn_pb_group_content zn_google_fonts_holder" data-baseid="' . $option['id'] . '">';
		if ( ! empty( $option['std'] ) ) {
			foreach ( $option['std'] as $key => $font ) {
				$output .= $this->render_repeater_content( $option, $font );
			}
		}

		// Set the repeater template as template
		ob_start(); ?>
			<script class="znhgfw-repeater-config" type="text/template">
				<?php echo wp_json_encode( $option ); ?>
			</script>
		<?php
		$output .= ob_get_clean();

		$output .= '</div>'; // END .zn_group_container

		return $output;
	}

	/**
	 * Render repeater content
	 *
	 * Will render the dynamic option content
	 *
	 * @param array $schema The option schema
	 * @param array $model The option model ( aka saved value )
	 * @return string The HTML markup for the given schema + model
	 */
	public function render_repeater_content( $schema, $model = array() ) {
		$output = '';

		$uid           = zn_uid();
		$i             = 0;
		$selected_font = ! empty( $model['font_family'] ) ? $model['font_family'] : $model;
		$font_family   = str_replace( ' ', '+', $selected_font );

		$output .= '<div class="zn_group">';
		$output .= '<div class="zn_group_header zn_gradient">';
		$output .= '<h4>' . $this->all_fonts[ $selected_font ]['family'] . '</h4>';
		// START ACTIONS
		$output .= '<div class="zn_group_actions">';
		// DELETE BUTTON
		$output .= '<a class="zn_remove"><span data-toggle="tooltip" data-title="Delete" class="zn_icon_trash"></span></a>';
		// Edit button
		$output .= '<a class="zn_modal_trigger no-scroll" href="#' . $uid . '" data-modal_title="' . $this->all_fonts[ $selected_font ]['family'] . ' font options"><span data-toggle="tooltip" data-title="Edit" class="zn_icon_edit no-scroll" ></span></a>';
		$output .= '</div>'; // END GROUP ACTIONS

		$output .= '</div>'; // END GROUP HEADER

		$output .= '<div id="' . $uid . '" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll">';

		// Fix variants
		$variants = array();
		if ( is_array( $this->all_fonts[ $selected_font ]['variants'] ) ) {
			foreach ( $this->all_fonts[ $selected_font ]['variants'] as $key => $value ) {
				$variants[ $value ] = $value;
			}
		}

		$schema['subelements'] = array(
			array(
				'id'    => 'font_family',
				'name'  => 'Font Family',
				'type'  => 'hidden',
				'std'   => $selected_font,
				'class' => 'zn_hidden',
			),
			array(
				'id'          => 'font_variants',
				'name'        => 'Font variants',
				'description' => 'Here you can select the font variants you want to load.',
				'type'        => 'checkbox',
				'options'     => $variants,
				'class'       => 'zn_full',
			),
		);

		foreach ( $schema['subelements'] as $key => $option ) {

			// SET THE DEFAULT VALUE
			if ( isset( $schema['std'][ $font_family ][ $option['id'] ] ) ) {
				$option['std'] = $schema['std'][ $font_family ][ $option['id'] ];
			}

			// Set the proper id
			$option['id'] = $schema['id'] . '[' . $font_family . '][' . $option['id'] . ']';

			// Generate the options
			$output .= ZNHGFW()->getComponent( 'html' )->zn_render_single_option( $option );
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
