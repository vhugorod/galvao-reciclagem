<?php if(! defined('ABSPATH')){ return; }
/*
	Name: Sticky Section LITE
	Description: This element will generate a sticky section that will be glued to the bottom edge of the screen (window) and in which you can add elements
	Class: ZnStickySectionLite
	Category: Layout, Fullwidth
	Keywords: row, container, block, footer, sticky, fixed
	Level: 1
	Style: true
	Toolbar: bottom-blue
*/

class ZnStickySectionLite extends ZnElements {

	function options() {

		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						'id'          => 'size',
						'name'        => 'Section Width',
						'description' => 'Select the desired size for this section.',
						'type'        => 'select',
						'std'        => 'container',
						'options'	  => array(
							'container' => 'Fixed width',
							'full_width' => 'Full width',
						),
						'live' => array(
							'type'		=> 'class',
							'css_class' => '.'.$uid.' .zn_sticky_section_size'
						)
					),

					array (
						'id'          => 'height',
						'name'        => 'Section Height',
						'description' => 'Select the desired height for this section.',
						'type'        => 'select',
						'std'        => 'auto',
						'options'     => array(
							'auto' => 'Auto',
							'custom_height' => 'Custom Height'
						),
					),

					array(
						'id'          => 'custom_height',
						'name'        => __( 'Section Custom Height', 'zn_framework'),
						'description' => __( 'Choose the desired height for this section. You can choose either height or min-height as a property. Height will force a fixed size rather than just a minimum. <br>*TIP: Use 100vh to have a full-height element.', 'zn_framework' ),
						'type'        => 'smart_slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '1400'
						),
						'supports' => array('breakpoints'),
						'units' => array('px', 'vh'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid.'.zn_sticky_section',
							'css_rule'  => 'height',
							'unit'      => 'px'
						),
						'dependency' => array( 'element' => 'height' , 'value'=> array('custom_height') )
					),

					array(
						'id'          => 'unstick',
						'name'        => __( 'Sticky mode breakpoint', 'zn_framework'),
						'description' => __( 'Choose the desired browser width (viewport) when this section should become static not fixed (sticky).', 'zn_framework' ),
						'type'        => 'slider',
						'class'       => 'zn_full',
						'std'        => '992',
						'helpers'     => array(
							'min' => '50',
							'max' => '2561'
						)
					),

					array(
						'id'          => 'background_color',
						'name'        => 'Background color',
						'description' => 'Here you can override the background color for this section.',
						'type'        => 'colorpicker',
						'alpha'       => true,
						'std'         => '',
						'live'        => array(
							'type'		=> 'css',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'background-color',
							'unit'		=> ''
						)
					),

					array(
						'id'          => 'zindex',
						'name'        => 'Custom Z-Index',
						'description' => 'Select a custom z-index of this sticky section element.',
						'type'        => 'text',
						'std'         => '',
						"numeric"        => true,
						'helpers'      => array(
							'step' => 1,
							'min' => -100,
							'max' => 100000,
						),
					),

					/**
					 *  padding
					 */
					array (
						"name"        => __( "Edit padding for each device breakpoint", 'zn_framework' ),
						"description" => __( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
						"id"          => "cc_spacing_breakpoints",
						"std"         => "lg",
						"tabs"        => true,
						"type"        => "zn_radio",
						"options"     => array (
							"lg"        => __( "LARGE", 'zn_framework' ),
							"md"        => __( "MEDIUM", 'zn_framework' ),
							"sm"        => __( "SMALL", 'zn_framework' ),
							"xs"        => __( "EXTRA SMALL", 'zn_framework' ),
						),
						"class"       => "zn_full zn_breakpoints"
					),

					// PADDINGS
					array(
						'id'          => 'cc_padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => array(
							'top' => '15px',
							'bottom' => '15px',
							),
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'padding',
						),
					),
					array(
						'id'          => 'cc_padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'cc_padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'cc_padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'cc_spacing_breakpoints' , 'value'=> array('xs') ),
					),


				),
			),

			'advanced' => array(
				'title' => 'Advanced options',
				'options' => array(

					array (
						"name"        => __( "Display \"Minimize\" button?", 'zn_framework' ),
						"description" => __( "Add a minimize button. The bar overlaps the footer and some parts might not be visible.", 'zn_framework' ),
						"id"          => "minimize",
						"std"         => "yes",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
					),

					array (
						"name"        => __( "Enable Sticky (Fixed) Footer Mode?", 'zn_framework' ),
						"description" => sprintf(
											__( "This option will force this sticky section to act like a sticky footer. The Index Layer will transform to '-100' and the '#page_wrapper' will add itself a margin-bottom similar to this section's height.<br><br>Don't forget to add a background-color to the page in <a href='%s' target='_blank'>Colors Option</a> !!", 'zn_framework' ),
											admin_url('admin.php?page=zn_tp_color_options')
											),
						"id"          => "sticky_footer",
						"std"         => "no",
						'type'        => 'zn_radio',
						'options'        => array(
							'yes' => __( "Yes", 'zn_framework' ),
							'no' => __( "No", 'zn_framework' ),
						),
						'class'        => 'zn_radio--yesno',
						"dependency"  => array(
							array( 'element' => 'height' , 'value'=> array('custom_height') ),
							// array( 'element' => 'minimize' , 'value'=> array('no') )
						),
					),

				)
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#vcux4GW2ctg') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/section-and-columns/') ),
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),
		);

		return $options;

	}

	/**
	 * Output the element
	 * IMPORTANT : The UID needs to be set on the top parent container
	 */
	function element() {

		$uid = $this->data['uid'];
		$element_id = $this->opt('custom_id') ? $this->opt('custom_id') : $uid;

		$options = $this->data['options'];

		$section_classes = array();

		$section_classes[] = $uid;
		$section_classes[] = zn_get_element_classes($options);

		if ( empty( $this->data['content'] ) ) {
			$this->data['content'] = array ( ZNB()->frontend->addModuleToLayout( 'ZnColumn', array() , array(), 'col-sm-12' ) );
		}
		if($this->opt('sticky_footer', 'no') == 'yes'){
			$section_classes[] = 'zn_sticky_section-footerMode';
		}
		// Add pb mode class
		$section_classes[] = 'znPbEditor-'. (ZNB()->utility->isActiveEditor() ? 'enabled' : 'disabled');

		?>
		<section class="zn_sticky_section <?php echo implode(' ', $section_classes); ?>" id="<?php echo esc_attr( $element_id ); ?>" <?php echo zn_get_element_attributes($options); ?>>

			<div class="zn_sticky_section_size <?php echo esc_attr( $this->opt('size','container') );?>">
				<?php echo ZNB()->utility->getElementContainer(array(
					'cssClasses' => 'row '. $this->opt('gutter_size','')
				)); ?>
					<?php
						ZNB()->frontend->renderElements( $this->data['content'] );
					?>

				</div>
			</div>
			<?php if( $this->opt('minimize','yes') == 'yes' && !ZNB()->utility->isActiveEditor()){ ?>
			<span class="zn_sticky_section_minimize js-toggle-class" data-target=".<?php echo esc_attr( $uid ); ?>" data-target-class="is-minimized"><span class="glyphicon glyphicon-chevron-down"></span></span>
			<?php } ?>
		</section>
	<?php
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){

		$uid = $this->data['uid'];
		$css = '';
		$s_css = '';

		$cc_padding_lg = '';
		$cc_padding_lg_default = $this->opt('cc_padding_lg', array('top' => '15px', 'bottom' => '15px') );
		if( $cc_padding_lg_default['top'] != '15px' && $cc_padding_lg_default['bottom'] != '15px' ){
			$cc_padding_lg = $cc_padding_lg_default;
		}

		// Padding
		if( $cc_padding_lg || $this->opt('cc_padding_md', '' ) || $this->opt('cc_padding_sm', '' ) || $this->opt('cc_padding_xs', '' ) ){

			$css .= zn_push_boxmodel_styles(array(
					'selector' => '.'.$uid,
					'type' => 'padding',
					'lg' =>  $this->opt('cc_padding_lg', array('top' => '15px', 'bottom' => '15px') ),
					'md' =>  $this->opt('cc_padding_md', '' ),
					'sm' =>  $this->opt('cc_padding_sm', '' ),
					'xs' =>  $this->opt('cc_padding_xs', '' ),
				)
			);
		}

		$s_css .= $this->opt('background_color') ? 'background-color:'.$this->opt('background_color').';' : '';

		$zindex = $this->opt('zindex', '');

		if($zindex != '' && !ZNB()->utility->isActiveEditor()){
			$s_css .= 'z-index:'.$zindex.' !important;';
		}

		if ( !empty($s_css) )
		{
			$css .= '.zn_sticky_section.'.$uid.'{'.$s_css.'}';
		}

		// Custom Height
		if( $this->opt('height','auto') == 'custom_height' ) {
			$css .= zn_smart_slider_css( $this->opt( 'custom_height' ), '.'.$uid.'.zn_sticky_section' );

			// sticky footer
			if( $this->opt('sticky_footer', 'no') == 'yes' ){
				$css .= zn_smart_slider_css( $this->opt( 'custom_height' ), '#page_wrapper', 'margin-bottom' );
			}
		}

		$trigger = $this->opt('unstick', '992');
		$css .= '
		@media (max-width: '.($trigger-1).'px) {
			#page_wrapper {margin-bottom: auto !important;}
		}
		@media (min-width: '.$trigger.'px) {
			.'.$uid.'.zn_sticky_section.znPbEditor-disabled {position: fixed;}
			#page_wrapper {z-index: auto;}
			.zn_sticky_section_minimize {display:block}
		}';

		return $css;
	}

}
