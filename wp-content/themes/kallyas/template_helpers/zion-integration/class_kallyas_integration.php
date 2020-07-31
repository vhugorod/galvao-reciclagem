<?php if(! defined('ABSPATH')){ return; }

class Znb_Kallyas_Integration extends Znb_Integration
{
	var $all_available_elements;


	/**
	 * Check if we can load this integration or not
	 * @return bool
	 */
	static public function can_load(){
		return true;
	}


	function initialize(){

		// Admin area
		if( is_admin() ){
			add_action( 'znb:builder_status_enabled', array( $this, 'enable_editor' ) );
			add_action( 'znb:builder_status_disabled', array( $this, 'disable_editor' ) );
			// Make zn_page_builder_status meta key protected
			add_filter( 'is_protected_meta', array( $this, 'make_pb_status_meta_field_protected' ), 10, 2 );
		}

		add_filter( 'znb:element:section:custom_width', array( $this, 'change_section_width') );
		add_filter( 'znb:builder_status_id', array( $this, 'change_editor_status'), 10, 2 );

		add_filter( 'zn_framework_init', array( $this, 'map_functions'), 10 );
		add_action( 'znb:elements:register_elements', array( $this, 'register_elements' ), 9 );

		add_filter( 'znb:editor:page_options', array( $this, 'add_page_options' ) );

		add_filter( 'znb:default_elements', array( $this, 'filterDefaultElements' ) );

	}

	/**
	 * Will make the zn_page_builder_status meta key a protected one
	 *
	 * @param $protected Whatever the meta key is protected
	 * @param $meta_key The meta key to check for protected
	 * @return bool
	 */
	function make_pb_status_meta_field_protected( $protected, $meta_key ){
		if( $meta_key == 'zn_page_builder_status' ){
			$protected = true;
		}

		return $protected;
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->$key();
	}

	function is_debug(){
		return ZNB()->isDebug();
	}

	/**
	 * Filter what Zion builder elements to load in the theme
	 * @param array $defaultElements The list of the Zion Builder's default elements
	 * @return array
	 */
	function filterDefaultElements( $defaultElements ){
		if( ! empty($defaultElements) && isset($defaultElements['heading']) ) {
			return array(
				'heading' => $defaultElements['heading']
			);
		}
		return array();
	}

	function add_page_options( $options ){

		$custom_options = array('general' => array(
			'title' => 'General options',
			'options' => array (
				array(
					"slug" => array( 'page' , 'post', 'portfolio', 'product' ),
					'id'         	=> 'show_header',
					'name'       	=> 'Show header',
					'description' 	=> 'Choose if you want to show the main header or not on this page. ',
					'type'        	=> 'toggle2',
					'std'			=> 'show_header',
					'value'			=> 'show_header',
					'live' => array(
						'type'		=> 'hide',
						'css_class' => '#header'
					),
				),
				array (
					"slug" => array( 'page' , 'post', 'portfolio', 'product'),
					'id'         	=> 'show_footer',
					'name'       	=> 'Show footer',
					'description' 	=> 'Choose if you want to show the main footer on this page. ',
					'type'        	=> 'toggle2',
					'std'			=> 'show_footer',
					'value'			=> 'show_footer',
					'live' => array(
						'type'		=> 'hide',
						'css_class' => '.site-footer, .znpb-footer-smart-area'
					)
				),
			),
		));
		return array_merge($custom_options, $options );
	}

	function change_section_width(){
		return (int)zget_option( 'custom_width' , 'layout_options', false, '1170' );
	}

	function map_functions(){
		ZN()->pagebuilder = $this;
	}

	function add_module_to_layout( $module_object = null, $options = array(), $content = array(), $width = null ){
		return ZNB()->frontend->addModuleToLayout( $module_object = null, $options = array(), $content = array(), $width = null );
	}

	/**
	 * Doesn't do nothing. Old function loaded all pagebuilder elements
	 * @return void Nothing
	 */
	function get_all_modules(){}

