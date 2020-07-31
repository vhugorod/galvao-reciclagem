<?php if(! defined('ABSPATH')){ return; }

class ZNB_Tabs extends ZionElement
{

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$extra_options = array (
			"name"           => __( "Tabs", 'zn_framework' ),
			"description"    => __( "Here you can add your desired tabs.", 'zn_framework' ),
			"id"             => "tabs",
			"std"            => "",
			"type"           => "group",
			"add_text"       => __( "Tab", 'zn_framework' ),
			"remove_text"    => __( "Tab", 'zn_framework' ),
			"group_sortable" => true,
			"element_title" => "title",
			"subelements"    => array (
				array (
					"name"        => __( "Tab Title", 'zn_framework' ),
					"description" => __( "Please enter the desired title that will appear as tab.", 'zn_framework' ),
					"id"          => "title",
					"std"         => "",
					"type"        => "text"
				),
				array (
					"name"        => __( "Display Icon?", 'zn_framework' ),
					"description" => __( "Please enter the desired title that will appear as tab.", 'zn_framework' ),
					"id"          => "has_icon",
					"std"         => "",
					"value"         => "1",
					"type"        => "toggle2"
				),
				array (
					"name"        => __( "Select Icon", 'zn_framework' ),
					"description" => __( "Select an icon to display.", 'zn_framework' ),
					"id"          => "icon",
					"std"         => "",
					"type"        => "icon_list",
					'class'       => 'zn_full',
					"dependency"  => array( 'element' => 'has_icon' , 'value'=> array('1') ),
				),
			)
		);

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Tab Buttons Alignment", 'zn_framework' ),
						"description" => __( "Please select an alignment for the Tab Buttons", 'zn_framework' ),
						"id"          => "tabs_alignment",
						"std"         => "left",
						"type"        => "select",
						'options'     => array(
							'left' => __( 'Left (default)', 'zn_framework' ),
							'center' => __( 'Center', 'zn_framework' ),
							'right' => __( 'Right', 'zn_framework' ),
							'justify' => __( 'Justified', 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'   => 'zn-tabs--alg-'
						),
					),

					array(
						'id'          => 'element_scheme',
						'name'        => __('Element Color Scheme','zn_framework'),
						'description' => __('Select the color scheme of this element','zn_framework'),
						'type'        => 'select',
						'std'         => 'light',
						'options'        => array(
							'light' => __('Light (default)','zn_framework'),
							'dark' => __('Dark','zn_framework')
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid,
							'val_prepend'  => 'zn-tabs--theme-',
						)
					),

					$extra_options,
				),
			),

			'spacing' => array(
				'title' => 'SPACING',
				'options' => array(

				),
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/horizontal-tabs/',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);

		$options['spacing']['options'] = array_merge($options['spacing']['options'], zn_margin_padding_options($uid) );

		return $options;
	}
	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];

		if( empty ( $options['tabs'] ) ){
			return;
		}

		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-tabs';
		$classes[] = 'zn-tabs--theme-'.$this->opt( 'element_scheme', '' );
		$classes[] = 'zn-tabs--alg-'.$this->opt( 'tabs_alignment', 'left' );

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces($attributes ) .'>';

		$single_tabs = $this->opt('tabs');
		$tabsListCount = count($single_tabs);

		$pb_tab = 0;

		if ( ! empty ( $single_tabs ) && is_array( $single_tabs ) )
		{
			echo '<ul class="zn-tabs-nav clearfix" role="tablist">';

				// foreach ( $single_tabs as $tab )
				for ($i = 0; $i < $tabsListCount; $i++ )
				{
					$cls = '';
					if ( $i === 0 ) {
						$cls = 'active in';
					}
					$uniq_name = $uid.'_'.$i;
					// Tab Handle
					echo '<li class="zn-tabs-navItem ' . $cls . '">';
						echo '<a href="#' . $uniq_name . '" role="tab" data-toggle="tab">';
							// ICON CHECK
							$hasTabIcon = ( ! empty( $single_tabs[$i]['has_icon'] ) && $single_tabs[$i]['has_icon'] !== 'zn_dummy_value' );
							if ( $hasTabIcon && isset( $single_tabs[$i]['icon'] ) && ! empty ( $single_tabs[$i]['icon'] ) ) {
								$iconHolder = $single_tabs[$i]['icon'];
								if ( ! empty( $iconHolder['family'] ) ) {
									echo '<span class="zn-tabs-navIcon " '. zn_generate_icon( $single_tabs[$i]['icon'] ) . '></span>';
								}
							}
							echo '<span class="zn-tabs-navTitle">' . $single_tabs[$i]['title'] . '</span>';
						echo '</a>';
					echo '</li>';
					// $i++;
				}

			echo '</ul>';

			echo '<div class="zn-tabs-content">';

				// foreach ( $single_tabs as $tab )
				for ($i = 0; $i < $tabsListCount; $i++ )
				{
					$cls = $content = '';
					if ( $i === 0 ) {
						$cls = 'active in';
					}
					$uniq_name = $uid.'_'.$i;


					// TAB CONTENT
					echo '<div class="zn-tabs-contentPane ' . $cls . '" id="' . $uniq_name . '">';

						// Add complex page builder element
						echo znb_get_element_container(array(
							'cssClasses' => 'row zn-tabs-paneContainer '
						));
							if ( empty( $this->data['content'][$i] ) ) {
								$column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' );
								$this->data['content'][$i] = array ( $column );
							}

							if ( !empty( $this->data['content'][$i] ) ) {
								ZNB()->frontend->renderContent( $this->data['content'][$i] );
							}

						echo '</div>';

					echo '</div>';
				}

			echo '</div>';
		}
		echo '</div>';
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('margin_lg', '' );
		$margins['md'] = $this->opt('margin_md', '' );
		$margins['sm'] = $this->opt('margin_sm', '' );
		$margins['xs'] = $this->opt('margin_xs', '' );
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}

		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt('padding_lg', '' );
		$paddings['md'] = $this->opt('padding_md', '' );
		$paddings['sm'] = $this->opt('padding_sm', '' );
		$paddings['xs'] = $this->opt('padding_xs', '' );
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid;
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		return $css;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_Tabs(array(
	'id' => 'ZnTabs',
	'name' => __('Tabs', 'zn_framework'),
	'description' => __('This element will generate group of tabs.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
	'multiple' => true,
	'has_multiple' => true,
	'keywords' => array('toggle'),
)));
