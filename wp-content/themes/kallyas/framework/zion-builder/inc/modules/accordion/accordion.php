<?php if(! defined('ABSPATH')){ return; }

class ZNB_Accordion extends ZionElement
{

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$extra_options = array (
			"name"           => __( "Accordions", 'zn_framework' ),
			"description"    => __( "Here you can create your desired accordions.", 'zn_framework' ),
			"id"             => "accordion_single",
			"std"            => "",
			"type"           => "group",
			"group_sortable" => true,
			"element_title" => "acc_title",
			"subelements"    => array (

				array (
					"name"        => __( "Title", 'zn_framework' ),
					"description" => __( "Please enter a title for this accordion.", 'zn_framework' ),
					"id"          => "acc_title",
					"std"         => "",
					"type"        => "text"
				),
				array (
					"name"        => __( "Expanded?", 'zn_framework' ),
					"description" => __( "Select yes if you want this panel to be expanded on page load.", 'zn_framework' ),
					"id"          => "acc_colapsed",
					"std"         => "no",
					"options"     => array (
						'yes' => __( 'Yes', 'zn_framework' ),
						'no'  => __( 'No', 'zn_framework' )
					),
					"type"        => "zn_radio",
					"class"        => "zn_radio--yesno",
				)
			)
		);

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Collapse Behavior", 'zn_framework' ),
						"description" => __( "Select the behavior of the collapsible panels. Upon click, Accordion Functionality will close other panels, while toggle just opens/closes the current clicked panel.", 'zn_framework' ),
						"id"          => "acc_behaviour",
						"std"         => "tgg",
						"options"     => array (
							'tgg' => __( 'Toggle', 'zn_framework' ),
							'acc'  => __( 'Accordion', 'zn_framework' )
						),
						"type"        => "select"
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
							'val_prepend'  => 'zn-accordion--theme-',
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
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#gIrgHl-BrLQ') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/accordion/') ),
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
	 * @return void
	 */
	function element()
	{

		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-accordion';
		$classes[] = 'zn-accordion--theme-'.$this->opt( 'element_scheme', 'light' );

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		echo '<div class="'.zn_join_spaces($classes).'" '. zn_join_spaces($attributes ) .'>';

				$acc_id = 1;
				$i = 0;
				$uniq   = zn_uid();
				$acc_js_el_id = "accordion_{$uniq}_{$acc_id}";

				echo '<div id="'.$acc_js_el_id.'" class="panel-group zn-accordion-panelGroup">';

				if ( isset ( $options['accordion_single'] ) && is_array( $options['accordion_single'] ) ) {
					foreach ( $options['accordion_single'] as $acc )
					{
						$collapsed = ((isset($acc['acc_colapsed']) && $acc['acc_colapsed'] == 'yes') ? 'in' : '');
						$sTitle = (isset($acc['acc_title']) ? $acc['acc_title'] : '');
						$isActive = ($collapsed ? '' : 'collapsed');

						// Functionality
						$acc_behaviour = '';
						if( isset($options['acc_behaviour']) && $options['acc_behaviour'] == 'acc' ){
							$acc_behaviour = ' data-parent="#'.$acc_js_el_id.'" ';
						}

						echo '<div class="panel zn-accordion-accGroup">';

								echo '<div class="zn-accordion-accTitle">';
									echo '<a data-toggle="collapse" '.$acc_behaviour.' href="#acc' . $uniq . '' . $acc_id . '" class="zn-accordion-accButton '.$isActive.'">';
										printf( '%s<span class="zn-accordion-accIcon"></span>', $sTitle );
									echo '</a>';
								echo '</div>';


							echo '<div id="acc' . $uniq . '' . $acc_id . '" class="zn-accordion-panelCollapse collapse ' . $collapsed .'">';

								echo znb_get_element_container(array(
									'cssClasses' => 'zn-accordion-accContent row '
								));

								if ( empty( $this->data['content'][$i] ) ) {
									$column = ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' );
									$this->data['content'][$i] = array ( $column );
								}

								if ( !empty( $this->data['content'][$i] ) ) {
									ZNB()->frontend->renderContent( $this->data['content'][$i] );
								}

								echo '</div>'; // .zn-accordion-accContent

							echo '</div>'; // .zn-accordion-panelCollapse

						echo '</div>'; // .zn-accordion-accGroup
						$acc_id ++;
						$i++;
					}
				}
				echo '</div>'; //.panel-group
			?>
		</div>
		<!-- end // .zn_accordion_element  -->
		<?php
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

ZNB()->elements_manager->registerElement( new ZNB_Accordion(array(
	'id' => 'ZnAccordion',
	'name' => __('Accordion', 'zn_framework'),
	'description' => __('This element will generate an accordion collapsible element.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
	'multiple' => true,
	'has_multiple' => true,
	'keywords' => array('toggle', 'collapsible', 'expandable'),
)));
