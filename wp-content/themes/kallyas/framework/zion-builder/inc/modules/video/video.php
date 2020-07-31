<?php if(! defined('ABSPATH')){ return; }

class ZNB_Video extends ZionElement
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
				'title' => __('General options','zn_framework'),
				'options' => array(

					array (
						"name"        => __( "Video Source Type", 'zn_framework' ),
						"description" => __( "Select the type of video source.", 'zn_framework' ),
						"id"          => "vb_video_source",
						"std"         => "external",
						"type"        => "select",
						"options"		=> array(
							"external_yt" => __("Youtube",'zn_framework'),
							"external_vim" => __("Vimeo",'zn_framework'),
							"selfhosted_clean" => __("Self hosted video (Clean browser player)",'zn_framework'),
							"selfhosted" => __("Self hosted video (MediaElement Player)",'zn_framework'),
							"external_other" => __("Other External Source",'zn_framework'),
						),
					),

					array (
						"name"        => __( "Video URL", 'zn_framework' ),
						"description" => __( "Please enter a link to your desired video ( Youtube, Vimeo or other ).", 'zn_framework' ),
						"id"          => "video_url",
						"std"         => "",
						"type"        => "text",
						"class"		=> "zn_input_xl",
						"placeholder" => "eg: https://www.youtube.com/watch?v=rKH4XjqZQiY",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt', 'external_vim', 'external_other') ),
					),

					/* LOCAL VIDEO */
					array(
						'name'        => __('Mp4 video source','zn_framework'),
						'description' => __('Add the MP4 video source for your local video','zn_framework'),
						'id'          => 'source_vd_self_mp4',
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/mp4',
							'button_title' => __('Add / Change mp4 video','zn_framework'),
						),
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('selfhosted','selfhosted_clean') ),
					),

					array(
						'id'          => 'source_vd_self_ogg',
						'name'        => __('Ogg/Ogv video source','zn_framework'),
						'description' => __('Add the OGG video source for your local video','zn_framework'),
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/ogg',
							'button_title' => __('Add / Change ogg video','zn_framework'),
						),
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('selfhosted','selfhosted_clean') ),
					),

					array(
						'id'          => 'source_vd_self_webm',
						'name'        => __('Webm video source','zn_framework'),
						'description' => __('Add the WEBM video source for your local video','zn_framework'),
						'type'        => 'media_upload',
						'std'         => '',
						'data'  => array(
							'type' => 'video/webm',
							'button_title' => __('Add / Change webm video','zn_framework'),
						),
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('selfhosted','selfhosted_clean') ),
					),

					array(
						'id'          => 'source_vd_autoplay',
						'name'        => __('Autoplay video?','zn_framework'),
						'description' => __('Enable autoplay for video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('selfhosted','selfhosted_clean', 'external_yt', 'external_vim') ),
					),

					array(
						'id'          => 'source_vd_loop',
						'name'        => __('Loop video?','zn_framework'),
						'description' => __('Enable looping the video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('selfhosted','selfhosted_clean', 'external_yt', 'external_vim') ),
					),

					array(
						'id'          => 'source_vd_controls',
						'name'        => __('Video controls','zn_framework'),
						'description' => __('Enable video controls?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt','selfhosted_clean') ),
					),

					/**
					 * Youtube Specific
					 */
					array(
						'id'          => 'source_vd_modestbranding',
						'name'        => __('Youtube Video - Modest branding','zn_framework'),
						'description' => __('Display modest branding for Youtube video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt') ),
					),

					array(
						'id'          => 'source_vd_autohide',
						'name'        => __('Youtube Video - Autohide branding','zn_framework'),
						'description' => __('Autohide branding for Youtube video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt') ),
					),
					array(
						'id'          => 'source_vd_showinfo',
						'name'        => __('Youtube Video - Show Info','zn_framework'),
						'description' => __('Hide various info for the youtube video?','zn_framework'),
						'std'         => '0',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt') ),
					),
					array(
						'id'          => 'source_vd_rel',
						'name'        => __('Youtube Video - Hide Related','zn_framework'),
						'description' => __('Hide related videos on the video ending?','zn_framework'),
						'std'         => '0',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_yt') ),
					),

					/**
					 * Vimeo Specific
					 */
					array(
						'id'          => 'source_vd_title',
						'name'        => __('Vimeo Video - Hide title','zn_framework'),
						'description' => __('Hide title for Vimeo video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_vim') ),
					),
					array(
						'id'          => 'source_vd_byline',
						'name'        => __('Vimeo Video - Hide Video uploader','zn_framework'),
						'description' => __('Hide video uploader (author) for Vimeo video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_vim') ),
					),
					array(
						'id'          => 'source_vd_portrait',
						'name'        => __('Vimeo Video - Hide Avatar','zn_framework'),
						'description' => __('Hide uploader avatar for Vimeo video?','zn_framework'),
						'std'         => '1',
						'type'        => 'zn_radio',
						"options"     => array (
							"1" => __( "Yes", 'zn_framework' ),
							"0"  => __( "No", 'zn_framework' )
						),
						"class"        => "zn_radio--yesno",
						"dependency"  => array( 'element' => 'vb_video_source' , 'value'=> array('external_vim') ),
					),

				),
			),

			'spacing' => array(
				'title' => 'SPACING',
				'options' => array(

					array(
						"name"        => __( "Size (Max-Width)", 'zn_framework' ),
						"description" => __( "Customize the size of this video.", 'zn_framework' ),
						"id"          => "max_width",
						'type'        => 'smart_slider',
						'std'        => '100',
						'helpers'     => array(
							'min' => '0',
							'max' => '2000'
						),
						'supports' => array('breakpoints'),
						'units' => array('%', 'px'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.'.$uid. ' .zn-videoInner',
							'css_rule'  => 'max-width',
							'unit'      => '%'
						),
					),

				),
			),


			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				// 'docs'    => 'https://my.hogash.com/documentation/video-box/',
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
		$classes[] = 'zn-video';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		echo '<div class="'.zn_join_spaces($classes).'" '.zn_join_spaces($attributes ).'>';
		echo '<div class="zn-videoInner u-m-auto">';

			$vb_video_source = $this->opt('vb_video_source', 'external');

			$vd_autoplay = $this->opt('source_vd_autoplay', '1');
			$vd_loop = $this->opt('source_vd_loop', '1');
			$vd_controls = $this->opt('source_vd_controls', '1');

			$video_attributes = array(
				'loop' => $vd_loop,
				'autoplay' => $vd_autoplay,
				'controls' => $vd_controls,

				// Youtube Specific
				'yt_modestbranding' => $this->opt('source_vd_modestbranding', '1'),
				'yt_autohide' => $this->opt('source_vd_autohide', '1'),
				'yt_showinfo' => $this->opt('source_vd_showinfo', '0'),
				'yt_rel' => $this->opt('source_vd_rel', '0'),

				// Vimeo Specific
				'vim_title' => $this->opt('source_vd_title', '0'),
				'vim_byline' => $this->opt('source_vd_byline', '0'),
				'vim_portrait' => $this->opt('source_vd_portrait', '1'),
			);

			$video_url = $this->opt('video_url','');

			// External embedded video
			if( ( $vb_video_source == 'external_yt' || $vb_video_source == 'external_vim' || $vb_video_source == 'external_other' ) && !empty($video_url)){

				echo '<div class="embed-responsive embed-responsive-16by9">';
					echo hgfw_get_video_from_link( $video_url, 'embed-responsive-item', '425', '240', $video_attributes );
				echo '</div>';

			}
			elseif( $vb_video_source == 'selfhosted' ) {

				$params = array();

				$vd_mp4 = $this->opt('source_vd_self_mp4','');
				$vd_ogg = $this->opt('source_vd_self_ogg','');
				$vd_webm = $this->opt('source_vd_self_webm','');

				$params[] = !empty($vd_mp4) ? 'mp4="'.$vd_mp4.'"' : '';
				$params[] = !empty($vd_ogg) ? 'ogv="'.$vd_ogg.'"' : '';
				$params[] = !empty($vd_webm) ? 'webm="'.$vd_webm.'"' : '';

				if(!empty($params)) {
					$params[] = 'autoplay="'.$vd_autoplay.'"';
					$params[] = 'loop="'.$vd_loop.'"';

					echo '[video '.implode(' ', $params).']';
				}

			}
			elseif( $vb_video_source == 'selfhosted_clean' ) {

				$vd_mp4 = $this->opt('source_vd_self_mp4','');
				$vd_ogg = $this->opt('source_vd_self_ogg','');
				$vd_webm = $this->opt('source_vd_self_webm','');

				echo '<div class="embed-responsive embed-responsive-16by9">';

					if( !empty($vd_mp4) || !empty($vd_ogg) || !empty($vd_webm)) {
						echo '<video class="embed-responsive-item" id="video-'.$uid.'" width="100%" preload="metadata" '.( $vd_autoplay == 1 ? 'autoplay="autoplay"':'' ).' '.( $vd_loop == 1 ? 'loop="loop"':'' ).' '.( $vd_controls == 1 ? 'controls="controls"':'' ).' >';
						if( !empty($vd_mp4) ) {
							echo '<source type="video/mp4" src="'.$vd_mp4.'">';
						}
						if( !empty($vd_webm)) {
							echo '<source type="video/webm" src="'.$vd_webm.'">';
						}
						if( !empty($vd_ogg)) {
							echo '<source type="video/ogg" src="'.$vd_ogg.'">';
						}
						echo '</video>';
					}
				echo '</div>';
			}
		echo '</div>';
		echo '</div>';

	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];

		if( $max_width = $this->opt('max_width','100') ){
			$css .= zn_smart_slider_css( $max_width, '.'.$uid.' .zn-videoInner', 'max-width', '%');
		}

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

ZNB()->elements_manager->registerElement( new ZNB_Video(array(
	'id' => 'ZnVideo',
	'name' => __('Video', 'zn_framework'),
	'description' => __('Video player or Image triggering a modal window with a Youtube or Vimeo video.', 'zn_framework'),
	'level' => 3,
	'category' => 'content, media',
	'legacy' => false,
	'keywords' => array('button', 'modal', 'player', 'youtube', 'vimeo', 'self hosted', 'embed', 'embedded'),
)));
