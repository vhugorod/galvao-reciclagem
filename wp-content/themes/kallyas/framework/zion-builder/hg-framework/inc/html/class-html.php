<?php if(! defined('ABSPATH')){ return; }

	class ZnHgFw_Options {

		// OLD VAR...
		var $data;

		/**
		 *	Will hold all registered option types
		 */
		private $_optionTypes = array();

		/**
		 *	Will hold all registered forms
		 */
		private $_registeredForms = array();

		/**
		 *	Holds all instantiated forms
		 */
		private $_formInstances = array();

		private $_dataSources = array();

		function __construct(){
			// Enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Form save
			// add_action( 'wp_ajax_znhgfw_html_save', array( $this, 'formSave' ) );

			// Register default option types
			$this->registerStandardOptionTypes();

			// Let others know we're in bussiness
			do_action( 'znhgfw_html_init', $this );
		}

		function registerStandardOptionTypes(){
			//  Load base class
			require( trailingslashit( dirname( __FILE__ ) ) . 'class-base-field.php' );
			require( trailingslashit( dirname( __FILE__ ) ) . 'class-base-form.php' );
			require( trailingslashit( dirname( __FILE__ ) ) . 'class-base-data-source.php' );

			$path = trailingslashit( trailingslashit( dirname( __FILE__ ) ) . 'fields' );
			$files = glob( $path . '*.php' );
			if( is_array( $files ) ){
				foreach ( $files as $file ) {

					// Load the file
					require( $file );

					// Get the option type name
					$optionType = str_replace( '.php', '', basename( $file ) );
					// Get the option type class name
					$optionClass = 'ZnHgFw_Html_' . ucwords( $optionType );

					// Register the option
					$this->registerOptionType( new $optionClass( $this ) );
				}
			}


			// Register data sources
			$path = trailingslashit( trailingslashit( dirname( __FILE__ ) ) . 'data_source' );
			$files = glob( $path . '*.php' );
			foreach ( $files as $file ) {
				$optionType = str_replace( '.php', '', basename( $file ) );
				$this->registerDataSource( require( $file ) );
			}

			// Register default form type
			// TODO : Move this form to kallyas
			require( trailingslashit( dirname( __FILE__ ) ) . 'forms/theme_form.php' );

		}

		function registerOptionType( $optionClassInstance ){
			$this->_optionTypes[$optionClassInstance->getType()] = $optionClassInstance;
		}

		function unregisterOptionType( $optionId ){
			if( ! empty( $this->_optionTypes[$optionId] ) ){
				unset( $this->_optionTypes[$optionId] );
			}
		}


		/**
		 * Registers a data source type. For example, get all pages
		 * @param  object $optionClass The data source type Class
		 * @return void
		 */
		function registerDataSource( $optionClass ){
			if( ! is_subclass_of( $optionClass, 'ZnHgFw_BaseDataSource' ) ){
				trigger_error( __CLASS__ . 'registerDataSource accepts only a child of ZnHgFw_BaseDataSource class', E_USER_ERROR );
			}

			// Register the data source class
			$this->_dataSources[$optionClass->dataSourceType] = $optionClass;
		}


		/**
		 * Will return the data source for an option
		 * @param  [type] $dataSourceType [description]
		 * @return [type]                 [description]
		 */
		function getDataSource( $dataSourceType ){
			if( ! empty( $this->_dataSources[$dataSourceType] ) ){
				return $this->_dataSources[$dataSourceType]->getSource();
			}

			return array();
		}

		function addForm( $formId, $config = array() ){

			// Check to see if we got a form type instance
			if ( $formId instanceof ZnHgFw_BaseFormType ) {
				$formInstance = $formId;
			} else {
				$formInstance = new ZnHgFw_BaseFormType( $formId, $config );
			}

			$this->_formInstances[$formInstance->id] = $formInstance;
		}

		function renderForm( $formId ){
			// var_dump( $this->_formInstances );
			if( ! empty( $this->_formInstances[$formId] ) ){
				return $this->_formInstances[$formId]->render();
			}
		}

		function renderOption( $optionConfig ){

			// Check if this is a valid option type
			if( ! isset( $this->_optionTypes[$optionConfig['type']] ) ){
				$message = '<div class="znhgfw-error-message">';
				$message .= sprintf(__( 'The option type (%s) is missing or we don\'t have any knowledge of this option type', 'zn_framework' ), $optionConfig['type']);
				$message .= '</div>';
				return $message;
			}

			// Check to see if the option has a data source
			if( ! empty( $optionConfig['data-source'] ) ){
				$optionConfig['options'] = $this->getDataSource( $optionConfig['data-source'] );
			}

			return $this->_optionTypes[$optionConfig['type']]->_render( $optionConfig );

		}

		function enqueue_scripts(){
			// Will load all scripts
			// START OLD SCRIPTS
			do_action( 'znfw_scripts' );

			// STYLES
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'zn_html_css', ZNHGFW()->getFwUrl('assets/dist/css/zn_html_css.css') );

			wp_enqueue_script( 'wp-color-picker-alpha', ZNHGFW()->getFwUrl( 'assets/dist/js/wp-color-picker-alpha.js' ), array( 'wp-color-picker' ), ZNHGFW()->getVersion() );

			// HTML SCRIPTS
			wp_enqueue_script( 'jquery-ui-slider' ); // HTML
			wp_enqueue_script( 'jquery-ui-button' ); // HTML
			wp_enqueue_script( 'jquery-ui-sortable' ); // HTML + PB
			wp_enqueue_script( 'jquery-ui-datepicker' ); // HTML

			wp_enqueue_media();

			// Add a dummy editor so we can use functions like wplink
			add_action( 'wp_footer', array( $this, 'add_dummy_editor' ), 1 );
			add_action( 'admin_footer', array( $this, 'add_dummy_editor' ), 1 );

			wp_enqueue_script( 'zn_timepicker', ZNHGFW()->getFwUrl('assets/dist/js/jquery.timepicker.min.js'),array( 'jquery' ), ZNHGFW()->getVersion(), true );
			wp_enqueue_script( 'zn_modal', ZNHGFW()->getFwUrl('assets/dist/js/zn_modal.js'),array( 'jquery' ), ZNHGFW()->getVersion(),true );
			wp_enqueue_script( 'zn_media', ZNHGFW()->getFwUrl('assets/dist/js/zn_media.js'),array( 'jquery' ), ZNHGFW()->getVersion(),true );
			wp_enqueue_script( 'zn_ace', ZNHGFW()->getFwUrl('assets/dist/js/src-min-noconflict/ace.js'),array( 'jquery' ), ZNHGFW()->getVersion(),true );

			wp_enqueue_script( 'zn_html_script', ZNHGFW()->getFwUrl('assets/dist/js/zn_html_script.js'),array( 'jquery' ), ZNHGFW()->getVersion(),true );
			wp_localize_script( 'zn_html_script', 'ZnAjax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'zn_framework' ),
				'debug' => ZNHGFW()->isDebug(),
			));

			// END OLD SCRIPTS
			// Load HTML styles
			wp_enqueue_style( 'znhgfw-html-style', ZNHGFW()->getFwUrl( 'assets/dist/css/html.css' ), array(), ZNHGFW()->getVersion(), 'all' );

			// Load all option types extra scripts
			foreach( $this->_optionTypes as $optionType ){
				$optionType->scripts();
			}

			foreach( $this->_formInstances as $formInstance ){
				$formInstance->scripts();
			}

		}

		// TODO : REMOVE THIS ?
		function add_dummy_editor(){
			echo '<div class="zn_hidden">';
				wp_editor( 'dummy_text', 'zn_dummy_editor_id' );
			echo '</div>';
		}

		// function formSave(){
		//
		// 	// check nonces and other security stuff
		// 	if ( ! is_user_logged_in() ) {
		// 		wp_send_json_error( 'unauthenticated' );
		// 	}
		//
		// 	do_action( 'znhgfw_html_form_save' );
		//
		// 	// Perform save action on all forms
		// 	$formId = ! empty( $_POST['formId'] ) ? $_POST['formId'] : false;
		//
		// 	// Bail if we don't have a form id
		// 	if( ! $formId ){
		// 		wp_send_json_error('form_id_missing');
		// 	}
		//
		// 	// Let others hook into this save form
		// 	do_action( 'znhgfw_html_form_save_'. $formId );
		// 	die();
		// }
