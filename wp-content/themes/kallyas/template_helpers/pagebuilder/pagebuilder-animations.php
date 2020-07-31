<?php

/* Animations helpers */

if ( !function_exists( 'zn_animations_scripts' ) ) {
	function zn_animations_scripts() {
		wp_enqueue_style( 'animate.css', THEME_BASE_URI . '/css/vendors/animate.min.css', '', ZN_FW_VERSION );
	}
}

if ( !function_exists( 'zn_animations_all' ) ) {
	function zn_animations_all($all) {
		$all_animations = array(
			'bounceInDown' => 'Bounce in down',
			'bounceInLeft' => 'Bounce in left',
			'bounceInRight' => 'Bounce in right',
			'bounceInUp' => 'Bounce in up',
			'fadeInDownBig' => 'Fade in down big',
			'fadeInLeftBig' => 'Fade in left big',
			'fadeInRightBig' => 'Fade in right big',
			'fadeInUpBig' => 'Fade in up big',
			'flip' => 'Flip',
			'lightSpeedIn' => 'lightSpeedIn',
			'rotateIn' => 'rotateIn',
			'rotateInDownLeft' => 'rotateInDownLeft',
			'rotateInDownRight' => 'rotateInDownRight',
			'rotateInUpLeft' => 'rotateInUpLeft',
			'rotateInUpRight' => 'rotateInUpRight',
			'rollIn' => 'rollIn',
			'zoomInDown' => 'zoomInDown',
			'zoomInLeft' => 'zoomInLeft',
			'zoomInRight' => 'zoomInRight',
			'zoomInUp' => 'zoomInUp',
			'slideInDown' => 'slideInDown',
			'slideInLeft' => 'slideInLeft',
			'slideInRight' => 'slideInRight',
			'slideInUp' => 'slideInUp',
			// Hidding animations
			'bounceOut' => 'Bounce out',
			'bounceOutDown' => 'Bounce out down',
			'bounceOutLeft ' => 'Bounce out left',
			'bounceOutRight' => 'Bounce out right',
			'bounceOutUp' => 'Bounce out up',
			'fadeOut' => 'Fade out',
			'fadeOutDown' => 'Fade out down',
			'fadeOutDownBig' => 'Fade out down big',
			'fadeOutLeft' => 'Fade out left',
			'fadeOutLeftBig' => 'Fade out left big',
			'fadeOutRight' => 'Fade out right',
			'fadeOutRightBig' => 'Fade out right big',
			'fadeOutUp' => 'Fade out up',
			'fadeOutUpBig' => 'Fade out up big',
			'lightSpeedOut' => 'lightSpeedOut',
			'rotateOut' => 'rotateOut',
			'rotateOutDownLeft' => 'rotateOutDownLeft',
			'rotateOutDownRight' => 'rotateOutDownRight',
			'rotateOutUpLeft' => 'rotateOutUpLeft',
			'rotateOutUpRight' => 'rotateOutUpRight',
			'hinge' => 'hinge',
			'zoomOut' => 'zoomOut',
			'zoomOutDown' => 'zoomOutDown',
			'zoomOutLeft' => 'zoomOutLeft',
			'zoomOutRight' => 'zoomOutRight',
			'zoomOutUp' => 'zoomOutUp',
			'slideOutDown' => 'slideOutDown',
			'slideOutLeft' => 'slideOutLeft',
			'slideOutRight' => 'slideOutRight',
			'slideOutUp' => 'slideOutUp',
			'flipOutX' => 'flipOutX',
			'flipOutY' => 'flipOutY',
			'rollOut' => 'rollOut',
		);

		return array_merge($all, $all_animations);
	}
}

add_action( 'init', 'zn_enable_animations' );
if ( !function_exists( 'zn_enable_animations' ) )
{
	function zn_enable_animations()
	{
		if ( zget_option( 'zn_animations', 'layout_options', false, 'no' ) == 'yes' )
		{
			add_action( 'wp_enqueue_scripts', 'zn_animations_scripts', 20 );
			add_filter( 'zn_animations_list', 'zn_animations_all', 20 );
		}
	}
}


// remove animations from builder
add_action( 'znb:init', 'znkl_remove_default_animations' );
function znkl_remove_default_animations(){
	remove_filter( 'zn_pb_options', 'znb_animation_helper', 20);
	remove_filter('zn_filter_get_element_classes', 'znb_animation_addclasses', 10);
}

