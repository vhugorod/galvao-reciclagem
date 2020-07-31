<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionPageBuilderFrontend
 *
 * Engine for rendering the PageBuilder elements in Edit and View mode
 */
class ZionPageBuilderFrontend {
	/**
	 * Will register custom PB areas ( for example, a custom footer )
	 * @var array
	 */
	var $_registered_layout_areas = array();

	/**
	 * Will register custom PB areas ( for example, a custom footer )
	 * @var array
	 */
	var $instantiated_modules = array();


	/**
	 * Main class constructor
	 */
	function __construct() {
		// Add search compatibility
		include ZNB()->plugin_dir . 'inc/ZionWordPressSearch.php';

		// Add the "Edit with pagebuilder" to the admin bar
		add_action( 'admin_bar_menu', array( $this, 'adminBarMenu' ), 999 );

		// Load our content
		add_action( 'wp', array( $this, 'prepareContent' ), 11 );
	}

	/**
	 * CHECKS TO SEE IF WE NEED TO LOAD THE PAGE BUILDER TEMPLATE
	 *
	 * @access public
	 * @param $template string
	 * @return string
	 */
	function loadPageBuilderTemplate( $template ) {
		// CHECK IF WE HAVE A PAGE BUILDER ENABLED PAGE/POST
		if ( ! $template = locate_template( array( 'template_helpers/index-page-builder.php' ) ) ) {
			$template = dirname( __FILE__ ) . '/index-page-builder.php';
		}
		return $template;
	}


	/**
	 * Prepares the pagebuilder
	 */
	function prepareContent() {

		// Check to see if we have PB enabled
		if ( ZNB()->utility->isPageBuilderEnabled() ) {
			$canLoadTemplate = apply_filters('znpb_can_load_template', true);
			if ( $canLoadTemplate ) {
				if ( ! post_password_required() ) {
					// Check if this is a page builder enabled page and load our editor/renderer
					add_action( 'template_include', array( $this, 'loadPageBuilderTemplate' ), 999 );
				}
				$this->registerDefaultLayoutArea();
			}
		}

		do_action( 'znb:frontend:content_prepare', $this );

		// Load the page layout
		$this->instantiate_elements();
		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts' ) );
	}


	/**
	 * Will register a layout area
	 * @param  [type] $area_id     [description]
	 * @param  [type] $area_config [description]
	 * @return [type]              [description]
	 */
	function registerLayoutArea( $area_id, $area_config ) {
		$this->_registered_layout_areas[ $area_id ] = $area_config;
	}


	/**
	 * Will register the default PB area if the PB is enabled on the current page
	 *
	 * @since: 1.0.0
	 */
	function registerDefaultLayoutArea() {
		$post_id     = ZNB()->utility->getPostID();
		$layout_data = get_post_meta( $post_id, 'zn_page_builder_els', true );
		$post        = get_post( $post_id );

		if ( ! is_array( $layout_data ) ) {
			if ( ! $layout_data = apply_filters( 'znpb_empty_page_layout', $layout_data, $post, $post_id ) ) {
				// We will add the new elements here
				$textbox = $this->addModuleToLayout( 'ZnTextBox', array(
					'title' => $post->post_title,
					'desc'  => $post->post_content,
				) );
				$column      = $this->addModuleToLayout( 'ZnColumn', array(), array( $textbox ), 'col-sm-12' );
				$layout_data = array( $this->addModuleToLayout( 'ZnSection', array(), array( $column ), 'col-sm-12' ) );
			}
		}

		$area_config = array(
			'layout_data' => $layout_data,
		);
		$this->registerLayoutArea( 'main_area', $area_config );
	}


	/**
	 * Will create instances for all elements needed on the current page
	 *
	 * @since 1.0.0
	 */
	function instantiate_elements() {
		foreach ( $this->_registered_layout_areas as $area_id => $area_config ) {
			$this->setupElements( $area_config[ 'layout_data' ] );
		}
	}