// OLD CODE

		function zn_render_single_option($option) {
			$defaults = array(
				'class' => '',
				'placeholder' => '',
				'std' => '',
				'supports' => '',
				'show_blank' => false,
			);

			// Check if we can render this option
			if(isset( $option['can_show'] ) && ! $option['can_show'] ){
				return;
			}

			// Sanitize fields
			$option = wp_parse_args( $option, $defaults );

			$dynamic_start = ( ! isset( $option['dynamic'] ) ) ? $this->zn_render_option_start($option) : '';
			$dynamic_end = ( ! isset( $option['dynamic'] ) ) ? $this->zn_render_option_end($option) : '';

			if( method_exists( $this, $option['type'] ) ){

				//[[Fixes: #763
				$result = call_user_func(array($this, $option['type']), $option);
				return sprintf('%s%s%s',$dynamic_start,$result,$dynamic_end );
			}
			else{
				return sprintf('%s%s%s',$dynamic_start,$this->renderOption( $option ),$dynamic_end );
			}
		}

		function zn_render_option_start($option) {
			$output = '';
			$class = '';
			// SHOW THE TITLE
			if ($option['type'] == 'group' ){
				$class = 'zn_group_container zn_full';
			}

			$data_atts = '';
			if ( isset( $option['dependency'] ) ) {

				$dependencies = array();



				// Special case when we only have one dependency
				// This is for old options when multiple dependencies were not available
				if( ! empty( $option['dependency']['element'] ) ){

					// Set the proper dependency element name
					if( !empty( $option['is_in_group'] ) ){
						foreach ( $option['dependency'] as $key => &$value) {
							if( $key === 'element' ){
								$value = $option['dependency_id'].'['.$value.']';
							}
						}
					}

					$dependencies[] = $option['dependency'];
				}
				else{

					// Set the proper dependency element name
					if( !empty( $option['is_in_group'] ) ){
						foreach ( $option['dependency'] as $key => &$dependency) {
							foreach ($dependency as $key => &$value) {
								if( $key === 'element' ){
									$value = $option['dependency_id'].'['.$value.']';
								}
							}
						}
					}

					$dependencies = $option['dependency'];
				}

				$config = json_encode( $dependencies );
				$data_atts = " data-dependency='{$config}' ";

			}

			/**
			 * Check if the options change needs to be done live
			 * TYPE : CSS , CLASS
			 */
			if ( isset( $option['live'] ) ) {

				if( !empty( $option['is_in_group'] ) ){
					$option['live']['is_in_group'] = true;
				}

				$live_config = json_encode( $option['live'] );
				$data_atts .= " data-live_setup='{$live_config}'";
				$class .= ' zn_live_change ';

			}

			$output .= '<div class="zn_option_container '.$option['class'].' '.$class.' clearfix" data-optionid="'.$option['id'].'" '.$data_atts.'>';

			if ( $option['type'] != 'hidden' && !$option['show_blank'] ) {
				// Add a label for livechanging options
				$live_text = '';
				if ( isset( $option['live'] ) && !empty( $option['name'] ) ) {
					$live_text = '<span class="zn_live_label">live</span>';
				}
				if ( isset( $option['deprecated'] ) && !empty( $option['name'] ) ) {
					$live_text = '<span class="zn_live_label zn_live_label--deprecated">deprecated</span>';
				}
				if ( !empty( $option['name'] ) ) {
					$output .= '<h4>'.$option['name'].' '.$live_text.'</h4>';
				}

			}

			if( !empty($option['description'] ) && !$option['show_blank'] ) {

				$output .= '<p class="zn_option_desc">';
				$output .= $option['description'];
				$output .= '</p>';

			}

			$output .= '<div class="zn_option_content zn_class_'.$option['type'].'">';

			return $output;
		}

		function zn_render_option_end($option){
			return '</div></div>';
		}



		/*--------------------------------------------------------------------------------------------------
			Start custom code option
		--------------------------------------------------------------------------------------------------*/
			function custom_code( $option, $stripslashes = true ) {

				$editor_type = isset( $option['editor_type'] ) ? $option['editor_type'] : 'css';
				if( $stripslashes ){
					$option['std'] = htmlspecialchars( stripslashes ( $option['std'] ) );
				}
				else{
					$option['std'] = htmlspecialchars( $option['std'] );
				}

				$output = '<div class="zn_code_input" id="zn_code_editor_'.$option['id'].'" data-editor_type="'.$editor_type.'">'.$option['std'] .'</div>';
				$output .= '<textarea class="zn_code_input_textarea zn_hidden" id="'.$option['id'].'" name="'.$option['id'].'">'.$option['std'].'</textarea>';
				return $output;
			}

		/*--------------------------------------------------------------------------------------------------
			Custom HTML
		--------------------------------------------------------------------------------------------------*/
			function custom_html( $option ) {
				// $option['std'] = htmlspecialchars( stripslashes ( $option['std'] ) );
				return $this->custom_code( $option );
			}
