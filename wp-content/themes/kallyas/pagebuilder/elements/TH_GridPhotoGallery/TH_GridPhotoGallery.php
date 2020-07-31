<?php if ( ! defined('ABSPATH')) {
	return;
}
/*
 Name: Grid Photo Gallery
 Description: Create and display a Grid based Photo Gallery element
 Class: TH_GridPhotoGallery
 Category: content
 Level: 3
 Keywords: images
*/
/**
 * Class TH_GridPhotoGallery
 *
 * Create and display a Grid Photo Gallery element
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    4.0.0
 */
class TH_GridPhotoGallery extends ZnElements {
	public static function getName() {
		return __( 'Grid Photo Gallery', 'zn_framework' );
	}

	/**
	 * Load dependant resources
	 */
	function scripts() {
		$pg_layout_type = $this->opt('pg_layout_type', 'masonry');

		if ('none' == $pg_layout_type) {
			return;
		}

		wp_enqueue_script( 'isotope');

		if ( 'packery' == $pg_layout_type ) {
			wp_enqueue_script( 'isotope-packery', THEME_BASE_URI . '/addons/isotope-addons/packery-mode.pkgd.min.js', array ( 'jquery', 'isotope' ), ZN_FW_VERSION, true );
		}
	}

	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css() {
		$css = '';
		$uid = $this->data['uid'];

		if ('custom' == $this->opt('pg_img_height', 'square')) {

			// Custom Ratio
			$pg_img_customratio = $this->opt('pg_img_customratio', '100');
			if ( '100' != $pg_img_customratio) {
				$sel = '.' . $uid . '.gridPhotoGallery--ratio-custom ';
				$css .= $sel . '.gridPhotoGalleryItem--w1 .gridPhotoGalleryItem--h1, ' . $sel . '.gridPhotoGalleryItem--w2 .gridPhotoGalleryItem--h2 {padding-bottom:' . $pg_img_customratio . '% }';
				$css .= $sel . '.gridPhotoGalleryItem--w2 .gridPhotoGalleryItem--h1 {padding-bottom:' . ($pg_img_customratio / 2) . '% }';
				$css .= $sel . '.gridPhotoGalleryItem--w1 .gridPhotoGalleryItem--h2 {padding-bottom:' . ($pg_img_customratio * 2) . '% }';
			}
		}


		return $css;
	}
	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element() {
		$options = $this->data['options'];

		$num_cols       = $this->opt('pg_num_cols', 4);
		$gridGallery    = $this->opt('single_photo_gallery');
		$pg_layout_type = $this->opt('pg_layout_type', 'masonry');

		$classes   = array();
		$classes[] = $uid = $this->data['uid'];
		$classes[] = 'gridPhotoGallery mfp-gallery mfp-gallery--misc';
		$classes[] = 'gridPhotoGallery--ratio-' . $this->opt('pg_img_height', 'square');
		$classes[] = 'gridPhotoGallery--cols-' . $num_cols;
		$classes[] = 'gpg-gutter--' . $this->opt('pg_gutter_size', '5');
		$classes[] = 'none' == $pg_layout_type ? 'stop-isotope' : '';
		$classes[] = zn_get_element_classes($options);

		$classes[] = 'gridPhotoGallery--scheme-' . $this->opt( 'element_scheme', 'light' );

		$lazy = 'yes' == $this->opt('lazy', '') ? 'data-echo' : 'src'; ?>
			<div class=" <?php echo implode(' ', $classes); ?>" data-cols="<?php echo esc_attr( $num_cols ); ?>" data-layout="<?php echo esc_attr( $pg_layout_type ); ?>" <?php echo zn_get_element_attributes($options); ?>>

				<div class="gridPhotoGallery__item gridPhotoGallery__item--sizer"></div>
				<?php
				if ( $gridGallery && is_array($gridGallery) ) {
					foreach ($gridGallery as $image) {
						$itemWidth  = $image['spg_width'] ? $image['spg_width'] : 1;
						$itemHeight = $image['spg_height'] ? $image['spg_height'] : 1;
						$link_start = '';
						$link_end   = '';
						$img        = $image['spg_image'];

						$item_scheme = isset($image['item_element_scheme']) ? $image['item_element_scheme'] : '';


						echo '<div class="gridPhotoGallery__item gridPhotoGalleryItem--w' . $itemWidth . ' gridPhotoGallery-item--scheme-' . $item_scheme . ' ">';

						$link_classes = 'gridPhotoGalleryItem--h' . $itemHeight . ' gridPhotoGallery__link kl-fontafter-alt gridPhotoGallery__link-anim--' . $this->opt('pg_img_anim', 'none');

						if ( isset($image['spg_pholder']) && 'yes' == $image['spg_pholder'] ) {
							echo '<span class="' . $link_classes . ' gridPhotoGallery__pHolder hidden-sm hidden-xs"></span>';
						} else {
							// default
							$link_type = 'img';

							if ( isset($image['spg_link_type']) && '' != $image['spg_link_type'] ) {
								$link_type = $image['spg_link_type'];
							} else {
								if ( isset($image['spg_video']) && ! empty($image['spg_video']) ) {
									$link_type = 'vid';
								}
							}

							// If Image
							if ( 'img' == $link_type && isset($img) && ! empty($img)) {
								$link_start = '<a title="' . $image['spg_title'] . '" class="' . $link_classes . '" data-lightbox="mfp" data-mfp="image" href="' . $img . '" >';
								$link_end   = '</a>';
								$icon       = '<i class="kl-icon glyphicon glyphicon-search circled-icon ci-' . $this->opt('pg_loupe_size', 'large') . '"></i>';
							}

							// If Video
							if ( 'vid' == $link_type && isset($image['spg_video']) && ! empty($image['spg_video'])) {
								$link_start = '<a title="' . $image['spg_title'] . '" class="' . $link_classes . '" data-lightbox="mfp" data-mfp="iframe" href="' . $image['spg_video'] . '">';
								$link_end   = '</a>';
								$icon       = '<i class="kl-icon glyphicon glyphicon-play circled-icon ci-' . $this->opt('pg_loupe_size', 'large') . '"></i>';
							}

							// If Custom URL
							if ( 'url' == $link_type && isset($image['spg_custom_url']['url']) && ! empty($image['spg_custom_url']['url'])) {
								$link_extracted = zn_extract_link(
										$image['spg_custom_url'],
										$link_classes,
										'title="' . $image['spg_title'] . '"',
										'<span class="' . $link_classes . '">',
										'</span>'
									);
								$link_start = $link_extracted['start'];
								$link_end   = $link_extracted['end'];
								$icon       = '<i class="kl-icon glyphicon glyphicon-link circled-icon ci-' . $this->opt('pg_loupe_size', 'large') . '"></i>';
							}


							echo $link_start;

							echo '<span class="gridPhotoGallery__imgAnimWrapper">';

							if ( isset($img) && ! empty($img) ) {
								$opt_img_width = (int) $this->opt('spg_img_width', '');
								if ( ! empty($opt_img_width)) {
									$resize_image = vt_resize( '', $img, $opt_img_width, '', true );
									$img_src      = $resize_image['url'];
									$img_attrs    = ' width="' . $resize_image['width'] . '" height="' . $resize_image['height'] . '" ';
								} else {
									$img_src   = $img;
									$img_attrs = ZngetImageSizesFromUrl($img, true);
								}

								$alt   = ZngetImageAltFromUrl( $img, true );
								$title = ZngetImageTitleFromUrl( $img, true );

								echo '<img class="gridPhotoGallery__img cover-fit-img" ' . $lazy . '="' . $img_src . '" ' . $img_attrs . ' ' . $alt . ' ' . $title . '>';
								echo $icon;
							}
							echo '</span>';
							echo $link_end;
						}
						echo '</div>';
					}
				} ?>
			</div>
			<div class="clearfix"></div>
	<?php
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options() {
		$uid = $this->data['uid'];

		if (isset( $this->data['options']['single_photo_gallery'] ) && ! empty($this->data['options']['single_photo_gallery'])) {
			foreach ( $this->data['options']['single_photo_gallery'] as $key => &$value ) {
				if ( ! isset($value['spg_link_type']) && isset($value['spg_video']) && '' != $value['spg_video'] ) {
					$value['spg_link_type'] = 'vid';
				}
			}
		}

		$extra_options = array(
			'name'           => __('Images', 'zn_framework'),
			'description'    => __('Here you can add your desired images.', 'zn_framework'),
			'id'             => 'single_photo_gallery',
			'std'            => '',
			'type'           => 'group',
			'add_text'       => __('Image', 'zn_framework'),
			'remove_text'    => __('Image', 'zn_framework'),
			'group_title'    => '',
			'group_sortable' => true,
			'element_title'  => 'spg_title',
			'element_img'    => 'spg_image',
			'subelements'    => array(
				array(
					'name'        => __('Title', 'zn_framework'),
					'description' => __('Please enter a title for this image.', 'zn_framework'),
					'id'          => 'spg_title',
					'std'         => '',
					'type'        => 'text',
				),

				array (
					'name'           => __( 'Act as placeholder?', 'zn_framework' ),
					'description'    => __( "Display the image as a placeholder? It'll show a blank space.", 'zn_framework' ),
					'id'             => 'spg_pholder',
					'std'            => 'no',
					'type'           => 'zn_radio',
					'options'        => array(
						'yes' => __( 'Yes', 'zn_framework' ),
						'no'  => __( 'No', 'zn_framework' ),
					),
					'class'        => 'zn_radio--yesno',
				),

				array(
					'name'        => __('Image', 'zn_framework'),
					'description' => __('Please select an image. This is mandatory', 'zn_framework'),
					'id'          => 'spg_image',
					'std'         => '',
					'type'        => 'media',
					'dependency'  => array( 'element' => 'spg_pholder', 'value'=> array('no') ),
				),

				array (
					'name'           => __( 'Link Type', 'zn_framework' ),
					'description'    => __( 'Select the link type of this gallery item.', 'zn_framework' ),
					'id'             => 'spg_link_type',
					'std'            => 'img',
					'type'           => 'select',
					'options'        => array(
						'img' => __( 'Image (modal)', 'zn_framework' ),
						'vid' => __( 'Video (modal)', 'zn_framework' ),
						'url' => __( 'Custom URL', 'zn_framework' ),
					),
					'dependency'  => array( 'element' => 'spg_pholder', 'value'=> array('no') ),
				),

				array(
					'name'        => __('Video URL', 'zn_framework'),
					'description' => __('Please enter the URL for your video. This video will appear when the user clicks on the image', 'zn_framework'),
					'id'          => 'spg_video',
					'std'         => '',
					'type'        => 'text',
					'dependency'  => array(
						array( 'element' => 'spg_pholder', 'value'=> array('no') ),
						array( 'element' => 'spg_link_type', 'value'=> array('vid') ),
					),
				),

				array(
					'name'        => __('Custom URL', 'zn_framework'),
					'description' => __('Please enter a custom URL.', 'zn_framework'),
					'id'          => 'spg_custom_url',
					'std'         => '',
					'type'        => 'link',
					'options'     => zn_get_link_targets(),
					'dependency'  => array(
						array( 'element' => 'spg_pholder', 'value'=> array('no') ),
						array( 'element' => 'spg_link_type', 'value'=> array('url') ),
					),
				),

				array(
					'name'        => __('Item Width', 'zn_framework'),
					'description' => __('Select the width of the element.', 'zn_framework'),
					'id'          => 'spg_width',
					'std'         => '1',
					'type'        => 'select',
					'options'     => array(
						'1' => __('1x', 'zn_framework'),
						'2' => __('2x', 'zn_framework'),
					),
				),
				array(
					'name'        => __('Item Height', 'zn_framework'),
					'description' => __('Select the height of the element.', 'zn_framework'),
					'id'          => 'spg_height',
					'std'         => '1',
					'type'        => 'select',
					'options'     => array(
						'1' => __('1x', 'zn_framework'),
						'2' => __('2x', 'zn_framework'),
					),
				),

				array(
					'id'             => 'item_element_scheme',
					'name'           => 'Element Color Scheme',
					'description'    => 'Select the color scheme of this element',
					'type'           => 'select',
					'std'            => '',
					'options'        => array(
						''      => 'Default',
						'light' => 'Light',
						'dark'  => 'Dark',
					),
					'live'        => array(
						'type'         => 'class',
						'css_class'    => '.' . $uid,
						'val_prepend'  => 'gridPhotoGallery-item--scheme-',
					),
				),

			),
		);


		$options = array(
			'has_tabs'  => true,
			'general'   => array(
				'title'   => 'General options',
				'options' => array(
					array(
						'name'        => __('Number of columns', 'zn_framework'),
						'description' => __('Select the desired number of columns for the grid.', 'zn_framework'),
						'id'          => 'pg_num_cols',
						'std'         => '4',
						'type'        => 'select',
						'options'     => array(
							'1' => __('1', 'zn_framework'),
							'2' => __('2', 'zn_framework'),
							'3' => __('3', 'zn_framework'),
							'4' => __('4', 'zn_framework'),
							'5' => __('5', 'zn_framework'),
							'6' => __('6', 'zn_framework'),
						),
					),
					array(
						'name'        => __('Images Height Ratio', 'zn_framework'),
						'description' => __('Select the desired image height ratio.', 'zn_framework'),
						'id'          => 'pg_img_height',
						'std'         => 'square',
						'type'        => 'select',
						'options'     => array(
							'short'  => __('Shorter Ratio', 'zn_framework'),
							'square' => __('Square Ratio', 'zn_framework'),
							'tall'   => __('Taller Ratio', 'zn_framework'),
							'custom' => __('Custom Ratio', 'zn_framework'),
						),
					),
					array(
						'name'        => __('Gutter size', 'zn_framework'),
						'description' => __('Please select the distance between images.', 'zn_framework'),
						'id'          => 'pg_gutter_size',
						'std'         => '5',
						'type'        => 'select',
						'options'     => array(
							'0'  => __('0 gutter size', 'zn_framework'),
							'3'  => __('6px gutter size', 'zn_framework'),
							'5'  => __('10px gutter size', 'zn_framework'),
							'10' => __('20px gutter size', 'zn_framework'),
							'15' => __('30px gutter size', 'zn_framework'),
						),
						'live' => array(
							'type'        => 'class',
							'css_class'   => '.' . $uid,
							'val_prepend' => 'gpg-gutter--',
						),
					),
					array(
						'name'        => __('Custom Ratio', 'zn_framework'),
						'description' => __('Select the custom Ratio (1% to 200%).', 'zn_framework'),
						'id'          => 'pg_img_customratio',
						'std'         => '100',
						'type'        => 'slider',
						'class'       => 'zn_full',
						'helpers'     => array(
							'min'  => '1',
							'max'  => '200',
							'step' => '1',
						),
						'dependency'  => array( 'element' => 'pg_img_height', 'value'=> array('custom') ),
					),
					array(
						'name'        => __('Isotope Layout Engine', 'zn_framework'),
						'description' => __("Select the isotope's engine that arranges the gallery.", 'zn_framework'),
						'id'          => 'pg_layout_type',
						'std'         => 'masonry',
						'type'        => 'select',
						'options'     => array(
							'none'    => __('None', 'zn_framework'),
							'masonry' => __('Masonry (built-in)', 'zn_framework'),
							'packery' => __('Packery (performance cost, automatically fills gaps). View Mode Only!', 'zn_framework'),
						),
					),

					array(
						'name'        => __('Images Width', 'zn_framework'),
						'description' => __('This option will resize and cache the images in your grid photo gallery items. Not specifying a width will result in full-width images that would slow down the loading of your page. ', 'zn_framework'),
						'id'          => 'spg_img_width',
						'std'         => '',
						'type'        => 'text',
						'placeholder' => 'eg: 600px',
					),

					array(
						'name'        => __('Hover Loupe size', 'zn_framework'),
						'description' => __("Please select the hover's loupe icon size.", 'zn_framework'),
						'id'          => 'pg_loupe_size',
						'std'         => 'large',
						'type'        => 'select',
						'options'     => array(
							'xsmall' => __('Extra small', 'zn_framework'),
							'small'  => __('Small', 'zn_framework'),
							'medium' => __('Medium', 'zn_framework'),
							'large'  => __('Large', 'zn_framework'),
						),
					),

					array(
						'name'        => __('Hover Image Animation', 'zn_framework'),
						'description' => __('Please select the images hover animation.', 'zn_framework'),
						'id'          => 'pg_img_anim',
						'std'         => 'none',
						'type'        => 'select',
						'options'     => array(
							'none'    => __('None', 'zn_framework'),
							'fadein'  => __('Fade In Image', 'zn_framework'),
							'fadeout' => __('Fade Out Image', 'zn_framework'),
							'zoomin'  => __('Zoom In', 'zn_framework'),
							'zoomout' => __('Zoom Out', 'zn_framework'),
						),
					),

					array(
						'name'        => __('Lazy Load images', 'zn_framework'),
						'description' => __('Select if you want to lazy load the images.', 'zn_framework'),
						'id'          => 'lazy',
						'std'         => '',
						'type'        => 'toggle2',
						'value'       => 'yes',
					),

					array(
						'id'             => 'element_scheme',
						'name'           => 'Element Color Scheme',
						'description'    => 'Select the color scheme of this element',
						'type'           => 'select',
						'std'            => 'light',
						'options'        => array(
							'light' => 'Light (default)',
							'dark'  => 'Dark',
						),
						'live'        => array(
							'type'         => 'class',
							'css_class'    => '.' . $uid,
							'val_prepend'  => 'gridPhotoGallery--scheme-',

						),
					),
				),
			),

			'images' => array(
				'title'   => 'Images',
				'options' => array(
					$extra_options,
				),
			),

			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url('https://my.hogash.com/video_category/kallyas-wordpress-theme/#Kj7XG3Vm-HQ') ),
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/grid-based-photo-gallery/') ),
				'copy'    => $uid,
				'general' => true,
			)),

		);
		return $options;
	}
}
