<?php

if( !function_exists( 'znpb_get_helptab' ) ){
	function znpb_get_helptab( $data ){

		$help_tab = array(
			'title' => '<span class="dashicons dashicons-editor-help"></span> HELP',
			'options' => array(),
		);

		// Video tutorials
		if( ! empty( $data['video'] ) ){
			$help_tab['options'][] = array (
				"name"        => __( 'Video Tutorial', 'zn_framework' ),
				"description" => '<span class="dashicons dashicons-video-alt3 u-v-mid"></span> <a href="'. esc_url( $data['video'] ) .'" target="_blank">'. __( 'Click here to access video tutorial for this element.', 'zn_framework' ).'</a>',
				"id"          => "video_link",
				"std"         => "",
				"type"        => "zn_title",
				"class"       => "zn_full zn_nomargin"
			);
		}

		// Written documentation
		if( ! empty( $data['docs'] ) ){
			$help_tab['options'][] = array (
				"name"        => __( 'Written Documentation', 'zn_framework' ),
				"description" => '<span class="dashicons dashicons-format-aside u-v-mid"></span> <a href="'. esc_url( $data['docs'] ) .'" target="_blank">'. __( 'Click here to access documentation for this element.', 'zn_framework' ).'</a>',
				"id"          => "docs_link",
				"std"         => "",
				"type"        => "zn_title",
				"class"       => "zn_full zn_nomargin"
			);
		}

		// Copy link
		if( ! empty( $data['copy'] ) ){
			$copy_text  = __( 'Click to copy ID to clipboard', 'zn_framework' );
			$copy_text2 = __( 'Unique ID:', 'zn_framework' );
			$desc_text1 = __( 'In case you need some custom styling use as a css class selector', 'zn_framework' );
			$desc_text2 = __( 'Click to copy CSS class to clipboard', 'zn_framework' );

			$help_tab['options'][] = array (
				"name"        => '<span data-clipboard-text="'.$data['copy'].'" data-tooltip="'.$copy_text.'">'.$copy_text2.' '.$data['copy'].'</span> ',
				"description" => $desc_text1.' <span class="u-code" data-clipboard-text=".'.$data['copy'].' {  }" data-tooltip="'.$desc_text2.'">.'.$data['copy'].'</span> .',
				"id"          => "id_element",
				"std"         => "",
				"type"        => "zn_title",
				"class"       => "zn_full zn_nomargin"
			);
		}

		if( isset( $data['custom_id'] ) && $data['custom_id'] == true ){
			$help_tab['options'][] = array (
				'id'          => 'custom_id',
				'name'        => 'Custom ID',
				'description' => 'You can change this sections ID if you want a more generic one.',
				'std'         => '',
				'type'        => 'text'
			);
		}

		if( ! empty( $data['general'] ) ){
			$help_tab['options'][] = znpb_general_help_option();
		}

		return $help_tab;
	}

}

if( ! function_exists( 'znpb_general_help_option' ) ){
	function znpb_general_help_option( $css_class = null ){
		return array (
			"name"        => '<a href="'. esc_url( 'https://my.hogash.com/support/') .'" target="_blank"  class="zn-helplink zn-helplink-support">'.__( 'Support Dashboard', 'zn_framework').'</a> &nbsp; | &nbsp; <a href="'.esc_url('https://my.hogash.com/docs/').'" target="_blank" class="zn-helplink zn-helplink-docs">'.__( 'Kallyas Video Tutorials & Documentation', 'zn_framework').'</a> &nbsp; | &nbsp; <a href="'.esc_url('https://themeforest.net/item/kallyas-responsive-multipurpose-wordpress-theme/4091658').'" target="_blank" class="zn-helplink zn-helplink-rate stars-yellow">'.__( 'Rate Kallyas', 'zn_framework').' <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a> <a class="zn-helplink zn-helplink-ratehelp" href="http://hogash.d.pr/11vC3" target="_blank" data-tooltip="How to rate?"><span class="dashicons dashicons-editor-help"></span></a>',
			"id"          => "otherlinks",
			"std"         => "",
			"type"        => "zn_title",
			"class"       => "zn_full zn-custom-title-sm zn_nomargin $css_class"
		);
	}
}

if( ! function_exists( 'zn_options_doc_link_option' ) ){
	function zn_options_doc_link_option( $url, $default_args = array() ){
		$option = array (
			"name" => '<span class="dashicons dashicons-format-aside u-v-mid"></span> '.__( 'Written Documentation:', 'zn_framework' ).' <a href="'. esc_url( $url ) .'" target="_blank">'. __( 'Click here to access documentation for this options section.', 'zn_framework' ).'</a>',
			"id"          => "docs_link",
			"std"         => "",
			"type"        => "zn_title",
			"class"       => "zn_full zn-admin-helplink zn_nomargin"
		);

		return wp_parse_args( $option, $default_args );
	}
}


if( ! function_exists( 'zn_options_video_link_option' ) ){
	function zn_options_video_link_option( $url, $desc = false, $default_args = array() ){
		$option = array (
			"name" => '<span class="dashicons dashicons-video-alt3 u-v-mid"></span> '.__( 'Video Tutorials:', 'zn_framework' ).' <a href="'. esc_url( $url ) .'" target="_blank">'. $desc .'</a>',
			"id"          => "video_link",
			"std"         => "",
			"type"        => "zn_title",
			"class"       => "zn_full zn-admin-helplink zn_nomargin"
		);

		return wp_parse_args( $option, $default_args );
	}

}