/* Animations helper */
add_filter( 'zn_pb_options', 'kallyas_elements_animation_options', 20, 2 );
if ( !function_exists( 'kallyas_elements_animation_options' ) ) {
	function kallyas_elements_animation_options( $options, $data ) {

		$all_animations = apply_filters('zn_animations_list', array(
			'none'                => 'None',
			'zn-anim-fadeIn'      => 'Fade In',
			'zn-anim-fadeInDown'  => 'Fade in down',
			'zn-anim-fadeInLeft'  => 'Fade in left',
			'zn-anim-fadeInRight' => 'Fade in right',
			'zn-anim-fadeInUp'    => 'Fade in up',
			'zn-anim-bounceIn'    => 'Bounce in',
			'zn-anim-zoomIn'      => 'zoomIn',
			'zn-anim-flipInX'     => 'flipInX',
			'zn-anim-flipInY'     => 'flipInY',
		));

		$anim_std = 'none';
		if( isset( $data['options']['appear_animation'] ) && strpos($data['options']['appear_animation'], 'wow') !== true ){
			$anim_std = 'zn-anim-' . str_replace(' wow', '', $data['options']['appear_animation']);
		}

		$default_options[] = array(
			'name'        => __('Appear animation', 'zn_framework'),
			'description' => __('Select the appear animation for this element when it comes into the viewport', 'zn_framework'),
			'id'          => 'appear_animation_v2',
			'type'        => 'select',
			'std'         => $anim_std,
			'options' => $all_animations,
		);

		$default_options[] = array(
			'name'        => __('Animation Duration', 'zn_framework'),
			'description' => __('Select the type of duration.', 'zn_framework'),
			'id'          => 'appear_duration',
			'std'         => '1000',
			'type'        => 'select',
			'options'     => array(
				'500'  => __( "Fast (500ms)", 'zn_framework' ),
				'1000' => __( "Normal (1000ms)", 'zn_framework' ),
				'2000' => __( "Slow (1000ms)", 'zn_framework' ),
			),
		);

		$default_options[] = array(
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
		);

		if ( isset( $options[ 'has_tabs' ] ) )
		{

			if ( !empty( $options[ 'znpb_misc' ] ) )
			{

				$restricted = false;
				if ( isset( $options[ 'restrict' ] ) && !empty( $options[ 'restrict' ] ) )
				{
					if ( in_array( 'appear_animation', $options[ 'restrict' ] ) )
					{
						$restricted = true;
					}
				}

				if ( !$restricted )
				{
					$znpb_misc = $options[ 'znpb_misc' ];
					$znpb_misc_merged = array_merge( $znpb_misc[ 'options' ], $default_options );
					$options[ 'znpb_misc' ][ 'options' ] = $znpb_misc_merged;
				}

			}
		}
		else
		{
			$options = array_merge( $options, $default_options );
		}

		return $options;

	}
}

/**
 * Add built-in animations for elements
 */
add_filter('zn_filter_get_element_classes', 'kallyas_animation_addclasses', 10, 2);
if ( !function_exists( 'kallyas_animation_addclasses' ) ){
	function kallyas_animation_addclasses( $classes, $options )
	{
		if ( empty( $options ) ) return $classes;

		$anim = '';
		// backwards compatiblity
		if( array_key_exists( 'appear_animation_v2', $options ) ){
			$anim = $options[ 'appear_animation_v2' ];
		}
		elseif (array_key_exists( 'appear_animation', $options ) && !in_array( $options[ 'appear_animation' ], array('none', 'wow no_animation', 'no_animation'))){
			$anim = $options[ 'appear_animation' ];
			if( strpos($anim, 'wow') !== true ){
				$anim = 'zn-anim-' . str_replace(' wow', '', $anim);
			}
		}

		// add custom animation css class
		if ( !empty($anim) && !in_array( $anim, array('none', 'wow no_animation', 'no_animation')) ){

			$classes[] = 'zn-animateInViewport';
			$classes[] = $anim;

			$classes[] = 'zn-anim-duration--'.( array_key_exists( 'appear_duration', $options ) ? $options[ 'appear_duration' ] : 1000);
		}

		return $classes;
	}
}

add_filter('zn_filter_get_element_attributes', 'kallyas_animation_delay_attribute', 10, 2);
if( !function_exists('kallyas_animation_delay_attribute') ){
	function kallyas_animation_delay_attribute( $attributes, $options )
	{
		if ( !empty( $options ) )
		{
			// add custom element animation attribute
			if (
				(
					// old appear animation
					( array_key_exists( 'appear_animation', $options ) && !in_array( $options[ 'appear_animation' ], array('none', 'wow no_animation', 'no_animation')) )
					// new appear animation
					|| (array_key_exists( 'appear_animation_v2', $options ) && $options[ 'appear_animation_v2' ] != 'none' )
				)
				&& array_key_exists( 'appear_delay', $options )
			)
			{
				$attributes[] = 'data-anim-delay="' . $options[ 'appear_delay' ] . 'ms"';
			}
		}
		return $attributes;
	}
}