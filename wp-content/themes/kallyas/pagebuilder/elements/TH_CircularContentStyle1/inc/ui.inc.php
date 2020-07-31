<?php if(!defined('ABSPATH')) { return; }
	/*
	 * Build and display the element
	 */

	$options = (isset($GLOBALS['options']['circ_1']) ? $GLOBALS['options']['circ_1'] : null);
	if(empty($options)){
		return;
	}

	$elm_classes[] = $uid = $this->data['uid'];
	$elm_classes[] = zn_get_element_classes($options);
	$elm_classes[] = 'kl-slideshow circularcarousel__slideshow circularcarousel__slideshow--style1';

	$attributes = zn_get_element_attributes($options);

	$bottom_mask = $this->opt('hm_header_bmasks','none');
	$elm_classes[] = $bottom_mask != 'none' ? 'maskcontainer--'.$bottom_mask : '';

	$elm_classes[] = 'uh_' . $this->opt('ww_header_style', '');

	$countitm = 0;
	$singleItem = $options['single_circ1'];
	$hasItems = isset ( $singleItem ) && is_array( $singleItem );
	if($hasItems){
		$countitm = count($singleItem);
	}

	// Slick Attributes
	$slick_attributes = array(
		"infinite" => true,
		"slidesToShow" => (int)$this->opt('ww_slider_max',3),
		"slidesToScroll" => 1,
		"autoplay" => $this->opt('ww_slider_autoplay', '1') == 1 ? true : false,
		"autoplaySpeed" => (int)$this->opt('ww_slider_timeout', 9000),
		"easing" => 'easeInOutExpo',
		"arrows" => true,
		"appendArrows" => '.'. $uid . ' .znSlickNav',
		"responsive" => array(
			array(
				"breakpoint" => 1199,
				"settings" => array(
					"slidesToShow" => 3
				)
			),
			array(
				"breakpoint" => 767,
				"settings" => array(
					"slidesToShow" => 2
				)
			),
			array(
				"breakpoint" => 520,
				"settings" => array(
					"slidesToShow" => 1
				)
			)
		)
	);

