<?php if ( !defined( 'WPINC' ) ) {
	die;
}

class ZnHgFw_TermMeta {
	// Holds the default options. They can be overridden through the "zn_termmeta_elements" filter
	private $_term_options = array();
	// Holds the reference to the HTML component
	private $_htmlFw = null;

	public function __construct() {
		global $pagenow;

		if ( empty( $pagenow ) ) {
			return;
		}

		$pages = apply_filters( 'znhgfw_termmeta_screens', array( 'edit-tags.php', 'term.php' ) );
		if ( in_array( $pagenow, $pages ) ) {
			// We need to have access to the taxonomy slug
			if ( isset( $_REQUEST[ 'taxonomy' ] ) && !empty( $_REQUEST[ 'taxonomy' ] ) ) {

				// Get an instance of the HTML classs
				$this->_htmlFw = ZNHGFW()->getComponent( 'html' );

				$taxonomy = sanitize_text_field( $_REQUEST[ 'taxonomy' ] );
				add_action( $taxonomy . '_edit_form', array( $this, 'render_options' ), 41, 2 );
				add_action( $taxonomy . '_add_form_fields', array( $this, 'render_options' ), 41, 2 );
				add_action( 'edited_term', array( $this, 'save_options' ), 10, 3 );
				add_action( 'create_term', array( $this, 'save_options' ), 10, 3 );
			}
		}
	}

	public function load_options_config( $taxonomy ) {

		$this->_active_taxonomy = $taxonomy;
		do_action( 'znhgfw_register_termmeta_config_' . $taxonomy, $this );
		do_action( 'znhgfw_register_termmeta_options', $this );

	}

	function register_termmeta_option( $option_config ) {
		if( in_array( $this->_active_taxonomy, $option_config['taxonomy'] ) ){
			$this->_term_options[ $option_config[ 'id' ] ] = $option_config;
		}
	}


	function unregister_termmeta_option( $option_id ) {
		if ( !empty( $this->_term_options[ $option_id ] ) ) {
			unset( $this->_term_options[ $option_id ] );
		}
	}


	public function render_options( $tag, $taxonomy = false ) {
		global $pagenow;

		// On edit-tags.php screen we receive the taxonomy as first parameter
		if ( is_string( $tag ) && !$taxonomy && $pagenow == "edit-tags.php" ) {
			$taxonomy = $tag;
		}

		// Load all needed options
		$this->load_options_config( $taxonomy );

		if ( ! is_array( $this->_term_options ) ) {
			return;
		}

		$tax_slug = ( ( is_object( $tag ) && isset( $tag->slug ) ) ? $tag->slug : $tag );
		// Render the options
		echo '<div class="znhgfw-metabox-container ' . $tax_slug . '">';

			foreach ( $this->_term_options as $option_args ) {
				// Continue if this option shouldn't be shown on this page
				if ( ! in_array( $taxonomy, $option_args[ 'taxonomy' ] ) ) {
					continue;
				}

				$saved_value = isset( $tag->term_id ) ? get_term_meta( $tag->term_id, $option_args[ 'id' ], true ) : '';


				if ( !empty( $saved_value ) ) {
					$option_args[ 'std' ] = $saved_value;
				}
				echo $this->_htmlFw->zn_render_single_option( $option_args );
			}

			echo wp_nonce_field( 'znhgfw_termmeta_nonce', 'znhgfw_termmeta_nonce', false, false );
		echo '</div>';

	}

	public function save_options( $term_id, $tt_id, $taxonomy ) {


		// verify if this is an auto save routine.
		// If it is, our form has not been submitted, so we don't want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify the nonce before proceeding.
		if ( !isset( $_POST[ 'znhgfw_termmeta_nonce' ] ) || !wp_verify_nonce( $_POST[ 'znhgfw_termmeta_nonce' ], 'znhgfw_termmeta_nonce' ) ) {
			return $term_id;
		}

		// Load all needed options
		$this->load_options_config( $taxonomy );

		foreach ( $this->_term_options as $option_args ) {
			if ( isset ( $_POST[ $option_args[ 'id' ] ] ) ) {
				update_term_meta( $term_id, $option_args[ 'id' ], $_POST[ $option_args[ 'id' ] ] );
			}
		}

	}
}

return new ZnHgFw_TermMeta();
