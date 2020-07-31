<?php if ( !defined( 'WPINC' ) ) {
	die;
}

class ZnHgFw_Metabox {

	private $_meta_options     = array();
	private $_active_post_type = '';
	private $_meta_locations   = array();

	private $_htmlFw;

	function __construct() {

		global $pagenow;

		if ( empty( $pagenow ) ) {
			return;
		}

		$pages = apply_filters( 'znhgfw_metabox_screens', array( 'post-new.php', 'post.php' ) );

		if ( in_array( $pagenow, $pages ) ) {
			$this->_htmlFw = ZNHGFW()->getComponent( 'html' );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
			add_action( 'save_post', array( $this, 'save_post' ) );
			do_action( 'znhgfw_metabox_init' );
		}
	}

	/**
	 * Allow users to add metaboxes
	 */
	function load_metabox_config( $post_type ) {
		$this->_active_post_type = $post_type;
		do_action( 'znhgfw_register_metabox_config_' . $post_type, $this );
		do_action( 'znhgfw_register_metabox_locations', $this );
		do_action( 'znhgfw_register_metabox_options', $this );
	}

	function register_meta_location( $location_id, $location_config ) {
		// Add the metabox location if it's config allows it
		if ( ( is_array( $location_config ) && in_array( $this->_active_post_type, $location_config[ 'post_type' ] ) ) || 'all' === $location_config ) {
			$this->_meta_locations[ $location_id ] = $location_config;
		}
	}


	function unregister_meta_location( $location_id ) {
		if ( isset($this->_meta_locations[ $location_id ]) && !empty( $this->_meta_locations[ $location_id ] ) ) {
			unset( $this->_meta_locations[ $location_id ] );
		}
	}


	function register_meta_option( $option_config ) {
		if( is_array( $this->_meta_locations ) && ! empty( $this->_meta_locations )) {
			$value = array_intersect( array_keys( $this->_meta_locations ), $option_config[ 'slug' ] );
			if ( !empty( $value ) ) {
				$this->_meta_options[ $option_config[ 'id' ] ] = $option_config;
			}
		}
	}


	function unregister_metabox( $option_id ) {
		if ( isset($this->_meta_options[ $option_id ]) && !empty( $this->_meta_options[ $option_id ] ) ) {
			unset( $this->_meta_options[ $option_id ] );
		}
	}


	function add_meta_boxes( $post_type, $post ) {

		// Load all metabox config
		$this->load_metabox_config( $post_type );

		if( ! empty( $this->_meta_locations )) {
			foreach ( $this->_meta_locations as $metabox_location_id => $metabox_config ) {
				add_meta_box( $metabox_location_id, $metabox_config[ 'title' ], array( $this, 'render_metabox' ), $metabox_config[ 'post_type' ], $metabox_config[ 'context' ], $metabox_config[ 'priority' ], array( 'metabox_location_id' => $metabox_location_id ) );
			}
		}
	}

	function render_metabox( $post, $metabox ) {

		if( ! is_array( $this->_meta_options ) || empty( $this->_meta_options ) ){
			return false;
		}

		echo '<div class="znhgfw-metabox-container ' . $metabox[ 'args' ][ 'metabox_location_id' ] . '">';
			foreach ( $this->_meta_options as $key => $options_config ) {
				if ( in_array( $metabox[ 'id' ], $options_config[ 'slug' ] ) ) {
					$option_saved_value = get_post_meta( $post->ID, $options_config[ 'id' ], true );
					if ( !empty( $option_saved_value ) ) {
						$options_config[ 'std' ] = $option_saved_value;
					}

					echo $this->_htmlFw->zn_render_single_option( $options_config );
				}
			}

			// Add a nonce field so we can check it on save
			echo wp_nonce_field( 'znhgfw_metafield_nonce', 'znhgfw_metafield_nonce' );
		echo '</div>';

	}

	function save_post( $post_id ) {

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we don't want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Verify the nonce before proceeding.
		if ( !isset( $_POST[ 'znhgfw_metafield_nonce' ] ) || !wp_verify_nonce( $_POST[ 'znhgfw_metafield_nonce' ], 'znhgfw_metafield_nonce' ) ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Get current post type
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : null;

		if( null === $post_type ){
			$post_type = get_post_type( $post_id );
		}
		$this->load_metabox_config( $post_type );

		// Allow others to hook here
		do_action( 'znhgtfw:save_metaboxes', $this->_meta_options, $post_id );

		foreach ( $this->_meta_options as $key => $options_config ) {

			// Check to see if we have the option id inside $_POST
			if ( isset ( $_POST[ $options_config[ 'id' ] ] ) ) {
				update_post_meta( $post_id, $options_config[ 'id' ], $_POST[ $options_config[ 'id' ] ] );
			}
			elseif( isset( $options_config['save_callback'] ) ){
				call_user_func( $options_config['save_callback'], $post_id, $options_config );
			}
		}
		return $post_id;
	}

}

return new ZnHgFw_Metabox();
