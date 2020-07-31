<?php if(! defined('ABSPATH')){ return; }

// We need to load the default menu walker so we can extend it
	require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' ); // Load all the nav menu interface functions

	add_action( 'admin_enqueue_scripts', 'znfw_wp_admin_nav_menus_css' );
	add_filter( 'wp_edit_nav_menu_walker', 'znfw_modify_backend_walker', 100);
	add_action( 'wp_nav_menu_item_custom_fields', 'znfw_add_menu_button_fields', 10, 4 );
	add_action( 'wp_update_nav_menu_item', 'znfw_update_menu', 100, 3);

	/*
	* Add CSS
	*/
	function znfw_wp_admin_nav_menus_css($hook)
	{
		// Check the hook so that the .css is only added to the .php file where we need it
		if( 'nav-menus.php' != $hook ) {
			return;
		}

		wp_enqueue_style( 'znfw-wp-admin-nav-menus-css', ZNHGTFW()->getFwUrl( 'assets/css/admin_mega_menu.css' ) );
		wp_enqueue_script('jquery');

		wp_enqueue_media();

		wp_enqueue_script( 'zn-mega-menu', ZNHGTFW()->getFwUrl( 'assets/js/admin-mega-menu.js' ),array( 'jquery' ), ZNHGTFW()->getVersion(), true );
		wp_localize_script('zn-mega-menu', 'ZnHgAdminMegaMenu', array(
            'popupTitle' => esc_html__( 'Select image', 'zn_framework' ),
        ));
	}


	/*
	 * SAVE / UPDATE CUSTOM OPTIONS
	 * @param int $menu_id
	 * @param int $menu_item_db
	 */
	function znfw_update_menu( $menu_id, $menu_item_db ) {
		$fields = array(
			'menu_item_zn_mega_menu_enable',
			'menu_item_zn_mega_menu_headers',
			'menu_item_zn_mega_menu_label',
			'menu_item_zn_mega_menu_smart_area',
			'menu_item_zn_mega_menu_bg_image'
		);


		foreach ( $fields as $key )
		{
			if(!isset($_REQUEST[$key][$menu_item_db]))
			{
				$_REQUEST[$key][$menu_item_db] = "";
			}

			$value = $_REQUEST[$key][$menu_item_db];
			update_post_meta( $menu_item_db, '_'.$key , $value );

		}
	}

	function znfw_modify_backend_walker( $walker ){
		return 'ZnBackendWalker';
	}

	function znfw_add_menu_button_fields( $item_id, $item, $depth, $args ){
		$item_id = esc_attr( $item->ID );
		// LABEL
		$key = 'menu_item_zn_mega_menu_label';
		$value = get_post_meta( $item_id, '_'.$key, true);
		?>
		<p class="field-mega-menu-badge description description-wide">
			<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
				<?php esc_html_e( 'Label' , 'zn_framework' ); ?><br />
				<input type="text" id="edit-menu-item-attr-label-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-label" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $value ); ?>" />
			</label>
		</p>

		<?php
			// USE AS MEGAMENU
			$title = esc_html__( 'Use as Mega Menu ?' , 'zn_framework' );
			$key = 'menu_item_zn_mega_menu_enable';
			$value = get_post_meta( $item_id, '_'.$key, true);
            $useAsMegaMenu = false;
			if($value != "") {
			    $value = "checked='checked'";
			    $useAsMegaMenu = true;
			}
			//#! The ID of the related Image preview option
			$dependencyImageID = 'enable-mega-menu-bg-image-'.esc_attr($item_id);
		?>
		<p class="field-enable-mega-menu description description-wide">
			<label for="enable-mega-menu-<?php echo esc_attr($item_id); ?>">
				<input id="enable-mega-menu-<?php echo esc_attr($item_id); ?>" type="checkbox" class="menu-item-checkbox znkl-mega-menu-enable" <?php echo ''.$value; ?>
                       name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]"
                       data-target="<?php echo esc_attr($dependencyImageID);?>"
                />
				<?php echo ''.$title; ?>
			</label>
		</p>

        <?php
        /*
         * Use background image
         */
		$key = 'menu_item_zn_mega_menu_bg_image';
		$saved_value = ( $useAsMegaMenu ? get_post_meta( $item_id, '_'.$key, true) : null );
		$imageSrc = '';
		$_image = null;
        if( ! empty($saved_value)) {
	        $_image = wp_get_attachment_image_src( $saved_value );
	        if( ! empty($_image[0])){
	            $imageSrc = $_image['0'];
            }
        }
		$cssClass = ( $useAsMegaMenu ? 'is-visible' : '');
		?>
        <div class="field-add-image-mega-menu description description-wide <?php echo esc_attr( $cssClass );?>" data-ref="<?php echo esc_attr($dependencyImageID); ?>">
            <label for="<?php echo esc_attr($dependencyImageID); ?>">
				<?php esc_html_e( 'Select a background image' , 'zn_framework' ); ?>
            </label>
            <div class="zn-hg-image-upload-mega-menu-wrapper">
                    <div class="zn-hg-image-preview-wrapper">
                        <img src="<?php echo esc_url( $imageSrc );?>" class="zn-hg-image-preview"/>
                        <span class="zn-hg-deleteImagePreview-button"><?php esc_html_e( 'x', 'zn_framework' );?></span>
                    </div>
                    <input type="button"
                           class="zn-hg-image-upload-button"
                           value="<?php esc_attr_e( 'Select image', 'zn_framework' );?>"
                           id="<?php echo esc_attr($dependencyImageID); ?>"/>
                    <input type="hidden"
                           class="zn-hg-bg-image-input-h"
                           name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($saved_value);?>"/>
                </div>
        </div>


		<?php
		// Smart area
		$title = esc_html__( 'Use a smart area ?' , 'zn_framework' );
		$option_key = 'menu_item_zn_mega_menu_smart_area';
		$saved_value = get_post_meta( $item_id, '_'.$option_key, true);

		$pb_templates_options = ZnMegaMenu::getSmartAreas();

		?>
        <p class="field-enable-mega-menu-smart-area description description-wide">
            <label for="enable-mega-menu-smart-area-<?php echo esc_attr($item_id); ?>">
				<?php echo ''.$title; ?>
            </label>
            <select id="enable-mega-menu-smart-area-<?php echo esc_attr($item_id); ?>" class="znkl-mega-menu-smart-area" name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($item_id); ?>]">
				<?php
				foreach ($pb_templates_options as $key => $value) {
					echo '<option value="'.$key.'" '.selected( $key, $saved_value, false ).'>'.$value.'</option>';
				}
				?>
            </select>
        </p>

		<?php
			$title = esc_html__( 'Hide menu header' , 'zn_framework' );
			$key = 'menu_item_zn_mega_menu_headers';
			$value = get_post_meta( $item_id, '_'.$key, true);

			if($value != "") $value = "checked='checked'";
		?>
		<p class="field-enable-mega-menu-headers description description-wide">
			<label for="enable-mega-menu-headers-<?php echo esc_attr($item_id); ?>">
				<input id="enable-mega-menu-headers-<?php echo esc_attr($item_id); ?>" type="checkbox" class="menu-item-checkbox" <?php echo ''.$value; ?> name="<?php echo esc_attr($key); ?>[<?php echo esc_attr($item_id); ?>]">
				<?php echo ''.$title; ?>
			</label>
		</p>

		<?php
	}

	/**
	 * Create HTML list of nav menu input items. ( COPIED FROM DEFAULT WALKER )
	 *
	 * @package WordPress
	 * @since 3.0.0
	 * @uses Walker_Nav_Menu
	 */
	class ZnBackendWalker extends Walker_Nav_Menu_Edit {

		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
		{

			$item_output = '';
			parent::start_el( $item_output, $item, $depth, $args, $id );

			$position = '<p class="field-move';
			$extra = $this->get_fields( $item, $depth, $args, $id );

			$wp47Position = '<fieldset class="field-move';

			$item_output = str_replace( $position, $extra . $position, $item_output );
			$output .= str_replace( $wp47Position, $extra . $wp47Position, $item_output );
		}

		function get_fields( $item, $depth, $args = array(), $id = 0 )
		{
			ob_start();

			// conform to https://core.trac.wordpress.org/attachment/ticket/14414/nav_menu_custom_fields.patch
			do_action( 'wp_nav_menu_item_custom_fields', $id, $item, $depth, $args );

			return ob_get_clean();
		}

	} // Walker_Nav_Menu_Edit
