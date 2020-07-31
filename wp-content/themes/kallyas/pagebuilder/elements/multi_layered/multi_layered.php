<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Multi Layered Content
	Description: This element will generate group of items that lay one each other
	Class: HG_MultiLayered
	Category: Layout
	Level: 3
	Style: true
	Multiple: true
	Keywords: toggle, layer, group
*/
class HG_MultiLayered extends ZnElements
{
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
				'title' => 'General options',
				'options' => array(

					array(
						'id'          => 'custom_height',
						'name'        => __( 'Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this layer group. If you want to reset the height, simply leave the input blank.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => array('lg'=>'400'),
						'helpers'     => array(
							'min' => '0',
							'max' => '1400'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid,
							'css_rule'  => 'min-height',
							'unit'      => 'px'
						)
					),

					array (
						"name"           => __( "Layers", 'zn_framework' ),
						"description"    => __( "Here you can add your desired layers.", 'zn_framework' ),
						"id"             => "layers",
						"std"            => "",
						"type"           => "group",
						"add_text"       => __( "Layer", 'zn_framework' ),
						"remove_text"    => __( "Layer", 'zn_framework' ),
						"group_sortable" => true,
						"element_title" => "title",
						"subelements"    => array (
							array (
								"name"        => __( "Layer Title", 'zn_framework' ),
								"description" => __( "Please enter the desired title of the layer.", 'zn_framework' ),
								"id"          => "title",
								"std"         => "",
								"type"        => "text"
							),
						)
					)

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

		if( empty ( $options['layers'] ) ){
			return;
		}

		$classes = $attributes = array();
		$uid = $this->data['uid'];
		$pb_active = ZNB()->utility->isActiveEditor();

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-multiLayers';
		$classes[] = 'zn-multiLayers--pb-'. ( $pb_active ? 'active' : 'inactive' );

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces($attributes ) .'>';

		$single_layers = $this->opt('layers');
		$listCount = count($single_layers);



		if ( ! empty ( $single_layers ) && is_array( $single_layers ) )
		{
			if($pb_active){
				echo '<div class="zn-multiLayers-content">';
			}

			// foreach ( $single_layers as $tab )
			for ($i = 0; $i < $listCount; $i++ )
			{
				$cls = $content = $uniq_name = '';

				if($pb_active){
					if ( $i === 0 ) {
						$cls = 'active in';
					}
				}
				$uniq_name = $uid.'_'.$i;
				// CONTENT
				echo '<div class="zn-multiLayers-item ' . $cls . '" id="' . $uniq_name . '">';

					// Add complex page builder element
					echo znb_get_element_container(array(
						'cssClasses' => 'row'
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

			if($pb_active){
				echo '</div>';
			}

			if( $pb_active ) {
				echo '<ul class="zn-multiLayers-nav clearfix" role="tablist">';

					// foreach ( $single_layers as $tab )
					for ($i = 0; $i < $listCount; $i++ )
					{
						$cls = '';
						if ( $i === 0 ) {
							$cls = 'active in';
						}

						$uniq_name = $uid.'_'.$i;
						// Tab Handle
						echo '<li class="zn-multiLayers-navItem ' . esc_attr( $cls ) . '">';
							echo '<a href="#' . $uniq_name . '" role="tab" data-toggle="tab">';
								echo '<span class="zn-multiLayers-navTitle">'.$single_layers[$i]['title'].'</span>';
							echo '</a>';
						echo '</li>';
						// $i++;
					}

				echo '</ul>';

			}

		}
		echo '</div>';
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		$css .= zn_smart_slider_css( $this->opt( 'custom_height', array('lg'=> '400') ), '.'.$uid , 'min-height' );

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
