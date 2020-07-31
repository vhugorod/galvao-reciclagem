<?php

/**
 * Will handle the output of the Google Fonts setup option type
 */
class ZnHgFw_Html_Group extends ZnHgFw_BaseFieldType {

	/**
	 * Holds a refference to the option type
	 *
	 * @var string
	 */
	public $type = 'group';


	/**
	 * Main class init function called from parent constructor
	 *
	 * @see ZnHgFw_BaseFieldType::__construct()
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_ajax_znhgfw_html_group_add', array( $this, 'ajax_callback' ) );
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

		if ( empty( $_POST['option_schema'] ) ) {
			wp_send_json_error(array(
				'message' => 'Element type is missing',
			));
		}

		$option = json_decode( stripslashes( $_POST['option_schema'] ), true );
		wp_send_json_success(array(
			'html_markup' => $this->render_repeater_content( $option, $option['std'] ),
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
		$output             = '';
		$uid                = '';
		$number_of_elements = is_array( $option['std'] ) ? count( $option['std'] ) : 0;
		$extra_button_class = '';
		$max_items          = isset( $option['max_items'] ) ? 'data-max_items="' . $option['max_items'] . '"' : '';
		$extra_button_class = ! empty( $max_items ) && $number_of_elements >= $option['max_items'] ? 'zn_add_button_inactive' : '';

		$output .= '<div class="zn_group_inner zn_group_container zn_pb_group_content ' . $extra_button_class . '" data-baseid="' . $option['id'] . '">';

		// ADD THE STD OPTIONS THAT CANNOT BE DELETED
		if ( ! empty( $option['default_std'] ) ) {
			$number_of_std_elements = count( $option['default_std'] );

			for ( $i = 0; $i < $number_of_std_elements; $i++ ) {

				// SET CUSTOM STD IF SET
				$model = array();
				if ( ! empty( $option['default_std'][ $i ]['std'] ) && ! empty( $schema['std'][ $i ] ) ) {
					$model = array_merge( $option['default_std'][ $i ]['std'], $schema['std'][ $i ] );
				} elseif ( ! empty( $option['default_std'][ $i ]['std'] ) ) {
					$model = $option['default_std'][ $i ]['std'];
				}

				$output .= $this->render_repeater_content( $option, $model, $i );
			}
		}

		// IF WE HAVE STANDARD ELEMENTS, CHANGE THE START VALUE
		$start = ! empty( $option['default_std'] ) ? count( $option['default_std'] ) : 0;

		// We do not have any fixed elemenets
		for ( $i = $start; $i < $number_of_elements; $i++ ) {
			$options = array();
			$uid     = zn_uid();

			$output .= $this->render_repeater_content( $option, $option['std'][ $i ], $i );
		}

		$output .= '</div>'; // Close zn_inner
		$output .= '<div class="zn_add_button zn-btn-done" ' . $max_items . '  data-type="' . $option['id'] . '">Add more</div>';

		// Set the repeater template as template
		ob_start(); ?>
			<script class="znhgfw-repeater-config" type="text/template">
				<?php echo wp_json_encode( $option ); ?>
			</script>
		<?php
		$output .= ob_get_clean();

		return $output;
	}

	/**
	 * Render repeater content
	 *
	 * Will render the dynamic option content
	 *
	 * @param array $schema The option schema
	 * @param array $model The option model ( aka saved value )
	 * @param mixed $index The current element in relation to parent
	 * @return string The HTML markup for the given schema + model
	 */
	public function render_repeater_content( $schema, $model = array(), $index = 0 ) {
		$output = '';
		$uid    = zn_uid();

		// GET THE ELEMENT TITLE IF SUPPORTED
		if ( isset( $schema['element_title'] ) && isset( $model[ $schema['element_title'] ] ) ) {
			$title = sanitize_text_field( $model[ $schema['element_title'] ] );

			// Limit the title to 45 characters
			if ( strlen( $title ) > 45 ) {
				$title = substr( $title, 0, 45 ) . '...';
			}

			if ( 'zn_color' === $schema['element_title'] ) {
				$title .= '<span style="background-color:' . $title . '" class="zn-color-preview"></span>';
			}
		} else {
			if ( isset( $schema['element_title'] ) ) {
				$title = $schema['element_title'];

				if ( 'zn_color' === $title ) {
					$title .= '<span style="background-color:' . $title . '" class="zn-color-preview"></span>';
				}
			} else {
				$title = '#' . ( $index + 1 );
			}
		}

		// Added image preview of list
		if ( isset( $schema['element_img'] ) && isset( $model[ $schema['element_img'] ] ) ) {
			$img = sanitize_text_field( $model[ $schema['element_img'] ] );
			if ( ! empty( $img ) ) {
				$title .= '<span style="background-image:url(' . $img . ')" class="zn-dyn-image-preview"></span>';
			}
		}

		$output .= '<div class="zn_group">';

		$output .= '<div class="zn_group_header zn_gradient">';
		$output .= '<h4>' . $title . '</h4>';
		// START ACTIONS
		$output .= '<div class="zn_group_actions">';

		$output .= '<span class="zn_group_actions-counter">' . ( $index + 1 ) . '</span>';

		// DELETE BUTTON
		$output .= '<a class="zn_remove" data-tooltip="' . __( 'Delete', 'zn_framework' ) . '"><span class="zn_icon_trash"></span></a>';

		if ( ! isset( $schema['sortable'] ) || true === $schema['sortable'] ) {
			// RE-ORDER BUTTON
			$output .= '<a class="zn_group_handle" data-tooltip="' . __( 'Move', 'zn_framework' ) . '"><span class="zn_icon_order"></span></a>';
		}

		// Clone button
		$output .= '<a class="zn_clone_button"  data-clone="clone" data-tooltip="' . __( 'Clone', 'zn_framework' ) . '"><span class="zn_icon_clone"></span></a>';
		// Edit button
		$output .= '<a class="zn_modal_trigger zn_icon_edit no-scroll" href="#' . $uid . '" data-modal_title="Element options" data-tooltip="' . __( 'Edit', 'zn_framework' ) . '"><span class="zn_icon_edit"></span></a>';
		$output .= '</div>'; // END .zn_group_actions
		$output .= '</div>'; // END .zn_group_header

		$output .= '<div id="' . $uid . '" class="zn-modal-form zn-modal-group-form zn_hidden no-scroll" >';

		if ( isset( $schema['subelements']['has_tabs'] ) ) {
			unset( $schema['subelements']['has_tabs'] );

			$output .= '<div class="zn-tabs-container">';
			$output .= '<div class="zn-options-tab-header">';
			$tab_num = 0;
			foreach ( $schema['subelements'] as $key => $tab ) {
				$cls = '';
				if ( 0 === $tab_num ) {
					$cls = 'zn-tab-active';
				}
				$output .= '<a href="#" class="' . $cls . '" data-zntab="' . $key . '">' . $tab['title'] . '</a>';
				$tab_num++;
			}

			$output .= '</div>';

			$tab_num = 0;
			foreach ( $schema['subelements'] as $key => $tab ) {
				$cls = '';
				if ( 0 === $tab_num ) {
					$cls = 'zn-tab-active';
				}
				$output .= '<div class="zn-options-tab-content zn-tab-key-' . $key . ' ' . $cls . '">';

				foreach ( $tab['options'] as $key => $value ) {
					$value['is_in_group'] = true;

					// SET THE DEFAULT VALUE
					if ( isset( $model[ $value['id'] ] ) ) {
						$value['std'] = $model[ $value['id'] ];
					}

					// Set the proper id
					$value['id']            = $schema['id'] . '[' . $index . '][' . $value['id'] . ']';
					$value['dependency_id'] = $schema['id'] . '[' . $index . ']';

					// Generate the options
					$output .= ZNHGFW()->getComponent( 'html' )->zn_render_single_option( $value );
				}

				$output .= '</div>';
				$tab_num++;
			}

			$output .= '</div>';

			$schema['subelements']['has_tabs'] = true;
		} else {
			foreach ( $schema['subelements'] as $key => $value ) {
				$value['is_in_group'] = true;

				// SET THE DEFAULT VALUE
				if ( isset( $model[ $value['id'] ] ) ) {
					$value['std'] = $model[ $value['id'] ];
				}

				// Set the proper id
				$value['id']            = $schema['id'] . '[' . $index . '][' . $value['id'] . ']';
				$value['dependency_id'] = $schema['id'] . '[' . $index . ']';

				// Generate the options
				$output .= ZNHGFW()->getComponent( 'html' )->zn_render_single_option( $value );
			}
		}

		$output .= '</div>';

		$output .= '</div>'; // Close zn_group

		return $output;
	}
}
