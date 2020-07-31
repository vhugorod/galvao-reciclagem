<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

define( 'PB_PATH', dirname( __FILE__ ) );

/**
 * Class ZionEditor
 *
 * This class handles all functionality for the page builder
 */
class ZionEditor {
	/**
	 * Class constructor
	 * TODO: better optimize this code. We should only load the files if the editor is active
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'onWpInit' ) );

		if ( ZNB()->utility->isRequest( 'ajax' ) ) {

			//  Element default options
			add_filter( 'zn_pb_options', array( $this, 'defaultOptions' ), 10 );

			// Load zip exporters
			require dirname( __FILE__ ) . '/ZionPageBuilderExportHelper.php';
			require dirname( __FILE__ ) . '/class-page-builder-ajax.php';
		}
	}


	/**
	 * Method that runs on wp action
	 */
	public function onWpInit() {
		if ( ZNB()->utility->isActiveEditor() ) {
			$this->init();
		}
	}

	/**
	 * Main init. Will run only if the pagebuilder id active
	 */
	public function init() {
		// Disable caching
		$this->disableCaching();

		// Load HTML component from framework
		ZNHGFW()->getComponent( 'html' );

		// Add editor template
		add_action( 'wp_footer', array( $this, 'addFrontEditor' ) );
		add_action( 'wp_footer', array( $this, 'add_pb_factory' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts' ) );
		add_action( 'wp_enqueue_scripts', array( ZNB()->scripts_manager, 'enqueueCompiledCss' ), 9 );
		add_filter( 'body_class', array( $this, 'addBodyClass' ) );

		// LOAD ALL THE ELEMENTS SCRIPTS AND INLINE JS
		add_action( 'wp_footer', array( $this, 'addInlineJs' ) );

		// Fix post_type not added for ajax calls
		// See wp-includes/post-template.php -- ! is_admin
		add_filter( 'post_class', array( $this, 'fixPostClasses' ) );

		// Render functions
		add_action( 'znb:before_element_render', array( $this, 'beforeElement' ) );
		add_action( 'znb:after_element_render', array( $this, 'addInlineAssets' ) );
		add_action( 'znb:after_element_render', array( $this, 'afterElement' ) );

		do_action( 'znpb_editor_after_init' );
	}

	/**
	 * @return mixed|void
	 */
	public function getPageOptions() {
		$options = array(
			'has_tabs'   => true,
			'custom_css' => array(
				'title'   => __( 'Custom css', 'zn_framework' ),
				'options' => array(
					array(
						'id'          => 'zn_page_custom_css',
						'name'        => __( 'Custom CSS', 'zn_framework' ),
						'description' => __( 'Use this field to add your own custom css that will be applied to the current page.', 'zn_framework' ),
						'type'        => 'custom_code',
						'class'       => 'zn_full',
						'editor_type' => 'css',
					),
				),
			),
			'custom_js' => array(
				'title'   => 'Custom js',
				'options' => array(
					array(
						'id'          => 'zn_page_custom_js',
						'name'        => __( 'Custom JS', 'zn_framework' ),
						'description' => __( 'Use this field to add your own custom javascript code that will be applied to the current page.', 'zn_framework' ),
						'type'        => 'custom_code',
						'class'       => 'zn_full',
						'editor_type' => 'javascript',
					),
				),
			),
		);

		return apply_filters( 'znb:editor:page_options', $options );
	}


	/* EDITOR RENDER ELEMENTS METHOD */
	public function defaultOptions( $options ) {
		if ( empty( $options ) ) {
			return $options;
		}

		$default_options = array();

		$default_options[] = array(
			"name"        => __( "Element display?", 'zn_framework' ),
			"description" => __( "Using this option you can show/hide the element for different type of visitors.", 'zn_framework' ),
			"id"          => "znpb_hide_visitors",
			"std"         => "all",
			"type"        => "select",
			"options"     => array(
				"all"      => __( "Show for all", 'zn_framework' ),
				"loggedin" => __( "Show only for logged in users", 'zn_framework' ),
				"visitor"  => __( "Show only for visitors ( not logged in )", 'zn_framework' ),
			),
		);

		$default_options[] = array(
			'id'          => 'css_class',
			'name'        => 'CSS class',
			'description' => 'Enter a css class that will be applied to this element. You can than edit the custom css, either in the Page builder\'s CUSTOM CSS (which is loaded only into that particular page), or in Kallyas options > Advanced > Custom CSS which will load the css into the entire website.',
			'type'        => 'text',
			'std'         => '',
		);

		if ( isset( $options['znpb_misc']['disable'] ) && in_array( 'znpb_hide_breakpoint', $options['znpb_misc']['disable'] ) ) {
			// nothing
		} else {
			$default_options[] = array(
				"name"        => __( "Hide element on breakpoints", 'zn_framework' ),
				"description" => __( "Choose to hide the element on either desktop, mobile or tablets. Please know that elements will not be hidden in Page builder edit mode, only normal View mode.", 'zn_framework' ),
				"id"          => "znpb_hide_breakpoint",
				"std"         => "",
				"type"        => "checkbox",
				"supports"    => array( 'zn_radio' ),
				"options"     => array(
					"lg" => __( "Large", 'zn_framework' ),
					"md" => __( "Medium", 'zn_framework' ),
					"sm" => __( "Small", 'zn_framework' ),
					"xs" => __( "Extra Small", 'zn_framework' ),
				),
				'class' => 'zn_breakpoints_classic',
			);
		}

		if ( isset( $options['has_tabs'] ) ) {

			// Re-order tabs
			if ( ! empty( $options['help'] ) ) {
				$help = $options['help'];
				unset( $options['help'] );
			}

			$options['znpb_misc'] = array(
				'title'   => 'Misc. Options',
				'options' => $default_options,
			);

			// Re-order tabs
			if ( ! empty( $help ) ) {
				$options['help'] = $help;
			}
		} else {
			$options = array_merge( $options, $default_options );
		}

		return $options;
	}

	/**
	 * @param $element
	 */
	public function beforeElement( $element ) {
		$size      = '';
		$css_class = '';
		if ( $element->flexible ) {
			$size = ( ! empty( $element->data['width'] ) ) ? $element->data['width'] : 'col-md-12';
			if ( strpos( $size, 'col-md-' ) === false ) {
				$size = str_replace( 'col-sm-', 'col-md-', $size );
			}
			$actual_size = $size;

			// RESPONSIVE FIXES
			$size_small  = ( ! empty( $element->data['options']['size_small'] ) ) ? $element->data['options']['size_small'] : str_replace( 'col-md-', 'col-sm-', $size );
			$size_xsmall = ( ! empty( $element->data['options']['size_xsmall'] ) ) ? $element->data['options']['size_xsmall'] : '';
			$size_large  = ( ! empty( $element->data['options']['size_large'] ) ) ? $element->data['options']['size_large'] : str_replace( 'col-md-', 'col-lg-', $size );

			// Set the proper responsive classes
			$size = $size . ' ' . $size_small . ' ' . $size_xsmall;

			$css_class = 'sortable_column';
		}

		// Check for LG Offset, if not, use SM's
		if ( isset( $element->data['options']['column_offset_lg'] ) && ! empty( $element->data['options']['column_offset_lg'] ) ) {
			$size .= ' ' . $element->data['options']['column_offset_lg'] . ' ';
		} else {
			if ( ! empty( $element->data['options']['column_offset'] ) ) {
				$size .= ' ' . str_replace( 'sm', 'lg', $element->data['options']['column_offset'] ) . ' ';
			}
		}

		// Check for MD Offset, if not, use SM's
		if ( isset( $element->data['options']['column_offset_md'] ) && ! empty( $element->data['options']['column_offset_md'] ) ) {
			$size .= ' ' . $element->data['options']['column_offset_md'] . ' ';
		} else {
			if ( ! empty( $element->data['options']['column_offset'] ) ) {
				$size .= ' ' . str_replace( 'sm', 'md', $element->data['options']['column_offset'] ) . ' ';
			}
		}

		if ( ! empty( $element->data['options']['column_offset'] ) ) {
			$size .= ' ' . $element->data['options']['column_offset'] . ' ';
		}

		$isHidden         = ! empty( $element->data['isHidden'] ) && $element->data['isHidden'];
		$isHiddenCssClass = $isHidden ? 'znklpb-element-hidden' : '';
		$uid              = zn_uid();

		echo '<div class="zn_pb_el_container zn_pb_section ' . $size . ' ' . $isHiddenCssClass . ' zn_element_' . strtolower( $element->class ) . '" data-form-uid="' . $uid . '" data-el-name="' . $element->getElementName() . ' options" data-uid="' . $element->data['uid'] . '" data-level="' . $element->level . '" data-object="' . $element->class . '" data-has_multiple="' . $element->has_multiple . '">';
		echo '<div class="zn_el_options_bar zn_pb_animate">';

		// SHOW THE WIDTH SELECTOR BUTTON
		if ( $element->flexible ) {
			$sizes = array(
				'col-md-12'  => '12/12',
				'col-md-11'  => '11/12',
				'col-md-10'  => '10/12',
				'col-md-9'   => '9/12',
				'col-md-8'   => '8/12',
				'col-md-7'   => '7/12',
				'col-md-6'   => '6/12',
				'col-md-5'   => '5/12',
				'col-md-4'   => '4/12',
				'col-md-3'   => '3/12',
				'col-md-2'   => '2/12',
				'col-md-1-5' => '1/5',
			);

			echo '<span class="zn_pb_select_width znpb_icon-resize-full zn_pb_icon">';
			echo '<span class="znpb_sizes_container">';

			foreach ( $sizes as $key => $value ) {
				$selected_width = '';
				if ( $key == $actual_size ) {
					$selected_width = ' class="selected_width" ';
				}
				echo '<span ' . $selected_width . ' data-width="' . $key . '">' . $value . '</span>';
			}

			echo '</span>';
			echo '</span>';
			//echo '<span class="zn_pb_increase zn_icon">&#xe2d3;</span>';
		}

		echo '<span class="znpb-element-title">' . $element->name . '</span>';
		echo '<a class="zn_pb_remove" data-tooltip="Remove element"><span class="znpb_icon-cancel zn_pb_icon"></span></a>';

		echo '<a class="zn_pb_group_handle" data-level="' . $element->level . '" data-tooltip="Move element"><span class="znpb_icon-move zn_pb_icon"></span></a>';
		echo '<a class="zn_pb_clone_button" data-clone="clone" data-tooltip="Clone element"><span class="znpb_icon-docs zn_pb_icon"></span></a>';

		// Element options
		if ( $element->options() ) {
			echo '<a data-uid="' . $element->data['uid'] . '" class="znpb-element-options-trigger zn_pb_edit_el" data-tooltip="Edit options"><span class="znpb_icon-cog-alt zn_pb_icon"></span></a>';
		}

		// Element save
		echo '<a data-uid="' . $element->data['uid'] . '" class="znpb-element-save-trigger" data-tooltip="Save element"><span class="znpb_icon-save zn_pb_icon"></span></a>';

		// Element hide
		echo '<a class="zn_pb_hide_element_button" data-tooltip="Hide element"><span class="znpb_icon-eye zn_pb_icon"></span></a>';

		// Section add new
		// if( $element->level == 1 ){
		// 	echo '<a class="znpb-add_template" data-tooltip="Add from library after"><span class="znpb_icon-eye3 zn_pb_icon">+</span></a>';
		// }

		echo '</div>'; // END OPTIONS BAR
	}

	/**
	 * @param object $element_instance The element instance that is currently rendering
	 */
	public function afterElement( $element_instance ) {
		echo '</div>'; // END ELEMENT
	}

	public function addInlineAssets( $element_instance ) {
		// Add inline js and css
		$echo = defined( 'ZN_PB_AJAX' );

		if ( $element_instance->js() ) {
			ZNHGFW()->getComponent( 'scripts-manager' )->add_inline_js( $element_instance->js(), $echo );
		}

		// Add inline CSS
		if ( $echo && method_exists( $element_instance, 'css' ) ) {
			ZNHGFW()->getComponent( 'scripts-manager' )->add_inline_css( $element_instance->css(), $echo );
		}
	}


	/**
	 * @param string $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	public function fixPostClasses( $classes, $class = '', $post_id = '' ) {
		$post = get_post( $post_id );

		$classes[] = $post->post_type;

		return $classes;
	}

	public function addInlineJs() {
		do_action( 'zn_pb_inline_js' );
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	public function addBodyClass( $classes ) {
		$classes[] = 'zn_pb_editor_enabled';

		return $classes;
	}

	/**
	 * Disables the cache
	 */
	public function disableCaching() {
		// Disable W3 Total cache/WP Super Cache, for editing page
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		// Disable W3TC
		if ( ! defined( 'DONOTMINIFY' ) ) {
			define( 'DONOTMINIFY', true );
		}

		if ( ! defined( 'DONOTCDN' ) ) {
			define( 'DONOTCDN', true );
		}
	}

	/**
	 * Will add the main editor container
	 * It will be populated by JavaScript
	 */
	public function addFrontEditor() {
		echo '<div id="zionBuilderApp"></div>';
	}

	public function add_pb_factory() {
		$json_encode_options = 0;
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			$json_encode_options = JSON_PRETTY_PRINT;
		}

		$pb_factory_data = wp_json_encode( $this->get_editor_data(), $json_encode_options );
		// var_dump( $this->get_editor_data() );
		echo '<script>' . PHP_EOL;
		echo '/* <![CDATA[ */' . PHP_EOL;

		echo 'var ZnPbData = ' . $pb_factory_data . ';' . PHP_EOL;

		echo '/* ]]> */' . PHP_EOL;
		echo '</script>';
	}

	/**
	 *
	 */
	public function get_editor_data() {
		$elements_data = $page_options_data = array();
		$categories    = $this->getCategories();
		$widgets       = array();
		$allElements   = ZNB()->elements_manager->getElements();
		foreach ( $allElements as $class => $values ) {
			if ( $class === 'ZnWidgetElement' ) {
				continue;
			}

			$elements_data[] = $values;
		}

		$widgets = $this->getAllWidgets();
		// Add all WordPress Widgets to editor
		foreach ( $widgets as $key => $widget ) {
			// $widget_module = $allElements[ 'ZnWidgetElement' ];
			$widget_module                     = ZNB()->elements_manager->getElement( 'ZnWidgetElement' );
			$cloned_module_instance            = clone $widget_module;
			$cloned_module_instance->name      = 'Widget - ' . $widget->name;
			$cloned_module_instance->widget_id = $widget->class;
			$elements_data[]                   = $cloned_module_instance;
		}

		// Get the page options
		$options = $this->getPageOptions();

		// Remove tabs if exists
		if ( ! empty( $options['has_tabs'] ) ) {
			unset( $options['has_tabs'] );
		}

		// Loop trough all the options tabs
		foreach ( $options as $key => $tab ) {
			foreach ( $tab['options'] as $k => $option ) {
				$page_options_data[$option['id']] = get_post_meta( ZNB()->utility->getPostID(), $option['id'], true );
			}
		}

		$l10n = array(
			'hidden_text' => esc_attr( __( "This element is hidden!", 'zn_framework' ) ),
		);

		// templates
		$templates    = ZNB()->templates->getPageBuilderTemplates();
		$allTemplates = array();

		if ( is_array( $templates ) ) {
			foreach ( $templates as $template ) {
				if ( empty( $template ) ) {
					continue;
				}
				$template       = unserialize( $template );
				$name           = str_replace( array( '{{{', '}}}' ), '', $template['name'] );
				$allTemplates[] = array(
					'name'  => $name,
					'level' => isset( $template['level'] ) ? $template['level'] : 1,
				);
			}
		}

		// Saved elements
		$savedElements    = ZNB()->templates->getPageBuilderTemplates( 'zn_pb_el_templates' );
		$allSavedElements = array();

		if ( is_array( $savedElements ) ) {
			foreach ( $savedElements as $savedElement ) {
				if ( empty( $savedElement ) ) {
					continue;
				}
				$savedElement       = unserialize( $savedElement );
				$name               = str_replace( array( '{{{', '}}}' ), '', $savedElement['name'] );
				$allSavedElements[] = array(
					'name'  => $name,
					'level' => isset( $savedElement['level'] ) ? $savedElement['level'] : 1,
				);
			}
		}

		return array(
			'current_layout'   => ZNB()->frontend->getLayoutModules(),
			'elements_data'    => $elements_data,
			'pb_menu'          => $categories,
			'page_options'     => $page_options_data,
			'fonts_list'       => ZNHGFW()->getComponent( 'font-manager' )->get_fonts_array(),
			'l10n'             => $l10n,
			'allTemplates'     => $allTemplates,
			'allSavedElements' => $allSavedElements,
			'postId'           => ZNB()->utility->getPostID(),
		);
	}

	/**
	 * Returns an array containing WordPress widgets data.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	function getAllWidgets() {
		global $wp_widget_factory;

		$widgets = array();

		foreach ( $wp_widget_factory->widgets as $class => $widget ) {
			$widget->class          = $class;
			$widgets[$widget->name] = $widget;
		}

		ksort( $widgets );

		return $widgets;
	}

	/**
	 * Retrieve the list of all default page builder categories
	 *
	 * @return mixed|void
	 */
	public function getCategories() {
		$categories = array(
			array(
				'filter' => '',
				'name'   => __( 'All elements', 'zn_framework' ),
			),
			array(
				'filter' => 'fullwidth',
				'name'   => __( 'Full width', 'zn_framework' ),
			),
			array(
				'filter' => 'layout',
				'name'   => __( 'Layouts', 'zn_framework' ),
			),
			array(
				'filter' => 'content',
				'name'   => __( 'Content', 'zn_framework' ),
			),
			array(
				'filter' => 'post',
				'name'   => __( 'Single elements', 'zn_framework' ),
			),
			array(
				'filter' => 'media',
				'name'   => __( 'Media', 'zn_framework' ),
			),
			array(
				'filter' => 'headers',
				'name'   => __( 'Headers', 'zn_framework' ),
			),
			array(
				'filter' => 'widgets',
				'name'   => __( 'Widgets', 'zn_framework' ),
			),
		);
		return apply_filters( 'zn_pb_categories', $categories );
	}

	/**
	 *
	 */
	public function loadScripts() {
		// Let others add scripts before ours
		do_action( 'znpb_editor_before_load_scripts' );

		wp_enqueue_style( 'zn_pb_style', ZNB()->plugin_url . '/assets/css/zn_front_pb.css' );
		wp_enqueue_style( 'open-sans' );

		// PB SPECIFIC PLUGINS
		wp_enqueue_script( 'isotope' );

		// IRIS IS NOT AVAILABLE IN FRONTEND SO WE NEED TO MANUALLY LOAD IT
		wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
			'jquery-ui-draggable',
			'jquery-ui-slider',
			'jquery-touch-punch',
		), false, 1 );
		wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), false, 1 );
		$colorpicker_l10n = array(
			'clear'         => __( 'Clear', 'zn_framework' ),
			'defaultString' => __( 'Default', 'zn_framework' ),
			'pick'          => __( 'Select Color', 'zn_framework' ),
		);
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );

		// Load the new system based on Vue JS
		wp_enqueue_script( 'zion-editor-script', ZNB()->plugin_url . '/dist/znpb_editor.bundle.js', array('jquery', 'zn_html_script'), ZNB()->version, true );

		// Load all JS files that are required by the elements
		foreach ( ZNB()->elements_manager->getElements() as $class => $element ) {
			$element->scripts();
		}

		// Let others add scripts before ours
		do_action( 'znpb_editor_after_load_scripts' );
	}

	/**
	 * @param $layout_data
	 * @param bool|false $single
	 *
	 * @return array
	 */
	public function buildOptionsArray( $layout_data, $single = false ) {
		if ( empty( $layout_data ) ) {
			return array();
		}

		$data = array();

		foreach ( $layout_data as $key => $module ) {
			$data[$module['uid']]            = $module;
			$data[$module['uid']]['content'] = array();

			if ( ! empty( $module['content'] ) ) {
				if ( ! empty( $module['content']['has_multiple'] ) ) {
					unset( $module['content']['has_multiple'] );

					foreach ( $module['content'] as $actual_content ) {
						$data = array_merge( $data, $this->buildOptionsArray( (array)$actual_content ) );
					}
				} else {
					$data = array_merge( $data, $this->buildOptionsArray( $module['content'] ) );
				}
			}
		}

		return $data;
	}
}