/*--------------------------------------------------------------------------------------------------
	Start Custom css option
--------------------------------------------------------------------------------------------------*/
	function custom_css($option) {

		$option['std'] = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', '' );
		return $this->custom_code( $option, false );

	}

/*--------------------------------------------------------------------------------------------------
	Start Custom css option
--------------------------------------------------------------------------------------------------*/
	function custom_js($option) {

		$option['std'] = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js', '' );
		return $this->custom_code( $option );

	}


/*--------------------------------------------------------------------------------------------------
	Start Gallery Element
--------------------------------------------------------------------------------------------------*/
	function gallery ( $option ) {

		// FOR GALLERY
		$defaults = array(
			'media_type' => 'image_gallery',
			'insert_title' => 'Insert gallery', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit gallery', // The text that will appear as the main option button for adding images
			'type' => 'image', // The media type : image, video, etc
			'value_type' => 'id', // What to return - url, id
			'state' => 'gallery-library', // The media manager state
			'frame' => 'post', // The media manager frame
			'class' => 'zn-media-gallery media-frame', // The media manager state
		);

		// Set the data
		$option['data'] = !empty( $option['data'] ) ? wp_parse_args( $option['data'], $defaults ) : $defaults;
		$option['preview_holder'] = 'No video selected';

		if ( !empty( $option['std'] ) ) {
			$saved_images = !empty( $option['std'] ) ? explode( ',', $option['std'] ) : array();
			$option['preview_holder'] = self::get_media_preview( $saved_images );
		}

		return $this->zn_media( $option );
	}

	// Returns the HTML needed for the gallery type option
	static function get_media_preview( $images ) {
		$images_holder = '';
		foreach ( $images as $image ) {
			$image_url = wp_get_attachment_image_src( $image, 'thumbnail' );
			$images_holder .= '<span class="zn-media-gallery-preview-image"><img src="'.$image_url[0].'" /></span>';
		}

		return $images_holder;
	}


	/**
	 * Generates a video element
	 * @param type $option
	 * @return string
	 */
	function video_upload( $option ) {

		// FOR Video upload
		$defaults = array(
			'media_type' => 'html5video', // The text that will appear on the inser button from the media manager
			'insert_title' => 'Select video', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'type' => 'video', // The media type : image, video, etc
			'state' => 'video-details', // The media manager state
			'frame' => 'video', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
			'class' => 'zn-media-video media-frame', // A css class that will be applied to the modal
		);

		// Set the data
		$option['data'] = !empty( $option['data'] ) ? wp_parse_args( $option['data'], $defaults ) : $defaults;
		$option['std'] = stripslashes( $option['std'] );
		$saved_video_settings = json_decode( $option['std'], true );
		$option['preview_holder'] = 'No video selected';

		// Build the preview holder
		if ( !empty( $saved_video_settings['mp4'] ) || !empty( $saved_video_settings['ogv'] ) || !empty( $saved_video_settings['webm'] ) ) {
			$option['preview_holder'] = '<video controls>';

				// Add the mp4 string if the user selected an mp4
				if ( !empty( $saved_video_settings['mp4'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['mp4'].'" type="video/mp4">';
				}

				if ( !empty( $saved_video_settings['ogv'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['ogv'].'" type="video/ogg">';
				}

				if ( !empty( $saved_video_settings['webm'] ) ){
					$option['preview_holder'] .= '<source src="'.$saved_video_settings['webm'].'" type="video/webm">';
				}

			$option['preview_holder'] .= '</video>';
		}

		return $this->zn_media( $option );
	}

	/**
	 * General WP media select window
	 * @param type $option
	 * @return type
	 */
	function media_upload( $option ){
		// FOR GENERAL UPLOADS
		$defaults = array(
			'media_type' => 'media_field_upload', // The text that will appear on the inser button from the media manager
			'insert_title' => 'Select video', // The text that will appear on the inser button from the media manager
			'button_title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'title' => 'Add / Edit video', // The text that will appear as the main option button for adding images
			'type' => 'image', // The media type : image, video, etc
			'state' => 'library', // The media manager state
			'frame' => 'select', // The media manager frame - can be select, post, manage, image, audio, video, edit-attachments
			'class' => 'zn-media-video media-frame', // A css class that will be applied to the modal
			'value_type' => 'url', // The media manager state
		);

		$args = wp_parse_args( $option['data'], $defaults );
		$data_attributes = self::set_data_attributes( $args );
		$option['std'] = esc_html( $option['std'] );

		$output = '<input id="'.$option['id'].'" class="zn-media-value-container zn_input zn_input_text" type="text"  name="'.$option['id'].'" value="'.$option['std'].'" />';

		// The main button
		$output .= '<div class="zn-main-button zn_media_upload_add zn-add-media-trigger" '.$data_attributes.'>'.$args['button_title'].'</div>';

		return $output;
	}

/*--------------------------------------------------------------------------------------------------
	Main media function
--------------------------------------------------------------------------------------------------*/
	function zn_media( $option ){

		$defaults = array(
			'button_title' => 'Add image',
			'preview' => 'image_holder',
			'preview_holder' => 'Nothing selected',

		);

		$args = wp_parse_args( $option['data'], $defaults );
		$preview_holder_class = !empty( $option['std'] ) ? '' : 'zn-media-preview-holder-empty';
		$data_attributes = self::set_data_attributes( $args );
		$option['std'] = esc_html( $option['std'] );

		$field_type = ( $args['preview'] == 'text' ) ? 'text' : 'hidden';

		// The option id where we store the values
		$output = '<input id="'.$option['id'].'" class="zn-media-value-container zn_input zn_input_text" type="'.$field_type.'"  name="'.$option['id'].'" value="'.$option['std'].'" />';

		// The main button
		$output .= '<div class="zn-main-button zn_media_upload_add zn-add-media-trigger" '.$data_attributes.'>'.$args['button_title'].'</div>';

		// RENDER THE IMAGE HOLDER
		if( $args['preview'] == 'image_holder' ){
			$output .= '<div class="zn-media-preview-holder zn_preview_holder_'.$option['data']['media_type'].' '.$preview_holder_class.'">'.$option['preview_holder'].'</div>';
		}

		return $output;

	}

	// This function prepares the data attributes
	static function set_data_attributes( $data ){
		$data_string = "";

		foreach($data as $key=>$value)
		{
			if(is_array($value)) $value = implode(", ",$value);
			$data_string .= " data-$key='$value' ";
		}

		return $data_string;
	}



}

return new ZnHgFw_Options();
