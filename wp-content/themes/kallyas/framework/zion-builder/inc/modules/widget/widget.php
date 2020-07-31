<?php if(! defined('ABSPATH')){ return; }

class ZNB_WidgetElement extends ZionElement
{
	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array (
						'id'          => 'options_wrapper',
						'class'       => 'zn_widget_options_container',
						'type'        => 'options_wrapper',
						'option_file'	=> dirname ( __FILE__ ) .'/options.php',
						'options_data' => $this->data
					),

				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/anchor-point-element/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),
		);

		return $options;

	}

	function element(){

		$options = $this->data['options'];
		if( empty( $options ) ) { return; }

		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-widgetEl';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces( $attributes ) .'>';

			global $wp_widget_factory;

			// Widget class
			$widget_option = $this->opt('widget');
			if( ! empty( $this->data['widget'] ) ) {
				$widget_slug = $this->data['widget'];
			}
			else if( ! empty( $widget_option ) ) {
				$widget_slug = $widget_option;
			}

			if( ! empty($widget_slug) && isset($wp_widget_factory->widgets[$widget_slug])) {

				// Widget instance
				$factory_instance   = $wp_widget_factory->widgets[$widget_slug];
				$widget_class       = get_class($factory_instance);
				$widget_instance    = new $widget_class($factory_instance->id_base, $factory_instance->name, $factory_instance->widget_options);

				// Get saved options
				$saved_options = ! empty( $this->data['options'] ) ? $this->data['options'] : array();

				// Widget settings
				$settings_key       = 'widget-' . $widget_instance->id_base;
				$widget_settings    = isset( $saved_options[$settings_key][0] ) ? $saved_options[$settings_key][0] : array();

				// Render the widget
				the_widget($widget_slug, $widget_settings, array('widget_id' => 'znpb_widget' . $this->data['uid']));
			}
			else if( isset( $widget_slug ) && ZNB()->utility->isActiveEditor() ) {

				// Widget doesn't exist!
				printf( _x( '%s does not exists.', '%s stands for widget slug.', 'zn_framework' ), $widget_slug );

			}

		echo '</div>';
	}
}

ZNB()->elements_manager->registerElement( new ZNB_WidgetElement(array(
	'id' => 'ZnWidgetElement',
	'name' => __('Widget', 'zn_framework'),
	'description' => __('This element will display a WordPress widget.', 'zn_framework'),
	'level' => 3,
	'category' => 'Widgets',
	'legacy' => false,
)));
