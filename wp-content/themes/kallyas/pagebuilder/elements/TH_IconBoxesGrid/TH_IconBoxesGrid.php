<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/*
 Name: Grid Icon Boxes
 Description: Create and display an Grid Image Boxes element.
 Class: TH_IconBoxesGrid
 Category: content
 Level: 3
*/
/**
 * Class TH_IconBoxesGrid
 *
 * Create and display an Grid Image Boxes element containing an icon, title description with different settings and hover effects
 *
 * @package  Kallyas
 * @category Page Builder
 *
 * @author   Team Hogash
 *
 * @since    4.0.0
 */
class TH_IconBoxesGrid extends ZnElements {
	public static function getName() {
		return __( 'Grid Icon Boxes', 'zn_framework' );
	}

	private function get_background_style( $background_image_config ) {
		$css = '';
		if ( ! empty( $background_image_config['image'] ) ) {
			$css .= 'background-image:url("' . $background_image_config['image'] . '");';
		}
		if ( ! empty( $background_image_config['repeat'] ) ) {
			$css .= 'background-repeat:' . $background_image_config['repeat'] . ';';
		}
		if ( ! empty( $background_image_config['position'] ) ) {
			$css .= 'background-position:' . $background_image_config['position']['x'] . ' ' . $background_image_config['position']['y'] . ';';
		}
		if ( ! empty( $background_image_config['attachment'] ) ) {
			$css .= 'background-attachment:' . $background_image_config['attachment'] . ';';
		}

		return $css;
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	public function css() {
		$css = '';
		$uid = $this->data['uid'];

		// Title Styles
		$title_styles = '';
		$title_typo   = $this->opt( 'title_typo' );
		if ( is_array( $title_typo ) && ! empty( $title_typo ) ) {
			foreach ( $title_typo as $key => $value ) {
				if ( ! empty( $value ) ) {
					if ( 'font-family' == $key ) {
						$title_styles .= $key . ':' . zn_convert_font( $value ) . ';';
					} else {
						$title_styles .= $key . ':' . $value . ';';
					}
				}
			}
			$css .= '#' . $uid . ' .grid-ibx__title {' . $title_styles . '} ';
		}
		// Description styles
		$desc_styles = '';
		$desc_typo   = $this->opt( 'desc_typo' );
		if ( is_array( $desc_typo ) && ! empty( $desc_typo ) ) {
			foreach ( $desc_typo as $key => $value ) {
				if ( ! empty( $value ) ) {
					if ( 'font-family' === $key ) {
						$desc_styles .= $key . ':' . zn_convert_font( $value ) . ';';
					} else {
						$desc_styles .= $key . ':' . $value . ';';
					}
				}
			}
			$css .= '#' . $uid . ' .grid-ibx__desc {' . $desc_styles . '} ';
		}
		// Icon color default and on hover
		$ibg_icon_color = $this->opt( 'ibg_icon_color', '' );
		$ibg_icon_color_hover = $this->opt( 'ibg_icon_color_hover', '' );
		if ( ! empty( $ibg_icon_color ) ) {
			$css .= '#' . $uid . ' .grid-ibx__icon {color:' . $ibg_icon_color . '} ';
		}
		if ( ! empty( $ibg_icon_color_hover ) ) {
			$css .= '#' . $uid . ' .grid-ibx__item:hover .grid-ibx__icon {color:' . $ibg_icon_color_hover . '} ';
		}

		// Force text color
		$ibg_text_color_hover = $this->opt( 'ibg_text_color_hover', '' );
		if ( ! empty( $ibg_text_color_hover ) ) {
			$css .= '#' . $uid . ' .grid-ibx__item:hover .grid-ibx__title, #' . $uid . ' .grid-ibx__item:hover .grid-ibx__desc {color:' . $ibg_text_color_hover . '}';
		}

		// Gradient lined style, use same hover color as icon
		if ( ! empty( $ibg_icon_color_hover ) ) {
			if ( 'lined-gradient' == $this->opt( 'ibg_style', 'lined-full' ) ) {
				$css .= '#' . $uid . '.grid-ibx--style-lined-gradient .grid-ibx__item:hover .grid-ibx__ghelper {border-color:' . $ibg_icon_color_hover . '} ';
				$css .= '#' . $uid . '.grid-ibx--style-lined-gradient .grid-ibx__item:hover:before, #' . $uid . '.grid-ibx--style-lined-gradient .grid-ibx__item:hover:after { background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . $ibg_icon_color_hover . '), color-stop(100%,transparent)); background: -webkit-linear-gradient(top,  ' . $ibg_icon_color_hover . ' 0%,transparent 100%); background: -webkit-linear-gradient(top, ' . $ibg_icon_color_hover . ' 0%, transparent 100%); background: linear-gradient(to bottom,  ' . $ibg_icon_color_hover . ' 0%,transparent 100%); }';
			}
		}

		// Icon height
		$height_old_val = isset( $this->data['options']['ibg_height'] ) && ! empty( $this->data['options']['ibg_height'] ) ? $this->data['options']['ibg_height'] : '200';

		$css .= zn_smart_slider_css(
			$this->opt( 'ibg_height_new', $height_old_val ),
			'#' . $uid . ' .grid-ibx__item'
		);

		// Custom background color
		$bg_color = $this->opt( 'ibg_bg_color' );
		if ( ! empty( $bg_color ) ) {
			$css .= '#' . $uid . ' .grid-ibx__item {background-color: ' . $bg_color . ';} ';
		}

		$bg_color_hover = $this->opt( 'ibg_bg_color_hover' );
		if ( ! empty( $bg_color_hover ) ) {
			$css .= '#' . $uid . ' .grid-ibx__item:hover {background-color: ' . $bg_color_hover . ';} ';
		}

		// Icon sizes
		$icon_size = $this->opt( 'ibg_size', '60' );
		if ( '60' != $icon_size ) {
			$css .= "#{$uid} span.grid-ibx__icon { font-size: {$icon_size}px }";
		}

		// Padding
		$paddings = array();
		$paddings['lg'] = $this->opt( 'padding_lg', '' ) ? $this->opt( 'padding_lg', '' ) : '';
		$paddings['md'] = $this->opt( 'padding_md', '' ) ? $this->opt( 'padding_md', '' ) : '';
		$paddings['sm'] = $this->opt( 'padding_sm', '' ) ? $this->opt( 'padding_sm', '' ) : '';
		$paddings['xs'] = $this->opt( 'padding_xs', '' ) ? $this->opt( 'padding_xs', '' ) : '';
		if ( ! empty( $paddings ) ) {
			$paddings['selector'] = '#' . $uid . ' .grid-ibx__item';
			$paddings['type'] = 'padding';
			$css .= zn_push_boxmodel_styles( $paddings );
		}

		// Color overrides
		$ibg_ib = $this->opt( 'ibg_ib' );
		if ( is_array( $ibg_ib ) && ! empty( $ibg_ib ) ) {
			foreach ( $ibg_ib as $k => $item ) {
				$gitem = '#' . $uid . ' .grid-ibx__item-' . $k;

				// Custom background color
				if ( isset( $item['bg_color'] ) && $bg_color = $item['bg_color'] ) {
					$css .= $gitem . ' {background-color: ' . $bg_color . ';} ';
				}
				if ( isset( $item['bg_color_hover'] ) && $bg_color_hover = $item['bg_color_hover'] ) {
					$css .= $gitem . ':hover {background-color: ' . $bg_color_hover . ';} ';
				}

				// Background image
				if ( isset( $item['background_image'] ) && ! empty( $item['background_image'] ) ) {
					$background_styles = $this->get_background_style( $item['background_image'] );
					if ( ! empty( $background_styles ) ) {
						$css .= sprintf( '%s {%s}', $gitem, $background_styles );
					}
				}

				if ( isset( $item['background_hover_image'] ) && ! empty( $item['background_hover_image'] ) ) {
					$background_hover_styles = $this->get_background_style( $item['background_hover_image'] );
					if ( ! empty( $background_hover_styles ) ) {
						$css .= sprintf( '%s:hover {%s}', $gitem, $background_hover_styles );
					}
				}

				// Icon color default and on hover
				if ( isset( $item['icon_color'] ) && $icon_color = $item['icon_color'] ) {
					$css .= $gitem . ' .grid-ibx__icon {color:' . $icon_color . '} ';
				}
				if ( isset( $item['icon_color_hover'] ) && $icon_color_hover = $item['icon_color_hover'] ) {
					$css .= $gitem . ':hover .grid-ibx__icon {color:' . $icon_color_hover . '} ';
				}

				// Force text color
				if ( isset( $item['text_color'] ) && $text_color = $item['text_color'] ) {
					$css .= $gitem . ' .grid-ibx__title, ' . $gitem . ' .grid-ibx__desc {color:' . $text_color . '}';
				}
				if ( isset( $item['text_color_hover'] ) && $text_color_hover = $item['text_color_hover'] ) {
					$css .= $gitem . ':hover .grid-ibx__title, ' . $gitem . ':hover .grid-ibx__desc {color:' . $text_color_hover . '}';
				}
			}
		}

		return $css;
	}

	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	public function element() {
		$uid           = $this->data['uid'];
		$options       = $this->data['options'];
		$ibg_ib        = $this->opt( 'ibg_ib' );
		$floated_style = $this->opt( 'ibg_titleorder' );

		// States and modificators
		$mods   = array();
		$mods[] = 'grid-ibx--cols-' . $this->opt( 'ibg_perrow', '3' );
		$mods[] = 'grid-ibx--md-cols-' . $this->opt( 'ibg_perrow_md', '3' );
		$mods[] = 'grid-ibx--sm-cols-' . $this->opt( 'ibg_perrow_sm', '2' );
		$mods[] = 'grid-ibx--xs-cols-' . $this->opt( 'ibg_perrow_xs', '1' );

		if ( '' != $this->opt( 'ibg_style' ) ) {
			$mods[] = 'grid-ibx--style-' . $this->opt( 'ibg_style', 'lined-full' );
		}
		$mods[] = $this->opt( 'ibg_hover', 'grid-ibx--hover-shadow' );
		$mods[] = $this->data['uid'];
		$mods[] = zn_get_element_classes( $options );

		$color_scheme = '' == $this->opt( 'element_scheme', '' ) ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$mods[]       = 'grid-ibx--theme-' . $color_scheme;
		$mods[]       = 'element-scheme--' . $color_scheme;

		// Floated style
		$mods[] = 'grid-ibx__flt-' . $floated_style;

		// Position title
		switch ( $floated_style ) :
			case '1':
			case 'inline_right':
				$title_order = 'before';
		break;
		case '':
			case 'zn_dummy_value':
			case 'inline_left':
			case 'floated_left':
			case 'floated_right':
				$title_order = 'after';
		break;
		endswitch;

		// Element shadow
		$mods[] = $this->opt( 'el_shadow', '' ) ? 'znBoxShadow-' . $this->opt( 'el_shadow', '' ) : '';
		$mods[] = $this->opt( 'el_shadow_hover', '' ) ? 'znBoxShadow--hov-' . $this->opt( 'el_shadow_hover', '' ) . ' znBoxShadow--hover' : ''; ?>

<div class='grid-ibx <?php echo implode( ' ', $mods ); ?>' <?php echo zn_get_element_attributes( $options, $uid ); ?>>
	<div class='grid-ibx__inner'>
		<div class='grid-ibx__row clearfix'>
	<?php
		$countib = count( $ibg_ib );

		if ( is_array( $ibg_ib ) && ! empty( $ibg_ib ) ) {
			$i = 0;
			foreach ( $ibg_ib as $ib ) {
				$icon_type = $ib['ibg_type'];
				$theicon = $ib['ibg_icon'];
				$icon_img = $ib['ibg_image'];

				// Do the link
				$ibg_link = zn_extract_link( $ib['ibg_link'], 'grid-ibx__link clearfix' );

				// Title
				$titlehtml = '';
				if ( ! empty( $ib['ibg_title'] ) ) {
					$title = $ib['ibg_title'];
					$titlehtml = '<div class="grid-ibx__title-wrp"><h4 class="grid-ibx__title element-scheme__hdg1" ' . WpkPageHelper::zn_schema_markup( 'title' ) . '>' . $title . '</h4></div>';
				} ?>
				<div class='grid-ibx__item  grid-ibx__item--type-<?php echo esc_attr( $icon_type ); ?> text-<?php echo esc_attr( $this->opt( 'alignment', 'center' ) ); ?> grid-ibx__item-<?php echo esc_attr( $i ); ?>'>
					<?php
					// Add a hack for the lined gradient box
					if ( 'lined-gradient' == $this->opt( 'ibg_style', 'lined-full' ) ) {
						echo "<i class='grid-ibx__ghelper'></i>";
					} ?>
					<div class='grid-ibx__item-inner'>
						<?php

						echo $ibg_link['start'];

				// Display title
				if ( 'before' == $title_order ) {
					echo $titlehtml;
				} ?>

						<?php if ( ! empty( $theicon['unicode'] ) || ! empty( $icon_img ) ) {
					?>
						<div class='grid-ibx__icon-wrp'>
						<?php
							// Icon Font
							if ( 'icon' == $icon_type ) {
								if ( ! empty( $theicon['unicode'] ) ) {
									echo '<span class="grid-ibx__icon" ' . zn_generate_icon( $theicon ) . '></span>';
								}
							}
					// Icon Image
					elseif ( 'img' == $icon_type ) {
						if ( ! empty( $icon_img ) ) {
							echo '<img class="grid-ibx__icon" src="' . $icon_img . '" ' . ZngetImageSizesFromUrl( $icon_img, true ) . ' alt="' . ZngetImageAltFromUrl( $icon_img ) . '" title="' . ZngetImageTitleFromUrl( $icon_img ) . '" />';
						}
					} ?>
						</div>
						<?php
				} ?>

						<?php

						// Wrap floated'
						if ( 'floated_left' == $floated_style || 'floated_right' == $floated_style ) {
							echo '<div class="grid-ibx__floatedWrapper">';
						}

				// Display title
				if ( 'after' == $title_order ) {
					echo $titlehtml;
				} ?>

						<?php if ( $desc = $ib['ibg_desc'] ) {
					?>
						<div class='clearfix'></div>
						<div class='grid-ibx__desc-wrp'>
							<?php printf( "<p class='grid-ibx__desc'>%s</p>", $desc ); ?>
						</div>
						<?php
				} ?>

						<?php

						// Wrap floated'
						if ( 'floated_left' == $floated_style || 'floated_right' == $floated_style ) {
							echo '</div>';
						} ?>

						<?php echo $ibg_link['end']; ?>

					</div>
				</div><!-- /.grid-ibx__item -->
			<?php
			$i++;
			} // end foreach
		} ?>

	</div><!-- /.grid-ibx__row -->
	</div>
</div><!-- /.grid-ibx -->

<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 *
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options() {
		$uid = $this->data['uid'];

		$ibox = array(
			'name'           => __( 'Icon Box', 'zn_framework' ),
			'description'    => __( 'Add Icon Box.', 'zn_framework' ),
			'id'             => 'ibg_ib',
			'std'            => '',
			'type'           => 'group',
			'add_text'       => __( 'Icon Box', 'zn_framework' ),
			'remove_text'    => __( 'Icon Box', 'zn_framework' ),
			'group_sortable' => true,
			'element_title'  => 'ibg_title',
			'subelements'    => array(

				array(
					'name'        => __( 'Title', 'zn_framework' ),
					'description' => __( 'Title text.', 'zn_framework' ),
					'id'          => 'ibg_title',
					'std'         => '',
					'type'        => 'text',
				),

				array(
					'name'        => __( 'Description', 'zn_framework' ),
					'description' => __( 'Description text.', 'zn_framework' ),
					'id'          => 'ibg_desc',
					'std'         => '',
					'type'        => 'textarea',
				),

				array(
					'name'        => __( 'Add link', 'zn_framework' ),
					'description' => __( 'Add a link here. Will wrap the whole block.', 'zn_framework' ),
					'id'          => 'ibg_link',
					'std'         => '',
					'type'        => 'link',
					'options'     => zn_get_link_targets(),
				),

				array(
					'name'        => __( 'Icon Type', 'zn_framework' ),
					'description' => __( 'Type of the icon.', 'zn_framework' ),
					'id'          => 'ibg_type',
					'std'         => 'icon',
					'type'        => 'select',
					'options'     => array(
						'icon' => __( 'Font Icon', 'zn_framework' ),
						'img'  => __( 'Image (PNG, JPG, SVG or even GIF)', 'zn_framework' ),
					),
				),

				array(
					'name'        => __( 'Image Icon', 'zn_framework' ),
					'description' => __( 'Upload an Icon Image.', 'zn_framework' ),
					'id'          => 'ibg_image',
					'std'         => '',
					'type'        => 'media',
					'dependency'  => array( 'element' => 'ibg_type', 'value'=> array('img') ),
				),

				array(
					'name'        => __( 'Select Icon', 'zn_framework' ),
					'description' => __( 'Select an icon to display.', 'zn_framework' ),
					'id'          => 'ibg_icon',
					'std'         => '',
					'type'        => 'icon_list',
					'class'       => 'zn_icon_list',
					'compact'     => true,
					'dependency'  => array( 'element' => 'ibg_type', 'value'=> array('icon') ),
				),

				array(
					'id'          => 'title1',
					'name'        => 'Color Options',
					'description' => 'You can override the default color options.',
					'type'        => 'zn_title',
					'class'       => 'zn_full zn-custom-title-large',
				),

				array(
					'name'        => __( 'Background Color', 'zn_framework' ),
					'description' => __( 'Add a background color to the box.', 'zn_framework' ),
					'id'          => 'bg_color',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'       => true,
				),

				array(
					'id'          => 'background_image',
					'name'        => 'Background image',
					'description' => 'Please choose a background image for this section.',
					'type'        => 'background',
					'options'     => array( 'repeat' => true, 'position' => true, 'attachment' => true, 'size' => true ),
				),

				array(
					'name'        => __( 'Background Hover Color', 'zn_framework' ),
					'description' => __( 'Hover Color of the background.', 'zn_framework' ),
					'id'          => 'bg_color_hover',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'		=> true,
				),

				array(
					'id'          => 'background_hover_image',
					'name'        => 'Background hover image',
					'description' => 'Please choose a background hover image for this section.',
					'type'        => 'background',
					'options'     => array( 'repeat' => true, 'position' => true, 'attachment' => true, 'size' => true ),
				),

				array(
					'name'        => __( 'Icon Color (Only for Icon Font)', 'zn_framework' ),
					'description' => __( 'Color of the icon.', 'zn_framework' ),
					'id'          => 'icon_color',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'       => true,
				),

				array(
					'name'        => __( 'Icon Hover Color', 'zn_framework' ),
					'description' => __( 'Hover Color of the icon.', 'zn_framework' ),
					'id'          => 'icon_color_hover',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'       => true,
				),

				array(
					'name'        => __( 'Text Color', 'zn_framework' ),
					'description' => __( 'Text color.', 'zn_framework' ),
					'id'          => 'text_color',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'       => true,
				),

				array(
					'name'        => __( 'Text Hover Color', 'zn_framework' ),
					'description' => __( 'Force the hover color of the texts to change.', 'zn_framework' ),
					'id'          => 'text_color_hover',
					'std'         => '',
					'type'        => 'colorpicker',
					'alpha'       => true,
				),
			),
		);

		// Old height
		$std_height = isset( $this->data['options']['ibg_height'] ) && ! empty( $this->data['options']['ibg_height'] ) ? $this->data['options']['ibg_height'] : '200';

		return array(
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array(
						'id'          => 'title1',
						'name'        => 'Boxes Options',
						'description' => 'The options below refer to the boxes.',
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					array(
						'name'        => __( 'Boxes Height', 'zn_framework' ),
						'description' => __( 'Fixed height of the boxes, in px.', 'zn_framework' ),
						'id'          => 'ibg_height_new',
						'type'        => 'smart_slider',
						'std'        => $std_height,
						'helpers'     => array(
							'min' => '10',
							'max' => '800',
						),
						'supports' => array('breakpoints'),
						'units' => array('px'),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.' . $uid . ' .grid-ibx__item',
							'css_rule'  => 'height',
							'unit'      => 'px',
						),
					),

					array(
						'name'        => __( 'Grid Style', 'zn_framework' ),
						'description' => __( 'Select a style for the grid.', 'zn_framework' ),
						'id'          => 'ibg_style',
						'std'         => 'lined-full',
						'type'        => 'select',
						'options'     => array(
							'' => __( 'Simple, no borders', 'zn_framework' ),
							'lined-full' => __( 'Fully Bordered', 'zn_framework' ),
							'lined-center' => __( 'Borders inside only', 'zn_framework' ),
							'lined-gradient' => __( 'Gradient Borders (Only with scale hover effect)', 'zn_framework' ),
						),
					),
					array(
						'name'        => __( 'Hover Style', 'zn_framework' ),
						'description' => __( 'Select the style should be applied on hover.', 'zn_framework' ),
						'id'          => 'ibg_hover',
						'std'         => 'shadow',
						'type'        => 'select',
						'options'     => array(
							'grid-ibx--hover-bg' => __( 'Just background color', 'zn_framework' ),
							'grid-ibx--hover-shadow' => __( 'Shadow under the box', 'zn_framework' ),
							'grid-ibx--hover-scale' => __( 'Scale the box', 'zn_framework' ),
							'grid-ibx--hover-shadowscale' => __( 'Scale & Shadow', 'zn_framework' ),
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.' . $uid,
						),
					),

					array(
						'name'        => __( 'Icon-Box Style', 'zn_framework' ),
						'description' => __( 'Float icon sideways?', 'zn_framework' ),
						'id'          => 'ibg_titleorder',
						'std'         => '',
						'type'        => 'select',
						'options'        => array(
							'' => __( 'Icon before title', 'zn_framework' ),
							'1' => __( 'Title before icon', 'zn_framework' ),
							'inline_left' => __( 'Inline - Icon & Title', 'zn_framework' ),
							'inline_right' => __( 'Inline - Title & Icon', 'zn_framework' ),
							'floated_left' => __( 'Floated Left', 'zn_framework' ),
							'floated_right' => __( 'Floated Right', 'zn_framework' ),
						),
					),

					array(
						'id'          => 'title2',
						'name'        => 'Container Options',
						'description' => 'The options below refer container and element itself.',
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					array(
						// 'name'        => __( 'Edit element padding & margins for each device breakpoint. ', 'zn_framework' ),
						// 'description' => __( 'This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.', 'zn_framework' ),
						'id'          => 'perrow_breakpoints',
						'std'         => 'lg',
						'tabs'        => true,
						'type'        => 'zn_radio',
						'options'     => array(
							'lg' => __( 'LARGE', 'zn_framework' ),
							'md' => __( 'MEDIUM', 'zn_framework' ),
							'sm' => __( 'SMALL', 'zn_framework' ),
							'xs' => __( 'EXTRA SMALL', 'zn_framework' ),
						),
						'class'       => 'zn_full zn_breakpoints',
					),

					array(
						'name'        => __( 'Icon Boxes per ROW', 'zn_framework' ),
						'description' => __( 'Select how many icon boxes to appear per row.', 'zn_framework' ),
						'id'          => 'ibg_perrow',
						'std'         => '3',
						'type'        => 'select',
						'options'     => array(
							'1' => __( '1 icon box per row', 'zn_framework' ),
							'2' => __( '2 icon box per row', 'zn_framework' ),
							'3' => __( '3 icon box per row', 'zn_framework' ),
							'4' => __( '4 icon box per row', 'zn_framework' ),
							'5' => __( '5 icon box per row', 'zn_framework' ),
						),
						'dependency'  => array( 'element' => 'perrow_breakpoints', 'value'=> array('lg') ),
					),

					array(
						'name'        => __( 'Icon Boxes per ROW (Medium screens)', 'zn_framework' ),
						'description' => __( 'Select how many icon boxes to appear per row.', 'zn_framework' ),
						'id'          => 'ibg_perrow_md',
						'std'         => '3',
						'type'        => 'select',
						'options'     => array(
							'1' => __( '1 icon box per row', 'zn_framework' ),
							'2' => __( '2 icon box per row', 'zn_framework' ),
							'3' => __( '3 icon box per row', 'zn_framework' ),
							'4' => __( '4 icon box per row', 'zn_framework' ),
							'5' => __( '5 icon box per row', 'zn_framework' ),
						),
						'dependency'  => array( 'element' => 'perrow_breakpoints', 'value'=> array('md') ),
					),

					array(
						'name'        => __( 'Icon Boxes per ROW (Small Screens)', 'zn_framework' ),
						'description' => __( 'Select how many icon boxes to appear per row.', 'zn_framework' ),
						'id'          => 'ibg_perrow_sm',
						'std'         => '2',
						'type'        => 'select',
						'options'     => array(
							'1' => __( '1 icon box per row', 'zn_framework' ),
							'2' => __( '2 icon box per row', 'zn_framework' ),
							'3' => __( '3 icon box per row', 'zn_framework' ),
							'4' => __( '4 icon box per row', 'zn_framework' ),
							'5' => __( '5 icon box per row', 'zn_framework' ),
						),
						'dependency'  => array( 'element' => 'perrow_breakpoints', 'value'=> array('sm') ),
					),

					array(
						'name'        => __( 'Icon Boxes per ROW (Extra Small)', 'zn_framework' ),
						'description' => __( 'Select how many icon boxes to appear per row.', 'zn_framework' ),
						'id'          => 'ibg_perrow_xs',
						'std'         => '1',
						'type'        => 'select',
						'options'     => array(
							'1' => __( '1 icon box per row', 'zn_framework' ),
							'2' => __( '2 icon box per row', 'zn_framework' ),
							'3' => __( '3 icon box per row', 'zn_framework' ),
							'4' => __( '4 icon box per row', 'zn_framework' ),
							'5' => __( '5 icon box per row', 'zn_framework' ),
						),
						'dependency'  => array( 'element' => 'perrow_breakpoints', 'value' => array('xs') ),
					),

					array(
						'name'        => __( 'Container Shadow', 'zn_framework' ),
						'description' => __( 'This option will apply a shadow on the entire element.', 'zn_framework' ),
						'id'          => 'el_shadow',
						'std'         => '',
						'options'     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1' => __( 'Shadow 1x', 'zn_framework' ),
							'2' => __( 'Shadow 2x', 'zn_framework' ),
							'3' => __( 'Shadow 3x', 'zn_framework' ),
							'4' => __( 'Shadow 4x', 'zn_framework' ),
							'5' => __( 'Shadow 5x', 'zn_framework' ),
							'6' => __( 'Shadow 6x', 'zn_framework' ),
						),
						'type' => 'select',
						'live' => array(
							'type'        => 'class',
							'css_class'   => '.' . $uid,
							'val_prepend' => 'znBoxShadow-',
						),
					),

					array(
						'name'        => __( 'Container Shadow on Hover', 'zn_framework' ),
						'description' => __( 'This option will apply a shadow on the entire element on hover.', 'zn_framework' ),
						'id'          => 'el_shadow_hover',
						'std'         => '',
						'options'     => array(
							''  => __( 'No shadow', 'zn_framework' ),
							'1'  => __( 'Shadow 1x', 'zn_framework' ),
							'2'  => __( 'Shadow 2x', 'zn_framework' ),
							'3'  => __( 'Shadow 3x', 'zn_framework' ),
							'4'  => __( 'Shadow 4x', 'zn_framework' ),
							'5'  => __( 'Shadow 5x', 'zn_framework' ),
							'6'  => __( 'Shadow 6x', 'zn_framework' ),
						),
						'type'        => 'select',
					),
				),
			),
			'icons' => array(
				'title' => 'Icon boxes',
				'options' => array(
					$ibox,
				),
			),
			'colors' => array(
				'title' => 'Color options',
				'options' => array(
					array(
						'id'          => 'element_scheme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select the color scheme of this element',
						'type'        => 'select',
						'std'         => '',
						'options'        => array(
							'' => 'Inherit from Kallyas options > Color Options [Requires refresh]',
							'light' => 'Light (default)',
							'dark' => 'Dark',
						),
						'live'        => array(
							'multiple' => array(
								array(
									'type'      => 'class',
									'css_class' => '.' . $uid,
									'val_prepend'  => 'grid-ibx--theme-',
								),
								array(
									'type'      => 'class',
									'css_class' => '.' . $uid,
									'val_prepend'  => 'element-scheme--',
								),
							),
						),
					),
					array(
						'name'        => __( 'Background Color', 'zn_framework' ),
						'description' => __( 'Add a background color to the box.', 'zn_framework' ),
						'id'          => 'ibg_bg_color',
						'std'         => '',
						'type'        => 'colorpicker',
						'alpha'		=> true,
						'live'        => array(
							'type'      => 'css',
							'css_class' => '.' . $uid . ' .grid-ibx__item',
							'css_rule'  => 'background-color',
							'unit'      => '',
						),
					),

					array(
						'name'        => __( 'Background Hover Color', 'zn_framework' ),
						'description' => __( 'Hover Color of the background.', 'zn_framework' ),
						'id'          => 'ibg_bg_color_hover',
						'std'         => '',
						'type'        => 'colorpicker',
						'alpha'		=> true,
					),

					array(
						'name'        => __( 'Icon Color (Only for Icon Font)', 'zn_framework' ),
						'description' => __( 'Color of the icon.', 'zn_framework' ),
						'id'          => 'ibg_icon_color',
						'std'         => '',
						'type'        => 'colorpicker',
						'alpha'		=> true,
						'live'        => array(
							'type'      => 'css',
							'css_class' => '.' . $uid . ' .grid-ibx__icon',
							'css_rule'  => 'color',
							'unit'      => '',
						),
					),

					array(
						'name'        => __( 'Icon Hover Color', 'zn_framework' ),
						'description' => __( 'Hover Color of the icon.', 'zn_framework' ),
						'id'          => 'ibg_icon_color_hover',
						'std'         => '',
						'type'        => 'colorpicker',
					),

					array(
						'name'        => __( 'Text Hover Color', 'zn_framework' ),
						'description' => __( 'Force the hover color of the texts to change.', 'zn_framework' ),
						'id'          => 'ibg_text_color_hover',
						'std'         => '',
						'type'        => 'colorpicker',
					),
				),
			),
			'font' => array(
				'title' => 'Font & Spacing',
				'options' => array(

					array(
						'name'        => __( 'Icon Size', 'zn_framework' ),
						'description' => __( 'Select the size of the icon.', 'zn_framework' ),
						'id'          => 'ibg_size',
						'std'         => '60',
						'type'        => 'slider',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '10',
							'max' => '400',
							'step' => '1',
						),
						'live' => array(
							'type'      => 'css',
							'css_class' => '.' . $uid . ' span.grid-ibx__icon',
							'css_rule'  => 'font-size',
							'unit'      => 'px',
						),
					),

					array(
						'name'        => __( 'Alignment', 'zn_framework' ),
						'description' => __( 'Select the alignment', 'zn_framework' ),
						'id'          => 'alignment',
						'std'         => 'center',
						'type'        => 'select',
						'options'     => array(
							'left' => __( 'Left', 'zn_framework' ),
							'center' => __( 'Center', 'zn_framework' ),
							'right' => __( 'Right', 'zn_framework' ),
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .grid-ibx__item',
							'val_prepend'  => 'text-',
						),
					),

					array(
						'name'        => __( 'Title settings', 'zn_framework' ),
						'description' => __( 'Specify the typography properties for the title.', 'zn_framework' ),
						'id'          => 'title_typo',
						'std'         => array(
							'font-size'   => '20px',
							'font-family'   => 'Open Sans',
							'line-height' => '30px',
							'font-style' => 'normal',
							'font-weight' => '400',
						),
						'supports'   => array( 'size', 'font', 'style', 'line', 'color', 'weight', 'spacing', 'case', 'mb' ),
						'type'        => 'font',
						'live'        => array(
							'type'      => 'font',
							'css_class' => '.' . $uid . ' .grid-ibx__title',
						),
					),
					array(
						'name'        => __( 'Description text settings', 'zn_framework' ),
						'description' => __( 'Specify the typography properties for the description text.', 'zn_framework' ),
						'id'          => 'desc_typo',
						'std'         => array(
							'font-size'   => '13px',
							'font-family'   => 'Open Sans',
							'line-height' => '24px',
							'font-style' => 'normal',
							'font-weight' => '400',
						),
						'supports'   => array( 'size', 'font', 'style', 'line', 'weight', 'color' ),
						'type'        => 'font',
						'live'        => array(
							'type'      => 'font',
							'css_class' => '.' . $uid . ' .grid-ibx__desc',
						),
					),

					/*
					 * Margins and padding
					 */
					array(
						'name'        => __( 'Edit items padding for each device breakpoint. ', 'zn_framework' ),
						'description' => __( "This will enable you to have more control over the padding of the container on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
						'id'          => 'spacing_breakpoints',
						'std'         => 'lg',
						'tabs'        => true,
						'type'        => 'zn_radio',
						'options'     => array(
							'lg'        => __( 'LARGE', 'zn_framework' ),
							'md'        => __( 'MEDIUM', 'zn_framework' ),
							'sm'        => __( 'SMALL', 'zn_framework' ),
							'xs'        => __( 'EXTRA SMALL', 'zn_framework' ),
						),
						'class'       => 'zn_full zn_breakpoints',
					),

					// PADDINGS
					array(
						'id'          => 'padding_lg',
						'name'        => 'Padding (Large Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'allow-negative' => false,
						'std'	  => '',
						'placeholder' => '0px',
						'dependency'  => array( 'element' => 'spacing_breakpoints', 'value'=> array('lg') ),
						'live' => array(
							'type'		=> 'boxmodel',
							'css_class' => '.' . $uid . ' .grid-ibx__item',
							'css_rule'	=> 'padding',
						),
					),
					array(
						'id'          => 'padding_md',
						'name'        => 'Padding (Medium Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'allow-negative' => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						'dependency'  => array( 'element' => 'spacing_breakpoints', 'value'=> array('md') ),
					),
					array(
						'id'          => 'padding_sm',
						'name'        => 'Padding (Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'allow-negative' => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						'dependency'  => array( 'element' => 'spacing_breakpoints', 'value'=> array('sm') ),
					),
					array(
						'id'          => 'padding_xs',
						'name'        => 'Padding (Extra Small Breakpoints)',
						'description' => 'Select the padding (in percent % or px) for this container.',
						'type'        => 'boxmodel',
						'allow-negative' => false,
						'std'	  => 	'',
						'placeholder'        => '0px',
						'dependency'  => array( 'element' => 'spacing_breakpoints', 'value'=> array('xs') ),
					),

				),
			),


			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#MiAcyl85h3o' ) ),
				'docs'    => sprintf( '%s', esc_url( 'https://my.hogash.com/documentation/grid-image-boxes/' ) ),
				'copy'    => $uid,
				'general' => true,
			) ),

		);
	}
}