	/**
	 * Will return the pagebuilder element for the current layout
	 * @return array The instantiated elements for the currnet page
	 */
	function getLayoutModules() {
		return $this->instantiated_modules;
	}


	/**
	 * Enqueue all scripts and styles needed for the current page
	 */
	function loadScripts() {
		wp_enqueue_style( 'zion-frontend', ZNB()->plugin_url . '/assets/css/znb_frontend.css', false, ZNB()->version );
		if ( is_rtl() ) {
			wp_enqueue_style( 'zion-frontend-rtl', ZNB()->plugin_url . '/assets/css/rtl.css', false, ZNB()->version );
		}
		wp_enqueue_script('zion-frontend-js', ZNB()->plugin_url . '/dist/znpb_frontend.bundle.js', array('jquery'), ZNB()->version, true);
		wp_localize_script( 'zion-frontend-js', 'ZionBuilderFrontend', array(
			'allow_video_on_mobile' => apply_filters( 'znb_enable_mobile_video', false ),
		) );

		// 1. Load element extra scripts
		if ( is_array( $this->instantiated_modules ) ) {
			foreach ($this->instantiated_modules as $elementUid => $elementInstance ) {
				$elementInstance->scripts();
			}
		}

		// 2. Load cached styles and js
		ZNB()->scripts_manager->enqueueCachedAssets();
	}

	/**
	 * @TODO: move to elements manager
	 * TODO: RETURN WHAT?
	 * @param  [type] $element [description]
	 * @param mixed $elements
	 * @return [type]          [description]
	 */
	function setupElements( $elements = array() ) {
		if ( ! is_array( $elements ) ) {
			return array();
		}

		foreach ( $elements as $key => $element_data ) {
			$this->setupSingleElement( $element_data );
		}
	}

	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $element_data
	 * @return [type]          [description]
	 */
	function setupSingleElement( $element_data ) {
		// Clone the element instance
		$element_instance = $this->getElementInstance( $element_data );

		if ( ! $element_instance ) {
			return false;
		}

		// If the element has content
		if ( ! empty( $element_data[ 'content' ] ) && ( 'false' != $element_data[ 'content' ] ) ) {

			// If the element contains multiple contents
			if ( ! empty( $element_data[ 'content' ][ 'has_multiple' ] ) ) {
				if ( isset( $element_data[ 'content' ][ 'has_multiple' ] ) ) {
					unset( $element_data[ 'content' ][ 'has_multiple' ] );
				}

				foreach ( $element_data[ 'content' ] as $actual_content ) {
					if ( is_array( $actual_content ) ) {
						foreach ( $actual_content as $value ) {
							$this->setupSingleElement( $value );
						}
					}
				}
			} else {
				foreach ( $element_data[ 'content' ] as $key => $value ) {
					$this->setupSingleElement( $value );
				}
			}
		}


		// @TODO : Check if we can move this code in the smart area file
		// do_action( 'znb:elements:register_instance' );

		// This is just for the Pagebuilder Template/smart area element
		if ( 'ZnPbCustomTempalte' === $element_data[ 'object' ] || 'ZnSmartArea' === $element_data[ 'object' ]) {
			$template_post_id = $element_instance->opt( 'pb_template' );
			$pb_data          = $this->getSmartAreaLayout( $template_post_id );

			if ( is_array( $pb_data ) ) {
				foreach ( $pb_data as $key => $value ) {
					$this->setupSingleElement( $value );
				}
			}
		}

		// LOAD INLINE JS
		if ( $element_instance->js() && function_exists( 'ZNHGFW' ) ) {
			ZNHGFW()->getComponent( 'scripts-manager' )->add_inline_js( $element_instance->js() );
		}

		$this->instantiated_modules[ $element_data[ 'uid' ] ] = $element_instance;

		// Return the class instance of the element
		return $element_instance;
	}


	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $template_post_id
	 * @return [type]          [description]
	 */
	function getSmartAreaLayout( $template_post_id ) {
		return get_post_meta( $template_post_id, 'zn_page_builder_els', true );
	}


