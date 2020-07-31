<?php if(! defined('ABSPATH')){ return; }

class ZNB_Sidebar extends ZionElement
{

	function getSidebars(){
		global $wp_registered_sidebars;
		$allSidebars = array();

		if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ){
			foreach ($wp_registered_sidebars as $key => $value) {
				$allSidebars[$key] = $value['name'];
			}
		}

		return $allSidebars;
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$sidebar_select = $this->opt( 'sidebar_select', 'defaultsidebar' );
		if( !$sidebar_select ) return;

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-sidebar';
		$classes[] = 'dn-mainSidebar';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = zn_schema_markup('sidebar');
		$attributes[] = 'class="'.implode(' ', $classes).'"';

		echo '<aside '. implode(' ', $attributes ) .'>';
			echo '<div class="dn-sidebar">';
				dynamic_sidebar( $sidebar_select );
			echo '</div>';
		echo '</aside>';
		?>
	<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(
					array (
						"name"        => __( "Select sidebar", 'zn_framework' ),
						"description" => __( "Select your desired sidebar to be used on this post", 'zn_framework' ),
						"id"          => "sidebar_select",
						"std"         => "",
						"type"        => "select",
						"options"     => $this->getSidebars()
					),

				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);
		return $options;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_Sidebar(array(
	'id' => 'ZnSidebar',
	'name' => __('Sidebar', 'zn_framework'),
	'description' => __('This element will display a sidebar.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
)));
