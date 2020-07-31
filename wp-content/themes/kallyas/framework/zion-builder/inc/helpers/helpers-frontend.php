<?php

/**
 * Create CSS of Boxmodel option-types
 */
if ( !function_exists( 'zn_push_boxmodel_styles' ) )
{
	function zn_push_boxmodel_styles( $args = array() )
	{

		$css = '';

		$defaults = array(
			'selector' => '',
			'type' => 'position',
			'lg' => array(),
			'md' => array(),
			'sm' => array(),
			'xs' => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args[ 'selector' ] ) )
		{
			return;
		}

		$brp = array( 'lg', 'md', 'sm', 'xs' );

		foreach ( $brp as $k )
		{

			if ( isset( $args[ $k ] ) && !empty( $args[ $k ] ) )
			{

				$brp_css = zn_add_boxmodel( $args[ $k ], $args[ 'type' ] );

				if ( !empty( $brp_css ) )
				{
					$mq = zn_wrap_mediaquery( $k, $args[ 'selector' ] );
					$css .= $mq[ 'start' ];
					if ( !empty( $brp_css ) )
					{
						$css .= $brp_css;
					}
					$css .= $mq[ 'end' ];
				}
			}
		}

		return $css;
	}
}


/**
 * Prepare individual sides css for boxmodel fields
 */
if ( !function_exists( 'zn_add_boxmodel' ) )
{
	function zn_add_boxmodel( $boxmodel_val = '', $type = 'position' )
	{
		$boxmodel_css = $before = $after = '';

		if ( $type == 'position' )
		{
			$before = '';
		}
		elseif ( $type == 'border-width' )
		{
			$before = 'border-';
			$after = '-width';
		}
		else {
			$before = $type . '-';
		}

		if ( is_array( $boxmodel_val ) )
		{
			foreach ( $boxmodel_val as $edge => $val )
			{
				if ( $val != '' && $edge != 'linked' )
				{
					if ( is_numeric( $val ) )
					{
						$val = $val . 'px';
					}
					$boxmodel_css .= $before . $edge . $after . ':' . $val . ';';
				}
			}
		}
		return $boxmodel_css;
	}
}


/**
 * Wrap CSS into media query
 */
if ( !function_exists( 'zn_wrap_mediaquery' ) )
{
	function zn_wrap_mediaquery( $breakpoint = 'lg', $selector = '' )
	{

		$mq = array();
		$mq[ 'start' ] = '';
		$mq[ 'end' ] = '';

		if ( !empty( $selector ) )
		{
			$selector = $selector . '{';
		}

		$mq_lg = $selector;
		$mq_md = '@media screen and (min-width: 992px) and (max-width: 1199px){';
		$mq_sm = '@media screen and (min-width: 768px) and (max-width:991px){';
		$mq_xs = '@media screen and (max-width: 767px){';
		$mq_end = '}';

		if ( $breakpoint == 'lg' )
		{
			$mq[ 'start' ] = $mq_lg;
			$mq[ 'end' ] = $mq_end;
		}
		elseif ( $breakpoint == 'md' )
		{
			$mq[ 'start' ] = $mq_md . $selector;
			$mq[ 'end' ] = $mq_end . $mq_end;
		}
		elseif ( $breakpoint == 'sm' )
		{
			$mq[ 'start' ] = $mq_sm . $selector;
			$mq[ 'end' ] = $mq_end . $mq_end;
		}
		elseif ( $breakpoint == 'xs' )
		{
			$mq[ 'start' ] = $mq_xs . $selector;
			$mq[ 'end' ] = $mq_end . $mq_end;
		}

		return $mq;
	}
}

/**
 * Get element classes that are globally applied in Misc. tab.
 */
if ( !function_exists( 'zn_get_element_classes' ) )
{
	function zn_get_element_classes( $options )
	{

		$classes = array();

		if ( !empty( $options ) )
		{

			// add breakpoint classes
			if ( array_key_exists( 'znpb_hide_breakpoint', $options ) )
			{
				foreach ( $options[ 'znpb_hide_breakpoint' ] as $breakpoint )
				{
					$classes[] = 'hidden-' . $breakpoint;
				}
			}

			// add custom element css class
			if ( array_key_exists( 'css_class', $options ) )
			{
				$classes[] = esc_attr($options[ 'css_class' ]);
			}

		}

		$classes = apply_filters('zn_filter_get_element_classes', $classes, $options);

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'zn_the_element_classes' ) ) {
	function zn_the_element_classes( $options ) {
		echo zn_get_element_classes( $options );
	}
}


/**
 * Get element attributes that are globally applied in Misc. tab.
 */
if ( !function_exists( 'zn_get_element_attributes' ) )
{
	function zn_get_element_attributes( $options, $id = '' )
	{

		$attributes = array();

		if ( !empty( $options ) )
		{
			// add custom element animation attribute
			if( array_key_exists( 'appear_animation', $options ) && $options[ 'appear_animation' ] != 'none' && array_key_exists( 'appear_delay', $options ) )
			{
				$attributes[] = 'data-anim-delay="' . $options[ 'appear_delay' ] . 'ms"';
			}

			// add id attribute
			if ( $id )
			{
				$attributes[] = 'id="' . esc_attr($id) . '"';
			}
		}

		$attributes = apply_filters('zn_filter_get_element_attributes', $attributes, $options);

		return implode( ' ', $attributes );
	}
}