	/**
	 * Will register old elements
	 * @param  [type] $element_manager [description]
	 */
	public function register_elements( $element_manager ) {

		// Load the compatbility element base
		include( dirname(__FILE__) . '/kallyas-element-base.php');

		$dirs = $this->get_elements_dirs();
		if( ! empty($dirs)){
		    $_processed = array();

			$kallyasElements = array();

			foreach( $dirs as $entry ) {

			    if( isset($_processed[$entry['path']])){
			        continue;
                }
                $_processed[$entry['path']] = $entry['path'];



				if( ! file_exists( $entry['path'] ) ){
					continue;
				}

				$elements_files_obj = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator( $entry['path'],
						RecursiveIteratorIterator::CHILD_FIRST )
				);
				$elements_files_obj->setMaxDepth ( 2 );

				$default_headers = array(
					'name' => 'Name',
					'class' => 'Class',
					'description' => 'Description',
					'category' => 'Category', // Full width elements , Content , Media , WooCommerce
					'level' => 'Level',
					'keywords' => 'Keywords',
					'unlimited_styles' => 'Styles',
					'flexible' => 'Flexible',
					'dependency_class' => 'Dependency_class',
					'scripts' => 'Scripts',
					'style' => 'Style',
					'has_multiple' => 'Multiple',
					'legacy' => 'Legacy',
				);

				foreach( $elements_files_obj as $filename => $fileobject ) {

					if ( 'php' != pathinfo( $fileobject->getFilename(), PATHINFO_EXTENSION ) )
						continue;

					$headers = get_file_data( $filename, $default_headers );

					if ( !$headers['class'] )
						continue;

					// CHECK IF WE HAVE A DEPENDENCY NOT INSTALLED
					if ( !empty( $headers['dependency_class'] ) && !class_exists( $headers['dependency_class'] ) ) {
						continue;
					}

					$path = $fileobject->getPath();
					$filename = str_replace('\\', '/', $filename);
					$url = trailingslashit($entry['url']) .basename($path);

					$args = array (
						'name' => $headers['name'],
						'id' => $headers['class'],
						'category' => $headers['category'],
						'path' => $path,
						'scripts' => $headers['scripts'],
						'style' => $headers['style'],
						'url' => $url,
						'file' => $filename,
						'flexible' => $headers['flexible'],
						'level' => $headers['level'],
						'unlimited_styles' => $headers['unlimited_styles'],
						'dependency_class' => $headers['dependency_class'],
						'has_multiple' => $headers['has_multiple'],
						'legacy' => $headers['legacy'],
						'keywords' => $headers['keywords'],
						'description' => $headers['description'],
						'icon' => ( is_file ( $path .'/icon.png' ) ) ? $url.'/icon.png' : ZNB()->plugin_url . '/assets/img/default_icon.png',
					);

					$kallyasElements[ $args['id'] ] = $args;
				}
			}
		}

		if( ! empty($kallyasElements)) {
			foreach ( $kallyasElements as $elementId => $elementArgs ) {
				// Finally ... register the element
				include_once( $elementArgs[ 'file' ] );
				$element_instance = new $elementArgs[ 'id' ]( $elementArgs );
				$element_manager->registerElement( $element_instance );
			}
		}
	}


	/**
	 * Returns a filtered list of pagebuilder element locations
	 *
	 * Can be filtered by plugins to add new elements
	 */
	function get_elements_dirs() {

		$dirs = array();

		//@since 4.0.12: Allow page builder elements to be added from plugins as well

		// theme
		$dirs[] = array(
			'url' => get_template_directory_uri() .'/pagebuilder/elements',
			'path' => get_template_directory() .'/pagebuilder/elements',
		);

		// child
		$dirs[] = array(
			'url' => get_stylesheet_directory_uri() .'/pagebuilder/elements',
			'path' => get_stylesheet_directory() .'/pagebuilder/elements',
		);
		return apply_filters( 'zn_pb_dirs', $dirs );
	}


	function zn_render_content( $elements ){
		return ZNB()->frontend->renderElements( $elements );
	}

	function register_smart_area( $areaId ){
		return ZNB()->smart_area->registerSmartArea( $areaId );
	}

	function is_active_editor(){
		return ZNB()->utility->isActiveEditor();
	}

	// TODO: REMOVE THIS FUNCTION FROM ALL LOCATIONS
	function load_page_modules( $modules ){
		return ZNB()->frontend->setupElements( $modules );
	}


	/**
	 * Renders an area without the options to show elements options buttons
	 * @param  [type]  $elements [description]
	 * @param  boolean $area_id  [description]
	 * @return [type]            [description]
	 */
	function zn_render_uneditable_content( $elements, $area_id = false ){
		ZNB()->frontend->renderUneditableContent( $elements, $area_id );
	}


	function addModuleToLayout(){

	}

	function refresh_pb_data(){

	}

	function make_text_editable( $content = '', $option_id = '' ){
		return ZNB()->utility->makeTextEditable( $content, $option_id );
	}

	function getColumnsContainer($args){
		return ZNB()->utility->getColumnsContainer($args);
	}

	function getElementContainer($args){
		return ZNB()->utility->getElementContainer($args);
	}

	/**
	 * Will change the editor status
	 * Prior plugin separartion, the pb status meta field was "zn_page_builder_status".
	 * Since then, we've made this meta field private so we can remove some unnecesary code
	 * @param  string $builder_status The current builder status
	 * @param  string $post_id The current post id
	 * @since 1.0.0
	 */
	function change_editor_status( $builder_status, $post_id ){
		if( $status = get_post_meta( $post_id, 'zn_page_builder_status', true ) ){
			$builder_status = ('enabled' == $status);
		}
		return $builder_status;
	}

	/**
	 * Will link the Zion builder PB enabled action
	 * @since: v1.0.0
	 * @param  string $post_id The post id for which we should enable the editor
	 */
	function enable_editor( $post_id ){
		update_post_meta( $post_id, 'zn_page_builder_status', 'enabled');
	}


	/**
	 * Will link the Zion builder PB disabled action
	 * @since: v1.0.0
	 * @param  string $post_id The post id for which we should disable the editor
	 */
	function disable_editor( $post_id ){
		update_post_meta( $post_id, 'zn_page_builder_status', 'disabled');
	}

}

// Dummy class needed to unload the pagebuilder functionality from Kallyas
class ZnBuilder{}
