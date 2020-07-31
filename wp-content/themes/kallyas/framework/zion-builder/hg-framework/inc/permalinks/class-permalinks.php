<?php

class ZnHgFw_Permalinks {
	function __construct(){
		add_action( 'admin_init', array( $this, 'zn_permalink_settings_init' ) );
		add_action( 'admin_init', array( $this, 'zn_permalink_settings_save' ) );
	}

	function zn_permalink_settings_save()
	{
		if ( !is_admin() )
		{
			return;
		}

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST[ 'zn_permalinks' ] ) /*|| isset( $_POST['zn_portfolio_item_slug_input'] ) */ )
		{
			$permalinks = $_POST[ 'zn_permalinks' ];
			update_option( 'zn_permalinks', $permalinks );
			flush_rewrite_rules();
		}
	}



	function zn_permalink_settings_init() {

		$post_types = array();
		$taxonomies = array();
		$this->zn_allowed_post_types = apply_filters( 'zn_allowed_post_types', $post_types );
		$this->zn_allowed_taxonomies = apply_filters( 'zn_allowed_taxonomies', $taxonomies );

		foreach ( $this->zn_allowed_post_types as $id => $name )
		{

			$post_type_section_id = 'zn-' . $id . '-permalink';

			// SECTION : UNIQUE ID, NAME, CALLBACK, SETTINGS PAGE
			add_settings_section( $post_type_section_id, $name . ' Slugs', '', 'permalink' );

			$this->add_settings_field( $id, $name, $post_type_section_id );

			if ( !empty( $this->zn_allowed_taxonomies[ $id ] ) )
			{

				$current_taxonomies = $this->zn_allowed_taxonomies[ $id ];
				foreach ( $current_taxonomies as $key => $taxonomy )
				{
					$this->add_settings_field( $taxonomy[ 'id' ], $taxonomy[ 'name' ], $post_type_section_id );
				}

			}

		}
	}

	function add_settings_field( $id, $name, $section )
	{

		// Add Slug option
		add_settings_field(
			$id,        // id
			$name . ' item slug',    // setting title
			array( &$this, 'permalink_callback' ),  // display callback
			'permalink',                                // settings page
			$section,                                // settings section
			array(
				'id' => $id
			)
		);
	}


	static public function permalink_callback( $field )
	{

		$permalinks = get_option( 'zn_permalinks' );

		?>
		<input name="zn_permalinks[<?php echo esc_attr( $field[ 'id' ] ); ?>]" type="text" class="regular-text code"
			   value="<?php if ( isset( $permalinks[ $field[ 'id' ] ] ) )
			   {
				   echo esc_attr( $permalinks[ $field[ 'id' ] ] );
			   } ?>" placeholder="<?php echo esc_attr( $field[ 'id' ] ); ?>"/>
		<?php
	}

}

return new ZnHgFw_Permalinks();
