<?php if(! defined('ABSPATH')){ return; }
/*
 Name: Stage Box
 Description: Create and display an Stage Image Box element. To be used with Icon Boxes.
 Class: TH_StageImageBox
 Category: content, media
 Level: 3
*/
/**
 * Class TH_StageImageBox
 *
 * Create and display an Stage Image Box element containing an image with tooltips. To be used with Icon Boxes.
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_StageImageBox extends ZnElements
{
	public static function getName(){
		return __( "Stage Image Box", 'zn_framework' );
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		$padding_std_lg = array(
			'top' => ( isset($this->data['options']['top_padding']) && !empty($this->data['options']['top_padding']) ? $this->data['options']['top_padding'] : '' ),
			'bottom' => ( isset($this->data['options']['bottom_padding']) && !empty($this->data['options']['bottom_padding']) ? $this->data['options']['bottom_padding'] : '' ),
		);

		// Margin
		$margins = array();
		$margins['lg'] = $this->opt('margin_lg', '' ) ?  $this->opt('margin_lg', '' ) : '';
		$margins['md'] = $this->opt('margin_md', '' ) ?  $this->opt('margin_md', '' ) : '';
		$margins['sm'] = $this->opt('margin_sm', '' ) ?  $this->opt('margin_sm', '' ) : '';
		$margins['xs'] = $this->opt('margin_xs', '' ) ?  $this->opt('margin_xs', '' ) : '';
		if( !empty($margins) ){
			$margins['selector'] = '.'.$uid;
			$margins['type'] = 'margin';
			$css .= zn_push_boxmodel_styles( $margins );
		}

		// Padding
		$paddings = array();
		$paddings['lg'] = ($this->opt('padding_lg', $padding_std_lg ) ?  $this->opt('padding_lg', $padding_std_lg ) : '');
		$paddings['md'] = $this->opt('padding_md', '' ) ?  $this->opt('padding_md', '' ) : '';
		$paddings['sm'] = $this->opt('padding_sm', '' ) ?  $this->opt('padding_sm', '' ) : '';
		$paddings['xs'] = $this->opt('padding_xs', '' ) ?  $this->opt('padding_xs', '' ) : '';
		if( !empty($paddings) ){
			$paddings['selector'] = '.'.$uid.' .stage-ibx__stage';
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}



		$ibstg_points_color = $this->opt('ibstg_points_color', '#FFFFFF');
		$ibstg_points_style = $this->opt('ibstg_points_style', 'trp');

		if($ibstg_points_style == 'trp') {
			$css .= ".$uid.stage-ibx--points-trp .stage-ibx__point:after {background: ".zn_hex2rgba_str($ibstg_points_color, 60)."; box-shadow: 0 0 0 3px ".$ibstg_points_color."; }
			.$uid.stage-ibx--points-trp .stage-ibx__point:hover:after, .$uid.stage-ibx--points-trp .stage-ibx__point.is-hover:after { box-shadow: 0 0 0 5px ".$ibstg_points_color.", 0 4px 10px #000; } ";

		} elseif($ibstg_points_style == 'full') {
			$css .= ".$uid.stage-ibx--points-full .stage-ibx__point:after {background: ".$ibstg_points_color.";}
			.$uid.stage-ibx--points-full .stage-ibx__point:hover:after, .$uid.stage-ibx--points-full .stage-ibx__point.is-hover:after {background: ".adjustBrightness($ibstg_points_color, 20).";} ";
		}

		return $css;
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$uid = $this->data['uid'];
		$options = $this->data['options'];

		$classes=array();
		$classes[] = $uid = $this->data['uid'];
		$classes[] = zn_get_element_classes($options);

		$classes[] = 'stage-ibx--points-'.$this->opt('ibstg_points_style', 'trp');

		$source = $this->opt('source', 'ibx');
		$classes[] = 'stage-ibx--src-'.$source;

?>

<div class="stage-ibx <?php echo implode(' ', $classes); ?>" <?php zn_the_element_attributes($options); ?>>

	<?php if($img = $this->opt('ibstg_stageimg','')){

		$saved_alt   = $this->opt('ibstg_stageimg_alt','') ? $this->opt('ibstg_stageimg_alt','') : ZngetImageAltFromUrl( $img, false );
		$saved_title = ZngetImageTitleFromUrl( $img, false );

		?>
	<div class="stage-ibx__stage">
		<img src="<?php echo esc_url( $img ); ?>" <?php echo ZngetImageSizesFromUrl($img, true); ?> alt="<?php echo esc_attr( $saved_alt ); ?>" title="<?php echo esc_attr( $saved_title ); ?>" class="stage-ibx__stage-img img-responsive">

		<?php
			if($source == 'custom'){
				$single = $this->opt('single');

				if( !empty($single) ){
					foreach ($single as $i => $pt) {

						$styles_left = (isset($pt['point_x']) && $pt['point_x'] != '' ? intval($pt['point_x']) : 10 );
						$styles_top = (isset($pt['point_y']) && $pt['point_y'] != '' ? intval($pt['point_y']) :10 );
						$unit = ( isset($pt['point_measure_unit']) && in_array($pt['point_measure_unit'], array('pixel', 'percent')) ? $pt['point_measure_unit'] : 'pixel' );
						$unit = ( ('pixel' == $unit) ? 'px' : '%' );

						$styles_left .= $unit;
						$styles_top  .= $unit;

						$attrs[$i][] = "style='left:{$styles_left}; top:{$styles_top};'";
						$attrs[$i][] = (isset($pt['point_title']) && $pt['point_title'] != '' ? 'data-title="'.$pt['point_title'].'"':'');
						$attrs[$i][] = (isset($pt['point_nr']) && $pt['point_nr'] != '' ? 'data-nr="'.$pt['point_nr'].'"':'');

						echo '<span class="stage-ibx__point" '. implode($attrs[$i], ' ') .'></span>';
					}
				}
			}
		?>

	</div><!-- /.stage-ibx__stage -->
	<?php } ?>

	<div class="clearfix"></div>

</div><!-- /.stage-ibx -->

<?php

	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		/**
		 * Legacy options
		 * @since 4.6
		 */
		$padding_std_lg = array(
			'top' => ( isset($this->data['options']['top_padding']) && !empty($this->data['options']['top_padding']) ? $this->data['options']['top_padding'] : '' ),
			'bottom' => ( isset($this->data['options']['bottom_padding']) && !empty($this->data['options']['bottom_padding']) ? $this->data['options']['bottom_padding'] : '' ),
		);

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Stage Image", 'zn_framework' ),
						"description" => __( "Upload an image that will be placed in the middle", 'zn_framework' ),
						"id"          => "ibstg_stageimg",
						"std"         => "",
						"type"        => "media"
					),

					array (
						"name"        => __( "Img Alt", 'zn_framework' ),
						"description" => __( "Add an alternative text for the image (SEO purposes). Deprecated! Use built-in media browser.", 'zn_framework' ),
						"id"          => "ibstg_stageimg_alt",
						"std"         => "",
						"type"        => "text"
					),

					array (
						"name"        => __( "Point Style", 'zn_framework' ),
						"description" => __( "The style of the points.", 'zn_framework' ),
						"id"          => "ibstg_points_style",
						"std"         => "trp",
						"type"        => "select",
						'options'     => array(
							'trp' => 'Bordered transparent',
							'full' => 'Colored',
						),
					),

					array (
						"name"        => __( "Point Color", 'zn_framework' ),
						"description" => __( "The color of the points.", 'zn_framework' ),
						"id"          => "ibstg_points_color",
						"std"         => "#FFFFFF",
						"type"        => "colorpicker",
					),
					/**
					 * Margins and padding
					 */
					array (
						"name"        => __( "Edit element padding & margins for each device breakpoint. ", 'zn_framework' ),
						"description" => __( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
						"id"          => "spacing_breakpoints",
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
					// MARGINS
					array(
						'id'          => 'margin_lg',
						'name'        => 'Margin (Large Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container. Accepts negative margin.',
						'type'        => 'boxmodel',
						'std'	  => '',
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid,
							'css_rule'	=> 'margin',
						),
					),
					array(
						'id'          => 'margin_md',
						'name'        => 'Margin (Medium Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'margin_sm',
						'name'        => 'Margin (Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'margin_xs',
						'name'        => 'Margin (Extra Small Breakpoints)',
						'description' => 'Select the margin (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),
					// PADDINGS
					array(
						'id'          => 'padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => $padding_std_lg,
						'placeholder' => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.'.$uid.' .stage-ibx__stage',
							'css_rule'	=> 'padding',
						),
					),
					array(
						'id'          => 'padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
					),
					array(
						'id'          => 'padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
					),
					array(
						'id'          => 'padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						"allow-negative" => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
					),

				),
			),

			'points' => array(
				'title' => 'Points',
				'options'=> array(

					array (
						"name"        => __( "Points Source", 'zn_framework' ),
						"description" => __( "Select the source of the points inside this element", 'zn_framework' ),
						"id"          => "source",
						"std"         => "ibx",
						'type'        => 'zn_radio',
						'options'        => array(
							'ibx' => __( "Icon Boxes", 'zn_framework' ),
							'custom' => __( "Custom Defined", 'zn_framework' ),
						),
					),

					array (
						"name"        => sprintf(__( '<span data-clipboard-text="%s" data-tooltip="Click to copy ID to clipboard">Unique ID: %s</span> ', 'zn_framework' ), $uid, $uid),
						"description" => sprintf(__( 'In case you need some custom styling use as a css class selector <span class="u-code" data-clipboard-text=".%s {  }" data-tooltip="Click to copy CSS class to clipboard">.%s</span> . To learn to use this feature, please ', 'zn_framework' ), $uid, $uid),
						"id"          => "id_element",
						"std"         => "",
						"type"        => "zn_title",
						"class"       => "zn_full zn_nomargin",
						"dependency"  => array( 'element' => 'source' , 'value'=> array('ibx') ),
					),

					array(
						"name"           => __( "Point", 'zn_framework' ),
						"description"    => __( "Add Point.", 'zn_framework' ),
						"id"             => "single",
						"element_title" => "point_text",
						"std"            => '',
						"type"           => "group",
						"add_text"       => __( "Point", 'zn_framework' ),
						"remove_text"    => __( "Point", 'zn_framework' ),
						"dependency"  => array( 'element' => 'source' , 'value'=> array('custom') ),
						// "group_sortable" => true,
						"subelements"    => array (

							array (
								"name"        => __( "Point [ X ] Coordinate", 'zn_framework' ),
								"description" => __( "This will add an animated dot onto the stage image with the X (distance from left) coordinates you provide. ", 'zn_framework' ),
								"id"          => "point_x",
								"std"         => "10",
								"type"        => "text",
								"placeholder" => "ex: 100",
								"dragup"     => array(
									'min' => '0'
								),
								"class"       => "zn_input_xs"
							),
							array (
								"name"        => __( "Point [ Y ] Coordinate", 'zn_framework' ),
								"description" => __( "This will add an animated dot onto the stage image with the Y (distance from top) coordinates you provide. ", 'zn_framework' ),
								"id"          => "point_y",
								"std"         => "10",
								"type"        => "text",
								"placeholder" => "ex: 100",
								"dragup"     => array(
									'min' => '0'
								),
								"class"       => "zn_input_xs"
							),
							array (
								"name"        => __( "Measure unit", 'zn_framework' ),
								"description" => __( "Please select the measurement unit to use when positioning the points.", 'zn_framework' ),
								"id"          => "point_measure_unit",
								"std"         => "px",
								"type"        => "select",
								'options'     => array(
									'pixel' => __('Pixels', 'zn_framework'),
									'percent' => __( 'Percentage', 'zn_framework'),
								),
							),

							array (
								"name"        => __( "Point Tooltip", 'zn_framework' ),
								"description" => __( "Add a custom tooltip text. Leave empty if you don't want to display a tooltip.", 'zn_framework' ),
								"id"          => "point_title",
								"std"         => "",
								"type"        => "text",
								"class"        => "zn_input_xl",
							),

							array (
								"name"        => __( "Inside Point Symbol", 'zn_framework' ),
								"description" => __( "Add a custom symbol or digits to be displayed INSIDE the point.", 'zn_framework' ),
								"id"          => "point_nr",
								"std"         => "",
								"type"        => "text",
							),

						)
					),
				)

			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#Gyo1FWwBpzI') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/stage-image-box/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