	/**
	 * Creates a module array
	 *
	 * @access public
	 * @param $module_object string
	 * @param $options array
	 * @param $content array
	 * @param $width string
	 * @return array
	 */
	function addModuleToLayout( $module_object = null, $options = array(), $content = array(), $width = null ) {
		$element_data = array(
			'object'  => $module_object,
			'options' => $options,
			'content' => $content,
			'width'   => $width,
			'uid'     => zn_uid( 'eluid' ),
		);

		$element_instance = $this->getElementInstance( $element_data );

		if ( ! $element_instance ) {
			return false;
		}

		$this->instantiated_modules[ $element_data[ 'uid' ] ] = $element_instance;

		return $element_data;
	}


	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $area
	 * @return [type]          [description]
	 */
	function renderContentByArea( $area = 'main_area' ) {
		// The area should have already been configured
		if ( empty( $this->_registered_layout_areas[ $area ] ) ) {
			return;
		}

		// Render the layout.
		ob_start();
		$this->renderContent( $this->_registered_layout_areas[ $area ][ 'layout_data' ] );
		$html = ob_get_clean();
		$html = apply_filters( 'znpb_area_content', $html, $area );

		echo '<div class="zn_pb_wrapper clearfix zn_sortable_content" data-droplevel="0">';
		echo do_shortcode( $html );
		echo '</div>';
	}


	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $elements
	 * @return [type]          [description]
	 */
	function renderContent( $elements ) {
		if ( ! empty( $elements[ 'has_multiple' ] ) ) {
			unset( $elements[ 'has_multiple' ] );
			foreach ( $elements as $key => $value ) {
				$this->renderElements( $value );
			}
		} else {
			$this->renderElements( $elements );
		}
	}


	/**
	 * Renders an area without displaying the content edit bar
	 * @param  array  $elements The array containing elements that needs to be displayed
	 * @param  bool|int $area_id  Area ID or false in case no area ID is passed
	 * @return string            HTML data for the requested area
	 */
	function renderUneditableContent( $elements, $area_id = false ) {
		$pbstate = ZNB()->utility->isActiveEditor();
		$html    = '';
		if ( $pbstate ) {
			// Set editor to disabled
			add_filter( 'znb:isActiveEditor', array( $this, 'disableActiveEditor' ) );
			remove_action( 'znb:before_element_render', array( ZNB()->builder, 'beforeElement' ) );
			remove_action( 'znb:after_element_render', array( ZNB()->builder, 'afterElement' ) );

			// Show an ediy smart area link
			if ( $area_id ) {
				$html .= '<div class="znhg-uneditable-area">';

				// Get the area Edit URL
				$edit_url = ZNB()->utility->getEditUrl( $area_id );
				$html .= '<a class="znhg-uneditable-area-url" target="_blank" href="' . $edit_url . '">' . __( 'Edit smart area with pagebuilder', 'zn_framework' ) . '<span class="dashicons dashicons-admin-generic"></span></a>';
			}
		}

		// Render the layout.
		ob_start();
		ZNB()->frontend->renderElements( $elements );
		$html .= ob_get_clean();

		if ( $pbstate && $area_id ) {
			$html .= '</div>';
		}

		// Process shortcodes.
		echo do_shortcode($html);

		if ( $pbstate ) {
			add_action( 'znb:before_element_render', array( ZNB()->builder, 'beforeElement' ) );
			add_action( 'znb:after_element_render', array( ZNB()->builder, 'afterElement' ) );
			remove_filter( 'znb:isActiveEditor', array( $this, 'disableActiveEditor' ) );
		}
	}


	public function disableActiveEditor() {
		return false;
	}

	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $elements
	 * @return [type]          [description]
	 */
	function renderElements( $elements = array() ) {
		if ( ! is_array( $elements ) ) {
			return;
		}

		foreach ( $elements as $element ) {
			$this->renderSingleElement( $element );
		}
	}