?>
	<div class=" <?php echo esc_attr( implode( ' ', $elm_classes ) ); ?>" <?php zn_the_element_attributes( $options ); ?>>

		<div class="bgback"></div>
		<?php
			WpkPageHelper::zn_background_source( array(
				'source_type' => $this->opt('source_type'),
				'source_background_image' => $this->opt('background_image'),
				'source_vd_yt' => $this->opt('source_vd_yt'),
				'source_vd_vm' => $this->opt('source_vd_vm'),
				'source_vd_self_mp4' => $this->opt('source_vd_self_mp4'),
				'source_vd_self_ogg' => $this->opt('source_vd_self_ogg'),
				'source_vd_self_webm' => $this->opt('source_vd_self_webm'),
				'source_vd_vp' => $this->opt('source_vd_vp'),
				'source_vd_autoplay' => $this->opt('source_vd_autoplay'),
				'source_vd_loop' => $this->opt('source_vd_loop'),
				'source_vd_muted' => $this->opt('source_vd_muted'),
				'source_vd_controls' => $this->opt('source_vd_controls'),
				'source_vd_controls_pos' => $this->opt('source_vd_controls_pos'),
				'source_overlay' => $this->opt('source_overlay'),
				'source_overlay_color' => $this->opt('source_overlay_color'),
				'source_overlay_opacity' => $this->opt('source_overlay_opacity'),
				'source_overlay_color_gradient' => $this->opt('source_overlay_color_gradient'),
				'source_overlay_color_gradient_opac' => $this->opt('source_overlay_color_gradient_opac'),
				'mobile_play' => $this->opt('mobile_play', 'no'),
			) );
		?>
		<div class="th-sparkles"></div>

		<div class="kl-slideshow-inner <?php echo esc_attr( $this->opt('size','container') );?> kl-slideshow-safepadding">
			<div class="row">
				<div class="ca-container" data-count="<?php echo esc_attr( $countitm ); ?>">

					<div class="znSlickNav"></div>

					<div class="ca-wrapper ca-st1 js-slick" data-slick='<?php echo json_encode($slick_attributes); ?>' >

					<?php
						if ( $hasItems )
						{
							$i = 1;
							$linkEntireItemCssClass = ( $this->opt( 'link_entire_item', false ) == 'yes' ) ? 'js-ca-more' :  '';

							foreach ( $singleItem as $slide )
							{
								echo '<div class="ca-item ca-item-' . $i . ' '.$linkEntireItemCssClass.'">';

								echo '<div class="ca-item-main">';

								echo '<div class="ca-background"></div>';

								// if ( isset ( $slide['ww_slide_image'] ) && ! empty ( $slide['ww_slide_image'] ) ) {
								//     echo '<div class="ca-icon" style="background-image:url('.$slide['ww_slide_image'].');"></div>';
								// }
								if ( isset ( $slide['ww_slide_image'] ) && ! empty ( $slide['ww_slide_image'] ) ) {
									$sl_img = $slide['ww_slide_image'];
									echo '<img src="' . $sl_img . '" '.ZngetImageSizesFromUrl($sl_img, true).' class="ca-icon-img contain-fit-img" '.ZngetImageAltFromUrl($sl_img, true).' '.ZngetImageTitleFromUrl($sl_img, true).' >';
								 }

								// TITLE
								if ( isset ( $slide['ww_slide_title'] ) && ! empty ( $slide['ww_slide_title'] ) ) {
									echo '<h3 class="ca-title" '.WpkPageHelper::zn_schema_markup('title').'>' . $slide['ww_slide_title'] . '</h3>';
								}

								// DESC
								if ( isset ( $slide['ww_slide_desc'] ) && ! empty ( $slide['ww_slide_desc'] ) ) {
									echo '<div class="ca-text">' . $slide['ww_slide_desc'] . '</div>';
								}

								// DESC
								if ( isset ( $slide['ww_slide_read_text'] ) && ! empty ( $slide['ww_slide_read_text'] ) ) {
									echo '<a href="#" class="btn btn-fullcolor ca-more js-ca-more">' . $slide['ww_slide_read_text'] .
										 ' <span class="glyphicon glyphicon-chevron-right kl-icon-white"></span></a>';
								}
								// Bottom Title
								if ( isset ( $slide['ww_slide_bottom_title'] ) && ! empty ( $slide['ww_slide_bottom_title'] ) ) {
									echo '<span class="ca-starting">' . $slide['ww_slide_bottom_title'] . '</span>';
								}

								echo '</div>';

								echo '<div class="ca-content-wrapper">';

								echo '<a href="#" class="ca-close js-ca-close"><span class="glyphicon glyphicon-remove"></span></a>';

								echo '<div class="ca-content">';

								// Content Title
								if ( isset ( $slide['ww_slide_content_title'] ) && ! empty ( $slide['ww_slide_content_title'] ) ) {
									echo '<h6 class="ca-panel-title">' . $slide['ww_slide_content_title'] . '</h6>';
								}

								// Content description
								if ( isset ( $slide['ww_slide_desc_full'] ) && ! empty ( $slide['ww_slide_desc_full'] ) ) {

									$content = wpautop( $slide['ww_slide_desc_full'] );
									echo '<div class="ca-content-text">';
										if ( preg_match( '%(<[^>]*>.*?</)%i', $content, $regs ) ) {
											echo do_shortcode( $content );
										}
										else {
											echo '<p>' . do_shortcode( $content ) . '</p>';
										}
									echo '</div>';
								}

								// Link
								if ( isset($slide['ww_slide_read_text_content']) && ! empty($slide['ww_slide_read_text_content']) )
								{
									$ww_slide_link = zn_extract_link( $slide['ww_slide_link'] );
									echo $ww_slide_link['start'] . $slide['ww_slide_read_text_content'] . $ww_slide_link['end'];
								}

								echo '</div>';
								echo '</div>';
								echo '</div><!-- end ca-item -->';
								$i ++;
							}
						}
					?>
					</div><!-- end ca-wrapper -->
				</div>
				<!-- end circular content carousel -->
			</div>
		</div>
		<?php
			zn_bottommask_markup($bottom_mask, $this->opt('hm_header_bmasks_bg',''));
		?>
		<!-- header bottom style -->
	</div><!-- end kl-slideshow -->