if ( ! function_exists( 'zn_the_element_attributes' ) ) {
	function zn_the_element_attributes( $options, $id = '' ) {
		echo zn_get_element_attributes( $options, $id = '' );
	}
}


function znb_get_column_container($args){
	$classes = !empty( $args['cssClasses'] ) ? $args['cssClasses'] : '';

	$content = '<div';

		if( ZNB()->utility->isActiveEditor() ){
			$classes .= ' zn_sortable_content zn_content';
			$content .= ' data-droplevel="2"';
		}

		$content .= ! empty( $classes ) ? ' class="'. $classes .'"' : '';
	$content .= '>';

	return $content;
}


function znb_get_element_container($args){
	$classes = !empty( $args['cssClasses'] ) ? $args['cssClasses'] : '';

	$content = '<div';

		if( ZNB()->utility->isActiveEditor() ){
			$classes .= ' zn_columns_container zn_content';
			$content .= ' data-droplevel="1"';
		}

		$content .= ! empty( $classes ) ? ' class="'. $classes .'"' : '';

		if( ! empty( $args['attributes'] ) ){
			foreach ( $args['attributes'] as $name => $value ) {
				$content .= " $name=" . '"' . $value . '"';
			}
		}


	$content .= '>';

	return $content;
}


if ( !function_exists( 'zn_smart_slider_css' ) )
{
	/**
	 * Function to generate custom CSS based on breakpoints
	 */
	function zn_smart_slider_css( $opt, $selector, $def_property = 'height', $def_unit = 'px' )
	{
		$css = '';

		if ( is_array( $opt ) && !empty( $opt ) )
		{

			$breakp = isset( $opt[ 'breakpoints' ] ) ? $opt[ 'breakpoints' ] : '';
			$prop = isset( $opt[ 'properties' ] ) ? $opt[ 'properties' ] : $def_property;

			// Default Unit
			$unit_lg = isset( $opt[ 'unit_lg' ] ) ? $opt[ 'unit_lg' ] : $def_unit;

			if( $opt[ 'lg' ] != '' ){
				$css .= $selector . ' {' . $prop . ':' . $opt[ 'lg' ] . $unit_lg . ';}';
			}

			if ( !empty( $breakp ) )
			{
				if ( isset( $opt[ 'md' ] ) && !empty( $opt[ 'md' ] ) )
				{
					$unit_md = isset($opt[ 'unit_md' ]) ? $opt[ 'unit_md' ] : $def_unit;
					$md_val = $opt[ 'md' ] . $unit_md;
					if ( $opt[ 'md' ] == 'auto' )
					{
						$md_val = $opt[ 'md' ];
					}
					$css .= '@media (min-width:992px) and (max-width:1199px) {' . $selector . ' {' . $prop . ':' . $md_val . ';} }';
				}
				if ( isset( $opt[ 'sm' ] ) && !empty( $opt[ 'sm' ] ) )
				{
					$unit_sm = isset($opt[ 'unit_sm' ]) ? $opt[ 'unit_sm' ] : $def_unit;
					$sm_val = $opt[ 'sm' ] . $unit_sm;
					if ( $opt[ 'sm' ] == 'auto' )
					{
						$sm_val = $opt[ 'sm' ];
					}
					$css .= '@media (min-width:768px) and (max-width:991px) {' . $selector . ' {' . $prop . ':' . $sm_val . ';} }';
				}
				if ( isset( $opt[ 'xs' ] ) && !empty( $opt[ 'xs' ] ) )
				{
					$unit_xs = isset($opt[ 'unit_xs' ]) ? $opt[ 'unit_xs' ] : $def_unit;
					$xs_val = $opt[ 'xs' ] . $unit_xs;
					if ( $opt[ 'xs' ] == 'auto' )
					{
						$xs_val = $opt[ 'xs' ];
					}
					$css .= '@media (max-width:767px) {' . $selector . ' {' . $prop . ':' . $xs_val . ';} }';
				}
			}
		}
		else
		{
			if ( !empty( $opt ) )
			{
				$css .= $selector . ' {' . $def_property . ':' . $opt . $def_unit . ';}';
			}
		}

		return $css;
	}
}


function znb_make_text_editable( $content = '', $option_id = '' ){

	if( ! ZNB()->utility->isActiveEditor() ){
		return $content;
	}

	$unique_id = zn_uid( 'zneda_' );
	$output = '<div id="'.$unique_id.'" class="znhg-editable-area" data-optionid="'.$option_id.'">';

	// Output the content
	$output .= $content;

	$output .= '</div>';

	return $output;
}

/**
 * Add SVG Sprite definitions to the footer.
 */
function znb_include_svg_icons() {

	if ( file_exists( ZNB()->plugin_dir . '/assets/img/svg-icons.svg' ) ) {
		require_once( ZNB()->plugin_dir . '/assets/img/svg-icons.svg' );
	}
}
add_action( 'wp_footer', 'znb_include_svg_icons', 9999 );