	function getElementWithData( $elementData ) {


		// Set-up UID if not provided. The UID is not provided when the element is cloned for example
		if ( ! isset( $elementData[ 'uid' ] ) ) {
			$elementData[ 'uid' ] = zn_uid( 'eluid' );
		}

		if ( ! empty( $this->instantiated_modules[ $elementData[ 'uid' ] ] ) ) {
			$elementInstance = $this->instantiated_modules[ $elementData[ 'uid' ] ];
		} else {
			// Create a new instance of the element
			// get an instance of the element
			$elementInstance = $this->getElementInstance( $elementData );
		}
		return $elementInstance;
	}

	/**
	 * Returns an instance of the element with provided data
	 * @param  array $elementData
	 * @return object The instance of the element from $elementData
	 */
	function getElementInstance( $elementData ) {
		if ( ! isset($elementData['object']) ) {
			return false;
		}

		// get an instance of the element
		$element_type_instance = ZNB()->elements_manager->getElement( $elementData[ 'object' ] );

		if ( false === $element_type_instance ) {
			return false;
		}

		if ( ! $element_type_instance ) {
			return false;
		}

		// Clone the element instance
		$elementInstance = clone $element_type_instance;

		// Add saved data to element
		$elementInstance->setData( $elementData );

		return $elementInstance;
	}


	/**
	 * @TODO: move to elements manager
	 * @param  [type] $element [description]
	 * @param mixed $elementData
	 * @return [type]          [description]
	 */
	function renderSingleElement( $elementData ) {
		// Get the element instance
		$elementInstance = $this->getElementWithData( $elementData );
		$canRender       = true;
		$isActiveEditor  = ZNB()->utility->isActiveEditor();

		if ( ! $elementInstance ) {
			return false;
		}

		if ( ! $isActiveEditor ) {
			// Check if we can display this element
			$display = $elementInstance->opt( 'znpb_hide_visitors', 'all' );
			if ( 'loggedin' === $display && ! is_user_logged_in() ) {
				$canRender = false;
			} elseif ( 'visitor' === $display && is_user_logged_in() ) {
				$canRender = false;
			}

			// check if element is hidden
			$ishidden = ! empty( $elementInstance->data['isHidden'] ) && $elementInstance->data['isHidden'];
			if ( $ishidden ) {
				$canRender = false;
			}
		}

		// Don't proceed if we can't render this element
		if ( ! $canRender ) {
			return false;
		}

		// Perform an action that other can use to add extra content
		do_action( 'znb:before_element_render', $elementInstance );

		// Use the edit method if the element supports it and the editor is active
		if ( $isActiveEditor && method_exists( $elementInstance, 'element_edit' ) ) {
			// render active editor version of the element
			$elementInstance->element_edit();
		} else {
			// Render element
			$elementInstance->elementRender();
		}
		// Perform an action that other can use to add extra content
		do_action( 'znb:after_element_render', $elementInstance );
	}

	/**
	 * Will add Edit/view page buttons to admin bar if pagebuilder is active on current page
	 * @param  [type] $wp_admin_bar [description]
	 * @return [type]               [description]
	 */
	function adminBarMenu( $wp_admin_bar ) {
		if ( ! ZNB()->utility->allowedEdit() || ! ZNB()->utility->isPageBuilderEnabled() ) {
			return false;
		}

		$post_id = ZNB()->utility->getPostID();
		$args    = array();

		if ( ZNB()->utility->isActiveEditor() ) {
			$args = array(
				'id'    => 'znb_preview_button',
				'title' => __( 'View page', 'zn_framework' ),
				'href'  => esc_url( get_permalink( $post_id ) ),
				'meta'  => array( 'class' => 'znb_preview_page_button' ),
			);
		} else {
			$args = array(
				'id'    => 'znb_edit_button',
				'title' => __( 'Edit with page builder', 'zn_framework' ),
				'href'  => ZNB()->utility->getEditUrl( $post_id ),
				'meta'  => array( 'class' => 'znb_edit_button' ),
			);
		}

		$wp_admin_bar->add_node( $args );
	}
}