/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
if ( !function_exists( 'znb_get_svg' ) )
{
	function znb_get_svg( $args = array() ) {

		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return __( 'Please define default parameters in the form of an array.', 'zn_framework' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return __( 'Please define an SVG icon filename.', 'zn_framework' );
		}

		// Set defaults.
		$defaults = array(
			'icon'        => '',
			'title'       => '',
			'desc'        => '',
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = ' aria-hidden="true"';

		// Set ARIA.
		$aria_labelledby = '';

		/*
		 * Example 1 with title: <?php echo znb_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
		 *
		 * Example 2 with title and description: <?php echo znb_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
		 *
		 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
		 */
		if ( $args['title'] ) {
			$aria_hidden     = '';
			$unique_id       = uniqid();
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

			if ( $args['desc'] ) {
				$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
			}
		}

		// Begin SVG markup.
		$svg = '<svg class="znb-icon znb-icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		// Display the title.
		if ( $args['title'] ) {
			$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

			// Display the desc only if the title is already set.
			if ( $args['desc'] ) {
				$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
			}
		}

		$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

		$svg .= '</svg>';

		return $svg;
	}
}


if ( !function_exists( 'znb_alignment_breakpoint_classes_output' ) )
{
	function znb_alignment_breakpoint_classes_output($brp = array()){

		if(empty($brp)) return;

		$classes = array();
		foreach( $brp as $k => $value ){
			if( !empty($value) )
				$classes[] = 'text-' . ($k != 'lg' ? $k . '-' : '' ) . $value;
		}
		return implode(' ',$classes);
	}
}


/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 *
 * <?php
 * $thumb = get_post_thumbnail_id();
 * $image = vt_resize($thumb, '', 140, 110, true);
 * ?>
 * <img src="<?php echo esc_url( $image[url] ); ?>" width="<?php echo esc_attr( $image[width] ); ?>" height="<?php echo esc_attr( $image[height] ); ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
*/
if ( !function_exists( 'znb_vt_resize' ) )
{
	/**
	 * @param null $attach_id
	 * @param null $img_url
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
	 *
	 * @return array
	 */
	function znb_vt_resize( $attach_id = null, $img_url = null, $width = 0, $height = 0, $crop = false )
	{
		if(!function_exists('mr_image_resize')) return $img_url;

		if ( $attach_id )
		{
			$img_url = wp_get_attachment_url( $attach_id );
		}
		$image = mr_image_resize( $img_url, $width, $height, $crop, 'c', false );

		if ( !empty( $image ) )
		{
			return $image;
		}
		else {
			return $img_url;
		}
	}
}

/**
 * Function to unite an array of classes used by breakpoint hide options
 * @param  array  $brp Array of breakpoint values, eg: array('lg', 'md')
 * @return string      String of united classes, eg: "hidden-lg hidden-md"
 */
if ( !function_exists( 'znb_breakpoint_classes_output' ) )
{
	function znb_breakpoint_classes_output($brp = array()){

		if(empty($brp)) return;

		$classes = array();
		foreach( $brp as $value ){
			$classes[] = 'hidden-' . $value;
		}
		return implode(' ',$classes);
	}
}


/**
 * Video Background Source
 * @supports Image, Selfhosted videos, Youtube Video, Vimeo Video, EmvedIframes
 * @param  array  $args Arguments
 * @return string       HTML markup
 *
 * TODO: If it's going to be moved into FW, all assets (JS & CSS) must be moved as well.
 *
 */
if( !function_exists('znb_background_source') ):
function znb_background_source( $args = array() )
{
	$defaults = array(
		'uid' => '',
		'source_type' => '',
		'source_background_image' => array(
			'image' => '',
			'repeat' => 'repeat',
			'attachment' => 'scroll',
			'position' => array(
				'x' => 'left',
				'y' => 'top'
			),
			'size' => 'auto',
		),
		'source_vd_yt' => '',
		'source_vd_vm' => '',
		'source_vd_self_mp4' => '',
		'source_vd_self_ogg' => '',
		'source_vd_self_webm' => '',
		'source_vd_embed_iframe' => '',
		'source_vd_vp' => '',
		'source_vd_autoplay' => 'yes',
		'source_vd_loop' => 'yes',
		'source_vd_muted' => 'yes',
		'source_vd_controls' => 'no',
		'source_vd_controls_pos' => 'bottom-right',
		'source_overlay' => 0,
		'source_overlay_color' => '',
		'source_overlay_opacity' => '',
		'source_overlay_color_gradient' => '',
		'source_overlay_color_gradient_opac' => '',
		'source_overlay_gloss' => '',
		'source_overlay_custom_css' => '',
		'enable_parallax' => '',
		'parallax_hack' => 'no',
		'mobile_play' => 'no',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters('znb_background_source_args', $args);

	$bg_source = '';
	$sourceType = $args['source_type'];
	$parallax = $sourceType == 'image' && $args['enable_parallax'] == 'yes';
	$bg_container_class='';

	if ( $sourceType ):

		// IMAGE
		if ( $sourceType == 'image' ) {
			$background_styles = array();
			$background_image = $args['source_background_image']['image'];

			if ( !empty( $background_image ) ) {

				if ( $parallax ) {
					$bg_container_class = 'zn-bgSource-imageParallax js-znParallax';
					if( isset($args['source_background_image']['attachment']) ){
						unset($args['source_background_image']['attachment']);
					}
				}

				$background_styles[] = 'background-image:url(' . $args['source_background_image']['image'] . ')';
				$background_styles[] = 'background-repeat:' . $args['source_background_image']['repeat'];
				$background_styles[] = 'background-position:' . $args['source_background_image']['position']['x'] . ' ' . $args['source_background_image']['position']['y'];
				$background_styles[] = 'background-size:' . $args['source_background_image']['size'];
				// Check if bg attachment was unset on parallax mode
				if( isset($args['source_background_image']['attachment']) ) {
					$background_styles[] = 'background-attachment:' . $args['source_background_image']['attachment'];
				}

				$bg_source .= '<div class="zn-bgSource-image" style="' . implode( ';', $background_styles ) . '"></div>';
			}
		}

		// SELF HOSTED // YOUTUBE // VIMEO
		else if ( $sourceType == 'video_self' || $sourceType == 'video_youtube' || $sourceType == 'video_vimeo' ) {

			$bg_source .= znb_VideoBg($args);

		}
		// IFRAME
		else if ( $sourceType == 'embed_iframe' ) {
			$source_vd_embed_iframe = $args['source_vd_embed_iframe'];
			$source_vd_vp = $args['source_vd_vp'];

			if ( !empty( $source_vd_embed_iframe ) ) {
				$video_attributes = array(
					'loop' => $args['source_vd_loop'] == 'yes' ? 1 : 0,
					'autoplay' => $args['source_vd_autoplay'] == 'yes' ? 1 : 0
				);
				$bg_source .= '<div class="js-object-fit-cover no-fitvids">';
					$bg_source .= '<div class="zn-bgSource-iframe embed-responsive embed-responsive-16by9">';
						$bg_source .= hgfw_get_video_from_link( $source_vd_embed_iframe, 'embed-responsive-item', '480', '270', $video_attributes );
						if(!empty($source_vd_vp)) {
							$bg_source .= '<div style="background-image:url('.$source_vd_vp.');" class="zn-bgSource-poster"></div>';
						}
					$bg_source .= '</div>';
				$bg_source .= '</div>';
			}
		}
	endif;

	// Overlays
	$bg_overlay = '';

	if ( $args['source_overlay'] != 0 ) {

		$overlay_color = $args['source_overlay_color'];
		$overlay_color_final = $overlay_color;

		// backwards compatibility, check if has separate opacity
		if(strpos($overlay_color, 'rgba') === false ){
			$overlay_opac = $args['source_overlay_opacity'];
			if( ! empty( $overlay_opac ) || $overlay_opac == '0' ){
				$overlay_color_final = zn_hex2rgba_str( $overlay_color, $overlay_opac );
			}
		}

		$ovstyle = 'background-color:' . $overlay_color_final;

		// Gradient
		if ( $args['source_overlay'] == 2 || $args['source_overlay'] == 3 ) {

			$gr_overlay_color = $args['source_overlay_color_gradient'];
			$gr_overlay_color_final = $gr_overlay_color;

			// backwards compatibility, check if has separate opacity
			if(strpos($gr_overlay_color, 'rgba') === false ){
				$overlay_gr_opac = $args['source_overlay_color_gradient_opac'];
				if( ! empty( $overlay_gr_opac ) || $overlay_gr_opac == '0' ){
					$gr_overlay_color_final = zn_hex2rgba_str( $gr_overlay_color, $overlay_gr_opac );
				}
			}

			// Gradient Horizontal
			if ( $args['source_overlay'] == 2 ) {
				$ovstyle = 'background: -webkit-linear-gradient(left, ' . $overlay_color_final . ' 0%,' . $gr_overlay_color_final . ' 100%); background: linear-gradient(to right, ' . $overlay_color_final . ' 0%,' . $gr_overlay_color_final . ' 100%); ';
			}
			// Gradient Vertical
			if ( $args['source_overlay'] == 3 ) {
				$ovstyle = 'background: -webkit-linear-gradient(top,  ' . $overlay_color_final . ' 0%,' . $gr_overlay_color_final . ' 100%); background: linear-gradient(to bottom,  ' . $overlay_color_final . ' 0%,' . $gr_overlay_color_final . ' 100%); ';
			}

		}

		// Custom CSS Gradient
		elseif ( $args['source_overlay'] == 4 ) {
			$custom_css_ov = $args['source_overlay_custom_css'];
			$custom_css_ov = preg_replace('!/\*.*?\*/!s', '', $custom_css_ov);
			$custom_css_ov = preg_replace('/\n\s*\n/', "", $custom_css_ov);
			$ovstyle = esc_attr($custom_css_ov);
		}

		$bg_overlay .= '<div class="zn-bgSource-overlay" style="' . $ovstyle . '"></div>';

	}

	// Gloss Overlays
	if ( $args['source_overlay_gloss'] == 1 ) {
		$bg_overlay .= '<div class="zn-bgSource-overlayGloss"></div>';
	}

	if ( $bg_source != '' || $bg_overlay != '' ) {
		echo '<div class="zn-bgSource '.$bg_container_class.'"  >';
			echo $bg_source;
			echo $bg_overlay;
		echo '</div>';
	}

	// Display mobile play video button
	if($args['mobile_play'] == 'yes'){

		$mp_video = '';

		if($sourceType == 'embed_iframe'){
			$mp_video = $args['source_vd_embed_iframe'];
		}
		elseif($sourceType == 'video_youtube'){
			$mp_video = $args['source_vd_yt'];
		}
		elseif($sourceType == 'video_vimeo'){
			$mp_video = $args['source_vd_vm'];
		}

		if(!empty($mp_video)){
			echo '<a href="'.$mp_video.'" class="mfp-iframe zn-bgSource-videoModal visible-xs visible-sm" data-text="'. __('PLAY VIDEO', 'zn_framework') .'">'. znb_get_svg(array('icon'=>'znb_play')) .'</a>';
		}
	}

}
endif;


/**
 * Video Background component
 * @param  array  $args Arguments
 * @return string       HTML markup
 *
 * TODO: If it's going to be moved into FW, all assets (JS & CSS) must be moved as well.
 *
 */
if( !function_exists('znb_VideoBg') ):
function znb_VideoBg( $args = array() )
{
	$defaults = array(
		'wrapper_class' => '',
		'video_class' => '',
		'source_type' => '',
		'source_vd_yt' => '',
		'source_vd_vm' => '',
		'source_vd_self_mp4' => '',
		'source_vd_self_ogg' => '',
		'source_vd_self_webm' => '',
		'source_vd_vp' => '',
		'source_vd_autoplay' => 'yes',
		'source_vd_loop' => 'yes',
		'source_vd_muted' => 'yes',
		'source_vd_controls' => 'no',
		'source_vd_controls_pos' => 'bottom-right',
		'mobile_play' => 'no',
		'source_overlay' => ''
	);

	$args = wp_parse_args( $args, $defaults );

	$autoplay = $args['source_vd_autoplay'] == 'yes' ? true : false;
	$loop     = $args['source_vd_loop'] == 'yes' ? true : false;
	$muted    = $args['source_vd_muted'] == 'yes' ? true : false;

	$output = '<div class="zn-bgSource-video no-fitvids '.$args['wrapper_class'].'">';

	$video_options = array(
		"video_ratio" 		=> "1.7778",
		"loop" 				=> $loop,
		"autoplay"			=> $autoplay,
		"muted"				=> $muted,
		"controls"			=> $args['source_vd_controls'] == 'yes' ? true : false,
		"controls_position" => $args['source_vd_controls_pos'],
		"mobile_play" 		=> $args['mobile_play'],
		"fallback_image" 	=> $args['source_vd_vp'],
	);

	$classes[] = 'zn-videoBg';
	$classes[] = 'zn-videoBg--fadeIn';
	$classes[] = $args['video_class'];


	$classes[] = 'zn-videoBg-gridOverlay zn-videoBg-gridOverlay--' . $args['source_overlay'];

	// Self Hosted Video
	if($args['source_type'] == 'video_self'){

		$classes[] = 'zn-videoBg--video';

		if($args['source_vd_self_mp4'])
			$video_options['mp4'] = $args['source_vd_self_mp4'];

		if($args['source_vd_self_webm'])
			$video_options['webm'] = $args['source_vd_self_webm'];

		if($args['source_vd_self_ogg'])
			$video_options['ogg'] = $args['source_vd_self_ogg'];
	}

	// Youtube video
	if($args['source_type'] == 'video_youtube' && !empty($args['source_vd_yt'])){
		$classes[] = 'zn-videoBg--embed';
		$video_options['youtube'] = hgfw_grab_youtube_id( $args['source_vd_yt'] );
	}
	// Vimeo video
	if($args['source_type'] == 'video_vimeo' && !empty($args['source_vd_vm'])){
		$classes[] = 'zn-videoBg--embed';
		$video_options['vimeo'] = hgfw_grab_vimeo_id( $args['source_vd_vm'] );
	}

	// Output Video Call
	$output .= '<div class="'.zn_join_spaces($classes).'" data-video-setup=\''.json_encode($video_options).'\' '.zn_schema_markup('video').'></div>';

	// Show Poster if available
	if(!empty($args['source_vd_vp'])) {
		$output .= '<div style="background-image:url('.$args['source_vd_vp'].');" class="zn-bgSource-poster"></div>';
	}

	$output .= '</div>';

	return $output;
}
endif;


/**
 * Fallback for non-JS browsers using Animation entry options
 */
add_action('wp_head', 'znb_animateFallback', 10);
if( !function_exists('znb_animateFallback') ):
function znb_animateFallback(){
	echo '
	<!-- Fallback for animating in viewport -->
	<noscript>
		<style type="text/css" media="screen">
			.zn-animateInViewport {visibility: visible;}
		</style>
	</noscript>
	';
}
endif;




/* Animations helper */
add_filter( 'zn_pb_options', 'znb_animation_helper', 20 );
if ( !function_exists( 'znb_animation_helper' ) ){
	function znb_animation_helper( $options )
	{

		$default_options = array(
			array(
				'name'        => __('Appear animation', 'zn_framework'),
				'description' => __('Select the appear animation for this element when it comes into the viewport', 'zn_framework'),
				'id'          => 'appear_animation',
				'type'        => 'select',
				'std'         => 'none',
				'options'     => array(
					'none'        => 'None',
					'fadeIn'      => 'Fade In',
					'fadeInDown'  => 'Fade in down',
					'fadeInLeft'  => 'Fade in left',
					'fadeInRight' => 'Fade in right',
					'fadeInUp'    => 'Fade in up',
					'bounceIn'    => 'Bounce in',
					'zoomIn'      => 'zoomIn',
					'flipInX'     => 'flipInX',
					'flipInY'     => 'flipInY',
				),
			),
			array(
				'name'        => __('Animation Duration', 'zn_framework'),
				'description' => __('Select the type of duration.', 'zn_framework'),
				'id'          => 'appear_duration',
				'std'         => '1000',
				'type'        => 'select',
				'options'     => array(
					'500'  => __( "Fast (500ms)", 'zn_framework' ),
					'1000' => __( "Normal (1000ms)", 'zn_framework' ),
					'2000' => __( "Slow (2000ms)", 'zn_framework' ),
				),
			),
			array(
				'name'        => __('Animation Delay', 'zn_framework'),
				'description' => __('Enter the number of milliseconds the animation is delayed after the element comes into the viewport. Please note that some elements can contain multiple elements that will animate progressively based on this value.', 'zn_framework'),
				'id'          => 'appear_delay',
				'type' => 'slider',
				'std' => '700',
				'helpers' => array(
					'min' => '0',
					'max' => '3000',
					'step' => '100'
				),
			),
		);

		if ( isset( $options[ 'has_tabs' ] ) ) {
			if ( !empty( $options[ 'znpb_misc' ] ) ) {

				$restricted = false;
				if ( isset( $options[ 'restrict' ] ) && !empty( $options[ 'restrict' ] ) ) {
					if ( in_array( 'appear_animation', $options[ 'restrict' ] ) ) {
						$restricted = true;
					}
				}
				if ( !$restricted ) {
					$znpb_misc = $options[ 'znpb_misc' ];
					$znpb_misc_merged = array_merge( $znpb_misc[ 'options' ], $default_options );
					$options[ 'znpb_misc' ][ 'options' ] = $znpb_misc_merged;
				}
			}
		}
		else {
			$options = array_merge( $options, $default_options );
		}
		return $options;
	}
}


add_filter('zn_filter_get_element_classes', 'znb_animation_addclasses', 10, 2);
if ( !function_exists( 'znb_animation_addclasses' ) ) {
	function znb_animation_addclasses( $classes, $options )
	{
		if ( empty( $options ) ) return $classes;
		// add custom animation css class
		if ( array_key_exists( 'appear_animation', $options ) && $options[ 'appear_animation' ] != 'none' ){

			$classes[] = 'zn-animateInViewport';
			$classes[] = 'zn-anim-'.$options[ 'appear_animation' ];

			if ( array_key_exists( 'appear_duration', $options ) ){
				$classes[] = 'zn-anim-duration--'.$options[ 'appear_duration' ];
			}
		}

		return $classes;
	}
}

if ( !function_exists( 'zn_margin_padding_options' ) ){
function zn_margin_padding_options( $uid = '', $args = array() ){

	$def = array(
		'responsive' => true,
		'margin' => true,
		'padding' => true,
		'margin_selector' => '.'.$uid,
		'padding_selector' => '.'.$uid,
		'margin_lg_std' => '',
		'margin_md_std' => '',
		'margin_sm_std' => '',
		'margin_xs_std' => '',
		'padding_lg_std' => '',
		'padding_md_std' => '',
		'padding_sm_std' => '',
		'padding_xs_std' => '',
	);

	$args = wp_parse_args($args, $def);

	$options = array();

	if( $args['responsive'] ){
		$options[] = array (
			"name"        => __( "Edit element padding & margins for each device breakpoint. ", 'zn_framework' ),
			"description" => __( "This will enable you to have more control over the padding of the element on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
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
		);
	}

	if( $args['margin'] ){
		// MARGINS
		$options[] = array(
			'id'          => 'margin_lg',
			'name'        => __('Margin (Large Breakpoints)', 'zn_framework'),
			'description' => __('Select the margin (in percent % or px) for this element. Accepts negative margin.', 'zn_framework'),
			'type'        => 'boxmodel',
			'std'	  	=> $args['margin_lg_std'],
			'placeholder' => '0px',
			"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
			'live' => array(
				'type'		=> 'boxmodel',
				'css_class' => $args['margin_selector'],
				'css_rule'	=> 'margin',
			),
		);
		if( $args['responsive'] ){
			$options[] = array(
				'id'          => 'margin_md',
				'name'        => __('Margin (Medium Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_md_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
			);
			$options[] = array(
				'id'          => 'margin_sm',
				'name'        => __('Margin (Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_sm_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
			);
			$options[] = array(
				'id'          => 'margin_xs',
				'name'        => __('Margin (Extra Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_xs_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
			);
		}
	}

	if( $args['padding'] ){
		// PADDINGS
		$options[] = array(
			'id'          => 'padding_lg',
			'name'        => __('Padding (Large Breakpoints)', 'zn_framework'),
			'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
			'type'        => 'boxmodel',
			"allow-negative" => false,
			'std'	  => $args['padding_lg_std'],
			'placeholder' => '0px',
			"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('lg') ),
			'live' => array(
				'type'		=> 'boxmodel',
				'css_class' => $args['padding_selector'],
				'css_rule'	=> 'padding',
			),
		);
		if( $args['responsive'] ){
			$options[] = array(
				'id'          => 'padding_md',
				'name'        => __('Padding (Medium Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('md') ),
			);
			$options[] = array(
				'id'          => 'padding_sm',
				'name'        => __('Padding (Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('sm') ),
			);
			$options[] = array(
				'id'          => 'padding_xs',
				'name'        => __('Padding (Extra Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints' , 'value'=> array('xs') ),
			);
		}
	}

	return $options;
}
}

/**
 * Create CSS for typography option-types (with breakpoints)
 */
if ( !function_exists( 'zn_typography_css' ) )
{
	function zn_typography_css( $args = array() )
	{

		$css = '';
		$defaults = array(
			'selector' => '',
			'lg' => array(),
			'md' => array(),
			'sm' => array(),
			'xs' => array(),
		);
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args[ 'selector' ] ) )
		{
			return;
		}

		$brp = array( 'lg', 'md', 'sm', 'xs' );

		foreach ( $brp as $k )
		{

			if ( is_array( $args[ $k ] ) & !empty( $args[ $k ] ) )
			{

				$brp_css = '';
				foreach ( $args[ $k ] as $key => $value )
				{
					if ( $value != '' )
					{
						if ( $key == 'font-family' )
						{
							$brp_css .= $key . ':' . zn_convert_font( $value ) . ';';
						}
						else
						{
							$brp_css .= $key . ':' . $value . ';';
						}
					}
				}

				if ( !empty( $brp_css ) )
				{
					$mq = zn_wrap_mediaquery( $k, $args[ 'selector' ] );
					$css .= $mq[ 'start' ];
					if ( !empty( $brp_css ) )
					{
						$css .= $brp_css;
					}
					$css .= $mq[ 'end' ];
				}
			}
		}

		return $css;
	}
}

if ( !function_exists( 'zn_convert_font' ) )
{
	function zn_convert_font( $fontfamily )
	{

		$fonts = array(
			'arial' => 'Arial, sans-serif',
			'verdana' => 'Verdana, Geneva, sans-serif',
			'trebuchet' => '"Trebuchet MS", Helvetica, sans-serif',
			'georgia' => 'Georgia, serif',
			'times' => '"Times New Roman", Times, serif',
			'tahoma' => 'Tahoma, Geneva, sans-serif',
			'palatino' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'helvetica' => 'Helvetica, Arial, sans-serif'
		);

		if ( array_key_exists( $fontfamily, $fonts ) )
		{
			$fontfamily = $fonts[ $fontfamily ];
		}
		else
		{
			// Google Font
			$fontfamily = '"' . $fontfamily . '", Helvetica, Arial, sans-serif';
		}

		return $fontfamily;
	}
}


if ( !function_exists( 'zn_schema_markup' ) )
{
	/**
	 * Schema.org additions
	 * @param 	string 	Type of the element
	 * @return  string  HTML Attribute
	 */
	function zn_schema_markup($type, $echo = false) {

		if (empty($type)) return false;

		$disable = apply_filters('zn_schema_markup_disable', false);

		if($disable == true) return;

		$attributes = '';
		$attr = array();

		switch ($type) {
			case 'body':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WebPage';
				break;

			case 'header':
				$attr['role'] = 'banner';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPHeader';
				break;

			case 'nav':
				$attr['role'] = 'navigation';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/SiteNavigationElement';
				break;

			case 'title':
				$attr['itemprop'] = 'headline';
				break;

			case 'subtitle':
				$attr['itemprop'] = 'alternativeHeadline';
				break;

			case 'sidebar':
				$attr['role'] = 'complementary';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPSideBar';
				break;

			case 'footer':
				$attr['role'] = 'contentinfo';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPFooter';
				break;

			case 'main':
				$attr['role'] = 'main';
				$attr['itemprop'] = 'mainContentOfPage';
				if (is_search()) {
					$attr['itemtype'] = 'https://schema.org/SearchResultsPage';
				}

				break;

			case 'author':
				$attr['itemprop'] = 'author';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'person':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment':
				$attr['itemprop'] = 'comment';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/UserComments';
				break;

			case 'comment_author':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment_author_link':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				$attr['rel'] = 'external nofollow';
				break;

			case 'comment_time':
				$attr['itemprop'] = 'commentTime';
				$attr['itemscope'] = 'itemscope';
				$attr['datetime'] = get_the_time('c');
				break;

			case 'comment_text':
				$attr['itemprop'] = 'commentText';
				break;

			case 'author_box':
				$attr['itemprop'] = 'author';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'video':
				$attr['itemprop'] = 'video';
				$attr['itemtype'] = 'https://schema.org/VideoObject';
				break;

			case 'audio':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/AudioObject';
				break;

			case 'blog':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Blog';
				break;

			case 'blogpost':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Blog';
				break;

			case 'name':
				$attr['itemprop'] = 'name';
				break;

			case 'url':
				$attr['itemprop'] = 'url';
				break;

			case 'email':
				$attr['itemprop'] = 'email';
				break;

			case 'post_time':
				$attr['itemprop'] = 'datePublished';
				break;

			case 'post_content':
				$attr['itemprop'] = 'text';
				break;

			case 'creative_work':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/CreativeWork';
				break;

			case 'logo':
				$attr['itemprop'] = 'logo';
				break;
		}

		/**
		 * Filter to override or append attributes
		 * @var array
		 */
		$attr = apply_filters('zn_schema_markup_attributes', $attr);

		foreach ($attr as $key => $value) {
			$attributes.= $key . '="' . $value . '" ';
		}

		if ($echo) {
			echo $attributes;
		}
		else {
			return $attributes;
		}
	}
}

/**
 * Will return an array containing button styles
 * @return array The button option array
 */
function znb_get_button_option_styles(){

	$path = plugins_url() . '/zn_framework/assets/img/button_icons';
	$defaultStyles = array(
		array(
			'value' => 'btn-fullcolor',
			'name'  => __( 'Flat (main color)', 'zn_framework' ),
			'image' => $path .'/01.flatmaincolor.jpg'
		),
		array(
			'value' => 'btn-fullwhite',
			'name'  => __( 'Flat (white)', 'zn_framework' ),
			'image' => $path .'/03.flatwhite.jpg'
		),
		array(
			'value' => 'btn-fullblack',
			'name'  => __( 'Flat (black)', 'zn_framework' ),
			'image' => $path .'/04.flatblack.jpg'
		),
		array(
			'value' => 'btn-lined',
			'name'  => __( 'Lined (light)', 'zn_framework' ),
			'image' => $path .'/05.linedlight.jpg'
		),
		array(
			'value' => 'btn-lined lined-dark',
			'name'  => __( 'Lined (dark)', 'zn_framework' ),
			'image' => $path .'/06.lineddark.jpg'
		),
		array(
			'value' => 'btn-lined lined-gray',
			'name'  => __( 'Lined (gray)', 'zn_framework' ),
			'image' => $path .'/07.linedgrey.jpg'
		),
		array(
			'value' => 'btn-lined lined-custom',
			'name'  => __( 'Lined (main color)', 'zn_framework' ),
			'image' => $path .'/08.linedmaincolor.jpg'
		),
		array(
			'value' => 'btn-lined btn-custom-color',
			'name'  => __( 'Lined (custom color)', 'zn_framework' ),
			'image' => $path .'/09.linedcustomcolor.jpg'
		),
		array(
			'value' => 'btn-lined lined-full-light',
			'name'  => __( 'Lined-Full (light)', 'zn_framework' ),
			'image' => $path .'/10.linedfulllight.jpg'
		),
		array(
			'value' => 'btn-lined lined-full-dark',
			'name'  => __( 'Lined-Full (dark)', 'zn_framework' ),
			'image' => $path .'/11.linedfulldark.jpg'
		),
		array(
			'value' => 'btn-lined btn-skewed',
			'name'  => __( 'Lined-Skewed (light)', 'zn_framework' ),
			'image' => $path .'/12.linedskewedlight.jpg'
		),
		array(
			'value' => 'btn-lined btn-skewed lined-dark',
			'name'  => __( 'Lined-Skewed (dark)', 'zn_framework' ),
			'image' => $path .'/13.linedskeweddark.jpg'
		),
		array(
			'value' => 'btn-lined btn-skewed lined-gray',
			'name'  => __( 'Lined-Skewed (gray)', 'zn_framework' ),
			'image' => $path .'/14.linedskewedgrey.jpg'
		),
		array(
			'value' => 'btn-fullcolor btn-skewed',
			'name'  => __( 'Flat-Skewed (main color)', 'zn_framework' ),
			'image' => $path .'/15.flatskewedmaincolor.jpg'
		),
		array(
			'value' => 'btn-fullcolor btn-skewed btn-custom-color',
			'name'  => __( 'Flat-Skewed (custom color)', 'zn_framework' ),
			'image' => $path .'/16.flatskewedcustomcolor.jpg'
		),
		array(
			'value' => 'btn-fullwhite btn-skewed',
			'name'  => __( 'Flat-Skewed (white)', 'zn_framework' ),
			'image' => $path .'/17.flatskewedwhite.jpg'
		),
		array(
			'value' => 'btn-fullblack btn-skewed',
			'name'  => __( 'Flat-Skewed (black)', 'zn_framework' ),
			'image' => $path .'/18.flatskewedblack.jpg'
		),
		array(
			'value' => 'btn-fullcolor btn-bordered',
			'name'  => __( 'Flat Bordered (main color)', 'zn_framework' ),
			'image' => $path .'/19.flatborderdmaincolor.jpg'
		),
		array(
			'value' => 'btn-fullcolor btn-bordered btn-custom-color',
			'name'  => __( 'Flat Bordered (custom color)', 'zn_framework' ),
			'image' => $path .'/20.flatborderdcustomcolor.jpg'
		),
		array(
			'value' => 'btn-default',
			'name'  => __( 'Bootstrap - Default', 'zn_framework' ),
			'image' => $path .'/21.boostrapdefault.jpg'
		),
		array(
			'value' => 'btn-primary',
			'name'  => __( 'Bootstrap - Primary', 'zn_framework' ),
			'image' => $path .'/22.boostrapprimary.jpg'
		),
		array(
			'value' => 'btn-success',
			'name'  => __( 'Bootstrap - Success', 'zn_framework' ),
			'image' => $path .'/23.boostrapinfo.jpg'
		),
		array(
			'value' => 'btn-info',
			'name'  => __( 'Bootstrap - Info', 'zn_framework' ),
			'image' => $path .'/24.boostrapsuccess.jpg'
		),
		array(
			'value' => 'btn-warning',
			'name'  => __( 'Bootstrap - Warning', 'zn_framework' ),
			'image' => $path .'/25.boostrapwarning.jpg'
		),
		array(
			'value' => 'btn-danger',
			'name'  => __( 'Bootstrap - Danger', 'zn_framework' ),
			'image' => $path .'/26.boostrapdanger.jpg'
		),
		array(
			'value' => 'btn-link',
			'name'  => __( 'Bootstrap - Link', 'zn_framework' ),
			'image' => $path .'/27.boostraplink.jpg'
		),
		array(
			'value' => 'btn-text',
			'name'  => __( 'Simple linked text', 'zn_framework' ),
			'image' => $path .'/28.simplelinktext.jpg'
		),
		array(
			'value' => 'btn-text btn-custom-color',
			'name'  => __( 'Simple linked text (Custom Color)', 'zn_framework' ),
			'image' => $path .'/29.simplelinktextcustom.jpg'
		),
		array(
			'value' => 'btn-underline btn-underline--thin',
			'name'  => __( 'Simple Underline Thin', 'zn_framework' ),
			'image' => $path .'/30.simpleunderlinethin.jpg'
		),
		array(
			'value' => 'btn-underline btn-underline--thick',
			'name'  => __( 'Simple Underline Thick', 'zn_framework' ),
			'image' => $path .'/31.simpleunderlinethick.jpg'
		),
	);
	return apply_filters( 'znb_get_button_option_styles', $defaultStyles );
}
