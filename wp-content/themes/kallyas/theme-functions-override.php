<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}
/*
 * All functions in this file can be overridden in a child theme.
 * This file is loaded in functions.php
 *
 * @package  Kallyas
 * @category Page Builder
 * @author   Team Hogash
 * @since    3.8.0
 */

/*
 * Load custom functions after the theme loads
 */
if ( ! function_exists( 'wpk_zn_on_init' ) ) {
	/**
	 * Load custom functions after the theme loads
	 *
	 * @hooked to after_setup_theme
	 *
	 * @see functions.php
	 */
	function wpk_zn_on_init() {

		// Check Sensei plugin
		if ( class_exists( 'WooThemes_Sensei' ) ) {
			include( THEME_BASE . '/template_helpers/vendors/sensei/functions-sensei.php' );
		}

		// Check Sensei plugin
		if ( class_exists( 'Simple_Job_Board' ) ) {
			include( THEME_BASE . '/template_helpers/vendors/simple-job-board/functions-sjb.php' );
		}

		// Load generic vendors
		include( THEME_BASE . '/template_helpers/vendors/general/general-vendor.php' );

		// Check Geo Directory plugin
		if ( ! is_admin() && defined( 'GEODIRECTORY_PLUGIN_DIR' ) ) {
			include( THEME_BASE . '/template_helpers/vendors/geo-directory/functions-geo-directory.php' );
		}

		// Check PostLoveHgFrontend plugin
		if ( class_exists( 'PostLoveHgFrontend' ) ) {
			include( THEME_BASE . '/template_helpers/vendors/hogash-post-love/config.php' );
		}

		// Check Sensei plugin
		if ( class_exists( 'WP_Hotel_Booking' ) ) {
			include( THEME_BASE . '/template_helpers/vendors/wp-hotel-booking/wp-hotel-booking.php' );
		}
	}
}

/*
 * Add theme support
 */
if ( ! function_exists( 'wpk_zn_on_after_setup_theme' ) ) {
	/**
	 * Add theme support
	 *
	 * @hooked to after_setup_theme
	 *
	 * @see functions.php
	 */
	function wpk_zn_on_after_setup_theme() {
		load_theme_textdomain( 'zn_framework', THEME_BASE . '/languages' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'nav-menus' );
		add_theme_support( 'title-tag' );

		/* Add post formats
		 *  @since v4.0.12
		 *  , 'gallery'
		 */
		add_theme_support( 'post-formats', array( 'video', 'quote', 'audio', 'link', 'gallery' ) );

		// Add image sizes
		set_post_thumbnail_size( 280, 187 );
		// Content width
		$zn_custom_width = (int)zget_option( 'custom_width', 'layout_options', false, '1170' );
		add_image_size( 'full-width-image', $zn_custom_width );

		add_theme_support( 'post-thumbnails' );
		add_image_size( 'lp_bi_image', 750, 350, true );
	}
}

/*
 * Shortcodes fixer
 */
if ( ! function_exists( 'shortcode_empty_paragraph_fix' ) ) {
	/**
	 * Shortcodes fixer
	 *
	 * @param $content
	 * @hooked to the_content
	 *
	 * @see functions.php
	 *
	 * @return string
	 */
	function shortcode_empty_paragraph_fix( $content ) {
		$array = array( '<p>[' => '[', ']</p>' => ']', ']<br />' => ']' );
		return $content = strtr( $content, $array );
	}
}

/*
 * Check if we are on the taxonomy archive page. We will display all items if it is selected
 */
if ( ! function_exists( 'zn_portfolio_taxonomy_pagination' ) ) {
	/**
	 * Check if we are on the taxonomy archive page. We will display all items if it is selected
	 *
	 * @param $query
	 * @hooked to pre_get_posts
	 *
	 * @see functions.php
	 */
	function zn_portfolio_taxonomy_pagination( $query ) {
		$portfolio_style = zget_option( 'portfolio_style', 'portfolio_options', false, 'portfolio_sortable' );
		$portfolio_per_page_show = zget_option( 'portfolio_per_page_show', 'portfolio_options', false, '4' );
		$load_more = zget_option( 'ptf_sort_loadmore', 'portfolio_options', false, 'no' );

		if ( ( is_tax( 'project_category' ) || is_post_type_archive( 'portfolio' ) ) && $query->is_main_query() ) {
			if ( 'portfolio_sortable' === $portfolio_style && 'yes' !== $load_more ) {
				set_query_var( 'posts_per_page', '-1' );
			} else {
				set_query_var( 'posts_per_page', $portfolio_per_page_show );
			}
		}
	}
}

/*
 * Calculate proper layout size
 */
if ( ! function_exists( 'zn_get_size' ) ) {
	/**
	 * Calculate proper layout size
	 *
	 * @param      $size
	 * @param null $sidebar
	 * @param int  $extra
	 *
	 * @return array
	 */
	function zn_get_size( $size, $sidebar = null, $extra = 0 ) {
		$new_size = array();

		$span_sizes = array(
			"four" => "col-sm-3",
			"one-third" => "col-sm-4",
			"span5" => "col-sm-5",
			"eight" => "col-sm-6",

			// wpk - custom sizes
			// @see: image Box 2
			"span7" => 'col-sm-7',
			"span10" => 'col-sm-10',
			"span11" => 'col-sm-11',

			"two-thirds" => "col-sm-8",
			"twelve" => "col-sm-9",
			"sixteen" => "col-sm-12",
			"portfolio_sortable" => 'portfolio_sortable',

			'span3' => 'col-sm-3',
			'span4' => 'col-sm-4',
			'span6' => 'col-sm-6',
			'span8' => 'col-sm-8',
			'span9' => 'col-sm-9',
			'span11' => 'col-sm-11',
			'span12' => 'col-sm-12',

		);

		// Image sizes for: 1170 LAYOUT
		$zn_width = zget_option( 'zn_width', 'layout_options', false, '1170' );
		if ( '1170' == $zn_width ) {
			$image_width = array(
				"four" => 270, // col-sm-3
				"one-third" => 370, // col-sm-4
				"span5" => 470, // col-sm-5
				"eight" => 570, // col-sm-6
				"two-thirds" => 770, // col-sm-8
				"twelve" => 870, // col-sm-9
				"sixteen" => 1170, // col-sm-12
				"span2" => 170, // col-sm-2
				"span3" => 270, // col-sm-3
				"span4" => 370, // col-sm-4
				"span6" => 570, // col-sm-6
				"span7" => 670, // col-sm-7
				"span8" => 770, // col-sm-8
				"span9" => 870, // col-sm-9
				"span10" => 970, // col-sm-10
				"span11" => 1070, // col-sm-11
				"span12" => 1170, // col-sm-12
				"portfolio_sortable" => 260,   // col-sm-*?
			);
		} elseif ( 'custom' == $zn_width ) {
			$zn_custom_width = (int)zget_option( 'custom_width', 'layout_options', false, '1170' );

			$gutter_size = 15 * 2;
			$onecol = ( $zn_custom_width / 12 );

			$image_width = array(
				"four" => ( ( ( $onecol * 3 ) - $gutter_size ) + $extra ), // col-sm-3
				"one-third" => ( ( ( $onecol * 4 ) - $gutter_size ) + $extra ), // col-sm-4
				"span5" => ( ( ( $onecol * 5 ) - $gutter_size ) + $extra ), // col-sm-5
				"eight" => ( ( ( $onecol * 6 ) - $gutter_size ) + $extra ), // col-sm-6
				"two-thirds" => ( ( ( $onecol * 8 ) - $gutter_size ) + $extra ), // col-sm-8
				"twelve" => ( ( ( $onecol * 9 ) - $gutter_size ) + $extra ), // col-sm-9
				"sixteen" => ( ( ( $onecol * 12 ) - $gutter_size ) + $extra ), // col-sm-12
				"span2" => ( ( ( $onecol * 2 ) - $gutter_size ) + $extra ), // col-sm-2
				"span3" => ( ( ( $onecol * 3 ) - $gutter_size ) + $extra ), // col-sm-3
				"span4" => ( ( ( $onecol * 4 ) - $gutter_size ) + $extra ), // col-sm-4
				"span6" => ( ( ( $onecol * 6 ) - $gutter_size ) + $extra ), // col-sm-6
				"span7" => ( ( ( $onecol * 7 ) - $gutter_size ) + $extra ), // col-sm-7
				"span8" => ( ( ( $onecol * 8 ) - $gutter_size ) + $extra ), // col-sm-8
				"span9" => ( ( ( $onecol * 9 ) - $gutter_size ) + $extra ), // col-sm-9
				"span10" => ( ( ( $onecol * 10 ) - $gutter_size ) + $extra ), // col-sm-10
				"span11" => ( ( ( $onecol * 11 ) - $gutter_size ) + $extra ), // col-sm-11
				"span12" => ( ( ( $onecol * 12 ) - $gutter_size ) + $extra ), // col-sm-12
				"portfolio_sortable" => ( ( ( $onecol * 3 ) - $gutter_size ) + $extra ),   // col-sm-*?
			);
		}

		// Image sizes for anything but 1170 LAYOUT
		else {
			$image_width = array(
				"four" => 220, // DONE
				"one-third" => 370,
				"eight" => 460, // DONE
				"two-thirds" => 770,
				"twelve" => 870,
				"sixteen" => 960, // DONE
				"span3" => 220, // DONE
				"span4" => 300, // DONE
				"span5" => 460,
				"span6" => 460, // DONE
				"span7" => 670,
				"span8" => 770,
				"span9" => 870,
				"span10" => 970,
				"span11" => 1070,
				"span12" => 960, // DONE
				"portfolio_sortable" => 210,
			);
		}

		if ( $sidebar ) {
			$image_width[$size] = $image_width[$size] - 300 - $extra;
		} elseif ( isset( $extra ) ) {
			$image_width[$size] = $image_width[$size] - $extra;
		}

		$n_height = $image_width[$size] / ( 16 / 9 );

		if ( isset( $span_sizes[$size] ) ) {
			$new_size['sizer'] = $span_sizes[$size];
		}

		if ( isset( $image_width[$size] ) ) {
			$new_size['width'] = $image_width[$size];
		}

		$new_size['height'] = $n_height;

		return $new_size;
	}
}

/*
 * Add the "gallery" shortcode
 */
if ( ! function_exists( 'zn_custom_gallery' ) ) {
	/**
	 * Add the "gallery" shortcode
	 *
	 * @param array $attr
	 * @hooked to add_shortcode
	 *
	 * @see functions.php
	 *
	 * @return mixed|string|void
	 */
	function zn_custom_gallery( $attr ) {
		global $post;

		static $instance = 0;

		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] ) {
				unset( $attr['orderby'] );
			}
		}

		// declare vars
		$id = 0;
		$order = 'RAND';
		$orderby = 'none';
		$size = 0;
		$itemtag = $captiontag = $icontag = '';
		$columns = 0;

		extract( shortcode_atts( array(
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'id' => $post->ID,
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'size' => 'thumbnail',
			'include' => '',
			'exclude' => '',
		), $attr, 'gallery' ) );

		$id = intval( $id );

		if ( 'RAND' == $order ) {
			$orderby = 'none';
		}

		if ( ! empty( $include ) ) {
			$_attachments = get_posts( array(
				'include' => $include,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby,
			) );
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $exclude ) ) {
			$attachments = get_children( array(
				'post_parent' => $id,
				'exclude' => $exclude,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby,
			) );
		} else {
			$attachments = get_children( array(
				'post_parent' => $id,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => $orderby,
			) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape( $itemtag );
		$captiontag = tag_escape( $captiontag );
		$icontag = tag_escape( $icontag );
		$valid_tags = wp_kses_allowed_html( 'post' );

		if ( ! isset( $valid_tags[$itemtag] ) ) {
			$itemtag = 'dl';
		}
		if ( ! isset( $valid_tags[$captiontag] ) ) {
			$captiontag = 'dd';
		}
		if ( ! isset( $valid_tags[$icontag] ) ) {
			$icontag = 'dt';
		}

		$columns = intval( $columns );
		$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
		$float = is_rtl() ? 'right' : 'left';
		$selector = "gallery-{$instance}";
		$gallery_style = $gallery_div = '';

		if ( apply_filters( 'use_default_gallery_style', true ) ) {
			$gallery_style = "<style type=\"text/css\">
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
			</style><!-- see gallery_shortcode() in wp-includes/media.php -->
			";
		}

		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id=\"$selector\" class=\"gallery galleryid-{$id} mfp-gallery mfp-gallery--images gallery-columns-{$columns} gallery-size-{$size_class}\">";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
		$num_ids = count( $attachments );
		$i = 1;
		$c = 1;
		$num_columns = 12 / $columns;
		$uid = uniqid( 'pp_' );

		foreach ( $attachments as $id => $attachment ) {
			if ( 1 == $c || 0 == $c % ( $columns + 1 ) ) {
				$output .= '<div class="row zn_image_gallery ">';
				$c = 1;
			}

			if ( $captiontag && trim( $attachment->post_excerpt ) ) {
				$title_caption = wptexturize( $attachment->post_excerpt );
			} else {
				$title_caption = '';
			}

			$output .= '<div class="col-sm-6 col-md-4 col-lg-' . $num_columns . '">';
			$output .= '<a href="' . wp_get_attachment_url( $id ) . '" title="' . $title_caption . '" class="hoverBorder">';
			$output .= wp_get_attachment_image( $id, $size, 0, $attr );

			// Show caption
			$output .= '<span class="gallery_caption">';
			$output .= $title_caption;
			$output .= '</span>';

			$output .= '</a>';
			$output .= '</div>';

			if ( ( $columns > 0 && 0 == $i % $columns ) || $i == $num_ids ) {
				$output .= '</div>';
			}

			$i++;
			$c++;
		}
		$output .= '</div>';
		return $output;
	}
}
remove_shortcode( 'gallery' );
add_shortcode( 'gallery', 'zn_custom_gallery' );

/*
 * Add extra data to head
 */
if ( ! function_exists( 'zn_head' ) ) {
	/**
	 * Add extra data to head
	 *
	 * @hooked to wp_head
	 *
	 * @see functions.php
	 */
	function zn_head() {
		?>

		<!--[if lte IE 8]>
		<script type="text/javascript">
			var $buoop = {
				vs: {i: 10, f: 25, o: 12.1, s: 7, n: 9}
			};

			$buoop.ol = window.onload;

			window.onload = function () {
				try {
					if ($buoop.ol) {
						$buoop.ol()
					}
				}
				catch (e) {
				}

				var e = document.createElement("script");
				e.setAttribute("type", "text/javascript");
				e.setAttribute("src", "<?php echo get_current_scheme(); ?>://browser-update.org/update.js");
				document.body.appendChild(e);
			};
		</script>
		<![endif]-->

		<!-- for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php
	}
}

/*
 * Page pre-loading
 */
if ( ! function_exists( 'zn_page_loading' ) ) {
	/**
	 * Page pre-loading
	 */
	function zn_page_loading() {
		$page_preloader = zget_option( 'page_preloader', 'general_options', false, 'no' );
		if ( 'no' != $page_preloader ) {
			echo '<div id="page-loading" class="kl-pageLoading--' . $page_preloader . '">';

			$page_preloader_img = zget_option( 'page_preloader_img', 'general_options', false, '' );

			if ( 'yes' == $page_preloader ) {
				echo '<div class="preloader-pulsating-circle border-custom"></div>';
			} else {
				if ( 'yes_spinner' == $page_preloader ) {
					echo '<div class="preloader-material-spinner"><svg class="preloader-material-svg" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="preloader-material-circle" fill="none" stroke-width="3" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>';
				} else {
					if ( 'yes_persp' == $page_preloader ) {
						echo '<div class="preloader-perspective-anim kl-main-bgcolor"></div>';
					} else {
						if ( 'yes_img_persp' == $page_preloader && ! empty( $page_preloader_img ) ) {
							echo '<div class="preloader-perspective-img"><img src="' . $page_preloader_img . '"></div>';
						} else {
							if ( 'yes_img_breath' == $page_preloader && ! empty( $page_preloader_img ) ) {
								echo '<div class="preloader-breath-img"><img src="' . $page_preloader_img . '"></div>';
							} else {
								if ( 'yes_img' == $page_preloader && ! empty( $page_preloader_img ) ) {
									echo '<div class="preloader-img"><img src="' . $page_preloader_img . '"></div>';
								}
							}
						}
					}
				}
			}

			echo '</div>';
		}
	}
}

/*
 * Display Google analytics to page
 */
if ( ! function_exists( 'add_googleanalytics' ) ) {
	/**
	 * Display Google analytics to page
	 *
	 * @hooked to wp_footer
	 *
	 * @see functions.php
	 */
	function add_googleanalytics() {
		if ( $google_analytics = zget_option( 'google_analytics', 'general_options' ) ) {
			echo stripslashes( $google_analytics );
		}
	}
}

/*
 * Display Google tag manager after opening body to page
 */
add_action('zn_after_body', 'add_googletagmanager', 1);
if ( ! function_exists( 'add_googletagmanager' ) ) {
	/**
	 * Display Google tag manger to page (after opening body tag)
	 *
	 * @hooked to zn_after_body action
	 *
	 * @see header.php
	 */
	function add_googletagmanager() {
		if ( $google_tag_manager = zget_option( 'google_tag_manager', 'general_options' ) ) {
			echo $google_tag_manager;
		}
	}
}


/*
 * Register menus
 */
if ( ! function_exists( 'zn_register_menu' ) ) {
	/**
	 * Register menus
	 *
	 * @hooked to init
	 *
	 * @see functions.php
	 */
	function zn_register_menu() {
		if ( function_exists( 'wp_nav_menu' ) ) {
			register_nav_menus( array(
				'main_navigation' => esc_html__( 'Main Navigation', 'zn_framework' ),
			) );
			register_nav_menus( array(
				'header_navigation' => esc_html__( 'Header Navigation ( Top Bar )', 'zn_framework' ),
			) );
			register_nav_menus( array(
				'footer_navigation' => esc_html__( 'Footer Navigation', 'zn_framework' ),
			) );
		}
	}
}


/*
 * Load video iframe from link
 */
if ( ! function_exists( 'get_video_from_link' ) ) {
	/**
	 * Load video iframe from link
	 *
	 * @param string     $string
	 * @param null       $css
	 * @param int        $width
	 * @param int        $height
	 * @param null|mixed $video_attributes
	 *
	 * @return mixed|null|string
	 */
	function get_video_from_link( $string, $css = null, $width = 425, $height = 239, $video_attributes = null ) {
		// Save old string in case no video is provided
		$old_string = $string;
		$video_url = parse_url( $string );

		$extra_options = array();
		$extra_options_str = '';

		if ( ! empty( $video_attributes ) && is_array( $video_attributes ) ) {
			$extra_options[] = 'autoplay=' . ( isset( $video_attributes['autoplay'] ) && ! empty( $video_attributes['autoplay'] ) ? $video_attributes['autoplay'] : 0 );
			$extra_options[] = 'loop=' . ( isset( $video_attributes['loop'] ) && ! empty( $video_attributes['loop'] ) ? $video_attributes['loop'] : 0 );
			$extra_options[] = 'controls=' . ( isset( $video_attributes['controls'] ) && ! empty( $video_attributes['controls'] ) ? $video_attributes['controls'] : 0 );
		}

		if ( 'www.youtube.com' == $video_url['host'] || 'youtube.com' == $video_url['host'] || 'www.youtu.be' == $video_url['host'] || 'youtu.be' == $video_url['host'] ) {
			if ( ! empty( $video_attributes ) && is_array( $video_attributes ) ) {
				// Youtube Specific
				$extra_options[] = 'modestbranding=' . ( isset( $video_attributes['yt_modestbranding'] ) && ! empty( $video_attributes['yt_modestbranding'] ) ? $video_attributes['yt_modestbranding'] : 1 );
				$extra_options[] = 'autohide=' . ( isset( $video_attributes['yt_autohide'] ) && ! empty( $video_attributes['yt_autohide'] ) ? $video_attributes['yt_autohide'] : 1 );
				$extra_options[] = 'showinfo=' . ( isset( $video_attributes['yt_showinfo'] ) && ! empty( $video_attributes['yt_showinfo'] ) ? $video_attributes['yt_showinfo'] : 0 );
				$extra_options[] = 'rel=' . ( isset( $video_attributes['yt_rel'] ) && ! empty( $video_attributes['yt_rel'] ) ? $video_attributes['yt_rel'] : 0 );
			}

			preg_match( '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^\n]+#', $string, $matches );
			if ( isset( $video_attributes['loop'] ) && ! empty( $video_attributes['loop'] ) ) {
				$extra_options[] = 'playlist=' . $matches[0];
			}
			$string = '<iframe class="' . $css . '" width="' . $width . '" height="' . $height . '" src="//www.youtube.com/embed/' . $matches[0] . '?iv_load_policy=3&amp;enablejsapi=0&amp;wmode=opaque&amp;feature=player_embedded&amp;' . implode( '&amp;', $extra_options ) . '" allowfullscreen></iframe>';
		} elseif ( 'www.dailymotion.com' == $video_url['host'] ) {
			$id = strtok( basename( $old_string ), '_' );
			$string = '<iframe width="' . $width . '" height="' . $height . '" src="//www.dailymotion.com/embed/video/' . $id . '?' . implode( '&amp;', $extra_options ) . '"></iframe>';
		} elseif ( 'vimeo.com' == $video_url['host'] || 'www.vimeo.com' == $video_url['host'] ) {
			if ( ! empty( $video_attributes ) && is_array( $video_attributes ) ) {
				// Vimeo Specific
				$extra_options[] = 'title=' . ( isset( $video_attributes['vim_title'] ) && ! empty( $video_attributes['vim_title'] ) ? $video_attributes['vim_title'] : 1 );
				$extra_options[] = 'byline=' . ( isset( $video_attributes['vim_byline'] ) && ! empty( $video_attributes['vim_byline'] ) ? $video_attributes['vim_byline'] : 1 );
				$extra_options[] = 'portrait=' . ( isset( $video_attributes['vim_portrait'] ) && ! empty( $video_attributes['vim_portrait'] ) ? $video_attributes['vim_portrait'] : 1 );
			}

			$string = preg_replace(
				array(
					'#http://(www\.)?vimeo\.com/([^ ?\n/]+)((\?|/).*?(\n|\s))?#i',
					'#https://(www\.)?vimeo\.com/([^ ?\n/]+)((\?|/).*?(\n|\s))?#i',
				),
				'<iframe ' . WpkPageHelper::zn_schema_markup( 'video' ) . ' class="youtube-player ' . $css . '" src="//player.vimeo.com/video/$2?' . implode( '&amp;', $extra_options ) . '" width="' . $width . '" height="' . $height . '" allowFullScreen></iframe>', $string );
		} else {
			$string = '<iframe ' . WpkPageHelper::zn_schema_markup( 'video' ) . ' class="' . $css . '" width="' . $width . '" height="' . $height . '" src="' . $old_string . '"></iframe>';
		}

		// If no video link was provided return the full link
		return ( $string != $old_string ) ? $string : null;
	}
}

/*
 * Comments display function
 */
if ( ! function_exists( 'zn_comment' ) ) {
	/**
	 * Comments display function
	 *
	 * @param $comment
	 * @param $args
	 * @param $depth
	 */
	function zn_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class( 'kl-comment' ); ?> id="li-comment-<?php comment_ID() ?>"  <?php echo WpkPageHelper::zn_schema_markup( 'comment' ); ?>>
		<div id="comment-<?php comment_ID(); ?>" class="kl-comment__wrapper">
			<div
				class="comment-author vcard kl-comment__author" <?php echo WpkPageHelper::zn_schema_markup( 'comment_author' ); ?>>
				<?php echo get_avatar( $comment, $size = '50' ); ?>
				<?php printf( __( '<cite class="fn">%s</cite>', 'zn_framework' ), get_comment_author_link() ) ?> <?php echo __( "says :", 'zn_framework' ); ?><?php comment_reply_link( array_merge( $args, array(
					'depth' => $depth,
					'max_depth' => $args['max_depth'],
				) ) ) ?>
			</div>

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'zn_framework' ) ?></em>
				<br/>
			<?php endif; ?>

			<div class="comment-meta commentmetadata kl-comment__meta">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"
				   class="kl-comment__meta-link" <?php echo WpkPageHelper::zn_schema_markup( 'comment_time' ); ?>>
					<?php printf( __( '%1$s at %2$s', 'zn_framework' ), get_comment_date(), get_comment_time() ) ?>
				</a>
				<?php edit_comment_link( __( '(Edit)', 'zn_framework' ), '  ', '' ) ?>
			</div>
			<div class="kl-comment__text" <?php echo WpkPageHelper::zn_schema_markup( 'comment_text' ); ?>>
				<?php comment_text() ?>
			</div>

			<div class="zn-separator sep_normal zn-margin-d kl-comments-sep"></div>
		</div>
		<?php
	}
}

//<editor-fold desc=">>> REGISTER SIDEBARS">

if ( ! function_exists( 'wpkRegisterSidebars' ) ) {
	/**
	 * Register theme sidebars
	 *
	 * @hooked to widgets_init
	 *
	 * @since 4.0.0
	 */
	function wpkRegisterSidebars() {
		if ( function_exists( 'register_sidebar' ) ) {
			$sidebar_widget_title_tag = apply_filters('zn_sidebar_widget_title_tag', 'h3');
			$footer_widget_title_tag = apply_filters('zn_footer_widget_title_tag', 'h3');

			/*
			 * Default sidebar
			 */
			register_sidebar( array(
				'name' => 'Default Sidebar',
				'id' => 'defaultsidebar',
				'description' => esc_html__( "This is the default sidebar. You can choose from the theme's options page where
										the widgets from this sidebar will be shown.", 'zn_framework' ),
				'before_widget' => '<div id="%1$s" class="widget zn-sidebar-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<' . $sidebar_widget_title_tag . ' class="widgettitle zn-sidebar-widget-title title">',
				'after_title' => '</' . $sidebar_widget_title_tag . '>',
			) );
			/*
			 * Hidden Panel sidebar
			 */
			register_sidebar( array(
				'name' => 'Hidden Panel Sidebar',
				'id' => 'hiddenpannelsidebar',
				'description' => esc_html__( "This is the sidebar for the hidden panel in the header. You can choose from the
								theme's options page where the widgets from this sidebar will be shown.", 'zn_framework' ),
				'before_widget' => '<div id="%1$s" class="widget support-panel-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<' . $sidebar_widget_title_tag . ' class="widgettitle title support-panel-widgettitle">',
				'after_title' => '</' . $sidebar_widget_title_tag . '>',

			) );
			// Footer sidebar 1
			$footer_row1_widget_positions = zget_option( 'footer_row1_widget_positions', 'general_options', false, '{"3":[["4","4","4"]]}' );

			$f_row1 = key( json_decode( stripslashes( $footer_row1_widget_positions ) ) );
			if ( $f_row1 > 1 ) {
				register_sidebars( $f_row1, array(
					'name' => 'Footer row 1 - widget %d',
					'id' => "znfooter",
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<' . $sidebar_widget_title_tag . ' class="widgettitle title m_title m_title_ext text-custom">',
					'after_title' => '</' . $sidebar_widget_title_tag . '>',
				) );
			} else {
				register_sidebars( 1, array(
					'name' => 'Footer row 1 - widget 1',
					'id' => "znfooter",
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<' . $sidebar_widget_title_tag . ' class="widgettitle title m_title m_title_ext text-custom">',
					'after_title' => '</' . $sidebar_widget_title_tag . '>',
				) );
			}

			// Footer sidebar 2
			$footer_row2_widget_positions = zget_option( 'footer_row2_widget_positions', 'general_options', false, '{"3":[["4","4","4"]]}' );

			$f_row1 = key( json_decode( stripslashes( $footer_row2_widget_positions ) ) );
			if ( $f_row1 > 1 ) {
				register_sidebars( $f_row1, array(
					'name' => 'Footer row 2 - widget %d',
					'id' => "znfooter",
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<' . $footer_widget_title_tag . ' class="widgettitle title m_title m_title_ext text-custom">',
					'after_title' => '</' . $footer_widget_title_tag . '>',
				) );
			} else {
				register_sidebars( 1, array(
					'name' => 'Footer row 2 - widget 1',
					'id' => "znfooter",
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<' . $footer_widget_title_tag . ' class="widgettitle title m_title m_title_ext text-custom">',
					'after_title' => '</' . $footer_widget_title_tag . '>',
				) );
			}

			// global $wp_registered_sidebars;
			// Dynamic sidebars
			if ( $unlimited_sidebars = zget_option( 'unlimited_sidebars', 'unlimited_sidebars' ) ) {
				foreach ( $unlimited_sidebars as $sidebar ) {
					if ( $sidebar['sidebar_name'] ) {

						// $i = count($wp_registered_sidebars) + 1;

						register_sidebar( array(
							'name' => $sidebar['sidebar_name'],
							'id' => zn_sanitize_widget_id( $sidebar['sidebar_name'] ),
							'before_widget' => '<div id="%1$s" class="widget zn-sidebar-widget %2$s">',
							'after_widget' => '</div>',
							'before_title' => '<' . $sidebar_widget_title_tag . ' class="widgettitle zn-sidebar-widget-title title">',
							'after_title' => '</' . $sidebar_widget_title_tag . '>',
						) );
					}
				}
			}
		}
	}
}
//</editor-fold desc=">>> REGISTER SIDEBARS">


/*
 * Get current scheme
 */
if ( ! function_exists( 'get_current_scheme' ) ) {
	/**
	 * Get current scheme
	 *
	 * @return string
	 */
	function get_current_scheme() {
		$scheme = 'http';
		if ( is_ssl() ) {
			$scheme .= "s";
		}
		return $scheme;
	}
}

/*
 * Get current page URL
 */
if ( ! function_exists( 'current_page_url' ) ) {
	/**
	 * Get current page URL
	 *
	 * @return string
	 */
	function current_page_url() {
		$pageURL = get_current_scheme() . '://';
		if ( isset( $_SERVER["SERVER_PORT"] ) && ( 80 != (int)$_SERVER["SERVER_PORT"] ) ) {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}

/*
 * Remove the "Read More" tag from excerpt
 */
if ( ! function_exists( 'clear_excerpt_more' ) ) {
	/**
	 * Remove the "Read More" tag from excerpt
	 *
	 * @param $more
	 * @hooked to excerpt_more
	 *
	 * @see functions.php
	 *
	 * @return string
	 */
	function clear_excerpt_more( $more ) {
		return '';
	}
}


/*
 * Flush WP Rewrite rules
 */
if ( ! function_exists( 'zn_rewrite_flush' ) ) {
	/**
	 * Flush WP Rewrite rules
	 *
	 * @hooked to after_switch_theme
	 *
	 * @see functions.php
	 */
	function zn_rewrite_flush() {
		flush_rewrite_rules();
	}
}

/*
 * Register the Custom Post Type: Portfolio
 */
if ( ! function_exists( 'zn_portfolio_post_type' ) ) {
	/**
	 * Register the Custom Post Type: Portfolio
	 *
	 * @hooked to init
	 *
	 * @see functions.php
	 */
	function zn_portfolio_post_type() {
		$permalinks = get_option( 'zn_permalinks' );
		$slug = true;

		if ( isset( $permalinks['portfolio'] ) && ! empty( $permalinks['portfolio'] ) ) {
			$slug = array( 'slug' => $permalinks['portfolio'] );
		}

		$labels = array(
			'name' => __( 'Portfolios', 'zn_framework' ),
			'singular_name' => __( 'Portfolio Item', 'zn_framework' ),
			'add_new' => __( 'Add New Portfolio Item', 'zn_framework' ),
			'all_items' => __( 'All Portfolio Items', 'zn_framework' ),
			'add_new_item' => __( 'Add New Portfolio', 'zn_framework' ),
			'edit_item' => __( 'Edit Portfolio Item', 'zn_framework' ),
			'new_item' => __( 'New Portfolio Item', 'zn_framework' ),
			'view_item' => __( 'View Portfolio Item', 'zn_framework' ),
			'search_items' => __( 'Search Portfolio Items', 'zn_framework' ),
			'not_found' => __( 'No Portfolio Items found', 'zn_framework' ),
			'not_found_in_trash' => __( 'No Portfolio Items found in trash', 'zn_framework' ),
			'parent_item_colon' => __( 'Parent Portfolio:', 'zn_framework' ),
			'menu_name' => __( 'Portfolio Items', 'zn_framework' ),
		);

		$args = array(
			'labels' => $labels,
			'description' => "",
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 100,
			'menu_icon' => THEME_BASE_URI . '/images/portfolio.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'excerpt' ),
			'has_archive' => true,
			'rewrite' => $slug,
			'query_var' => true,
			'can_export' => true,
		);
		register_post_type( 'portfolio', $args );
	}
}

/*
 * Register the Portfolio Post Taxonomy
 */
if ( ! function_exists( 'zn_portfolio_category' ) ) {
	/**
	 * Register the Portfolio Post Taxonomy
	 *
	 * @hooked to init
	 *
	 * @see functions.php
	 */
	function zn_portfolio_category() {
		$slug = true;
		$permalinks = get_option( 'zn_permalinks' );

		if ( isset( $permalinks['project_category'] ) && ! empty( $permalinks['project_category'] ) ) {
			$slug = array( 'slug' => $permalinks['project_category'] );
		}

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name' => __( 'Portfolio Categories', 'zn_framework' ),
			'singular_name' => __( 'Portfolio Category', 'zn_framework' ),
			'search_items' => __( 'Search Portfolio Categories', 'zn_framework' ),
			'all_items' => __( 'All Portfolio Categories', 'zn_framework' ),
			'parent_item' => __( 'Parent Portfolio Category', 'zn_framework' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:', 'zn_framework' ),
			'edit_item' => __( 'Edit Portfolio Category', 'zn_framework' ),
			'update_item' => __( 'Update Portfolio Category', 'zn_framework' ),
			'add_new_item' => __( 'Add New Portfolio Category', 'zn_framework' ),
			'new_item_name' => __( 'New Portfolio Category Name', 'zn_framework' ),
			'menu_name' => __( 'Portfolio categories', 'zn_framework' ),

		);

		register_taxonomy( 'project_category', 'portfolio', array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => $slug,
		) );
	}
}

add_action( 'init', 'zn_portfolio_tags' );
add_filter( 'zn_allowed_post_types', 'zn_add_portfolio_slugs' );
add_filter( 'zn_allowed_taxonomies', 'zn_add_portfolio_taxonomy_slugs' );

if ( ! function_exists( 'zn_portfolio_tags' ) ) {
	function zn_portfolio_tags() {
		$slug = true;
		$permalinks = get_option( 'zn_permalinks' );

		if ( ! empty( $permalinks['portfolio_tags'] ) ) {
			$slug = array( 'slug' => $permalinks['portfolio_tags'] );
		}

		register_taxonomy(
			'portfolio_tags',
			'portfolio',
			array(
				'label' => __( 'Portfolio tags', 'zn_framework' ),
				'rewrite' => $slug,
				'hierarchical' => false,
			)
		);
	}
}

if ( ! function_exists( 'zn_add_portfolio_slugs' ) ) {
	function zn_add_portfolio_slugs( $post_types ) {
		$post_types['portfolio'] = 'Portfolio';
		$post_types['documentation'] = 'Documentation';

		return $post_types;
	}
}
if ( ! function_exists( 'zn_add_portfolio_taxonomy_slugs' ) ) {
	function zn_add_portfolio_taxonomy_slugs( $taxonomies ) {
		$taxonomies['portfolio'] = array(
			array(
				'id' => 'project_category',
				'name' => 'Portfolio categories',
			),
			array(
				'id' => 'portfolio_tags',
				'name' => 'Portfolio tags',
			),
		);

		$taxonomies['documentation'] = array(
			array(
				'id' => 'documentation_category',
				'name' => 'Documentation categories',
			),
		);

		return $taxonomies;
	}
}


/*
 * Register the Documentation Custom Post Type
 */
if ( ! function_exists( 'zn_documentation_post_type' ) ) {
	/**
	 * Register the Documentation Custom Post Type
	 *
	 * @hooked to init
	 *
	 * @see functions.php
	 */
	function zn_documentation_post_type() {
		$slug = true;
		$permalinks = get_option( 'zn_permalinks' );

		if ( ! empty( $permalinks['documentation'] ) ) {
			$slug = array( 'slug' => $permalinks['documentation'] );
		}

		$labels = array(
			'name' => __( 'Documentation', 'zn_framework' ),
			'singular_name' => __( 'Documentation Item', 'zn_framework' ),
			'add_new' => __( 'Add New Documentation Item', 'zn_framework' ),
			'all_items' => __( 'All Documentation Items', 'zn_framework' ),
			'add_new_item' => __( 'Add New Documentation', 'zn_framework' ),
			'edit_item' => __( 'Edit Documentation Item', 'zn_framework' ),
			'new_item' => __( 'New Documentation Item', 'zn_framework' ),
			'view_item' => __( 'View Documentation Item', 'zn_framework' ),
			'search_items' => __( 'Search Documentation Items', 'zn_framework' ),
			'not_found' => __( 'No Documentation Items found', 'zn_framework' ),
			'not_found_in_trash' => __( 'No Documentation Items found in trash', 'zn_framework' ),
			'parent_item_colon' => __( 'Parent Documentation:', 'zn_framework' ),
			'menu_name' => __( 'Documentation Items', 'zn_framework' ),
		);

		$args = array(
			'labels' => $labels,
			'description' => "",
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 100,
			'menu_icon' => THEME_BASE_URI . '/images/portfolio.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor' ),
			'has_archive' => true,
			'rewrite' => $slug,
			'query_var' => true,
			'can_export' => true,
		);
		register_post_type( 'documentation', $args );
	}
}

/*
 * Register the Documentation Post Taxonomy
 */
if ( ! function_exists( 'zn_documentation_category' ) ) {
	/**
	 * Register the Documentation Post Taxonomy
	 *
	 * @hooked to init
	 *
	 * @see functions.php
	 */
	function zn_documentation_category() {
		$slug = true;
		$permalinks = get_option( 'zn_permalinks' );

		if ( ! empty( $permalinks['documentation_category'] ) ) {
			$slug = array( 'slug' => $permalinks['documentation_category'] );
		}

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name' => __( 'Documentation Categories', 'zn_framework' ),
			'singular_name' => __( 'Documentation Category', 'zn_framework' ),
			'search_items' => __( 'Search Documentation Categories', 'zn_framework' ),
			'all_items' => __( 'All Documentation Categories', 'zn_framework' ),
			'parent_item' => __( 'Parent Documentation Category', 'zn_framework' ),
			'parent_item_colon' => __( 'Parent Documentation Category:', 'zn_framework' ),
			'edit_item' => __( 'Edit Documentation Category', 'zn_framework' ),
			'update_item' => __( 'Update Documentation Category', 'zn_framework' ),
			'add_new_item' => __( 'Add New Documentation Category', 'zn_framework' ),
			'new_item_name' => __( 'New Documentation Category Name', 'zn_framework' ),
			'menu_name' => __( 'Documentation categories', 'zn_framework' ),
		);

		register_taxonomy( 'documentation_category', 'documentation', array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => $slug,
		) );
	}
}

/*
 * Display the breadcrumb menu
 */
if ( ! function_exists( 'zn_breadcrumbs' ) ) {
	/**
	 * Display the breadcrumb menu
	 *
	 * @param mixed $args
	 */
	function zn_breadcrumbs( $args = array() ) {
		global $post, $wp_query;

		$defaults = array(
			'delimiter' => '&raquo;',
			'show_home' => true,
			'home_text' => __( 'Home', 'zn_framework' ),
			'home_link' => home_url(),
			'show_current' => true, // show current post/page title in breadcrumbs
			'style' => zget_option( 'def_subh_bread_stl', 'general_options', false, 'black' ),
		);

		$args     = wp_parse_args($args, $defaults);
		$item_position = 1;

		$before = '<span class="current">'; // tag before the current crumb
		$after = '</span>'; // tag after the current crumb

		$prepend = '';

		$breadcrumb_style = 'bread-style--' . $args['style'];

		if ( znfw_is_woocommerce_active() ) {
			$shop_page_id = wc_get_page_id( 'shop' );
			$shop_page = get_post( $shop_page_id );


			if ( $shop_page_id && get_option( 'page_on_front' ) !== $shop_page_id ) {
				$has_shop_page =  get_permalink( wc_get_page_id( 'shop' ) );
				if ( ! empty( $has_shop_page ) ) {
					$shop_position = $args['show_home'] ? $item_position + 1 : $item_position;
					$prepend = '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '"><span property="name">' . get_the_title( wc_get_page_id( 'shop' ) );
					$prepend .= '</span></a><meta property="position" content="' . $shop_position . '"></li>';
				}
			}
		}


		if ( is_front_page() && $args['show_home'] ) {
			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList" class="breadcrumbs fixclear ' . $breadcrumb_style . '"><li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '"><span property="name">' . $args['home_text'] . '</span</a><meta property="position" content="' . $item_position . '"></li></ul>';
			$item_position++;
		} elseif ( is_home() && $args['show_home'] ) {
			$title = zget_option( 'archive_page_title', 'blog_options' );
			$title = do_shortcode( $title );

			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList" class="breadcrumbs fixclear ' . $breadcrumb_style . '">
					<li property="itemListElement" typeof="ListItem">
						<a property="item" typeof="WebPage" href="' . $args['home_link'] . '">
							<span property="name">' . $args['home_text'] . '</span>
						</a><meta property="position" content="' . $item_position . '">
					</li>
					<li>' . $title . '</li>
				</ul>';
		} else {
			$bClass = 'breadcrumbs fixclear ' . $breadcrumb_style;
			echo '<ul vocab="http://schema.org/" typeof="BreadcrumbList"';
			if ( is_search() ) {
				$bClass .= ' th-search-page-mtop';
			}

			echo ' class="' . $bClass . '">';

			if ( $args['show_home'] ) {
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '"><span property="name">' . $args['home_text'] . '</span></a><meta property="position" content="' . $item_position . '"></li>';
				$item_position++;
			}

			if ( is_category() ) {
				$thisCat = get_category( get_query_var( 'cat' ), false );

				if ( 0 != $thisCat->parent ) {
					$cats = get_category_parents( $thisCat->parent, true, '|zn_preg|' );
				} else {
					$cats = get_category_parents( $thisCat->term_id, true, '|zn_preg|' );
				}

				if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
					$cats = explode( '|zn_preg|', $cats );
					foreach ( $cats as $s_cat ) {
						if ( ! empty( $s_cat ) ) {
							$result = '';
							$s_cat_name = strip_tags( $s_cat );
							preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $s_cat, $result);
							if ( ! empty( $result['href'][0] ) ) {
								$s_cat_link = $result['href'][0];
								echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $s_cat_link . '"><span property="name">' . $s_cat_name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
								$item_position++;
							}
						}
					}
				}
				echo '<li>' . __( "Archive from category ", 'zn_framework' ) . '"' . single_cat_title( '', false ) . '"</li>';
			} elseif ( is_tax( 'product_cat' ) ) {
				echo $prepend;
				if ( $args['show_home'] ) {
					$item_position++;
				}

				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$parents = array();
				$parent = $term->parent;

				while ( $parent ) {
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
					$parent = $new_parent->parent;
				}

				if ( ! empty( $parents ) ) {
					$parents = array_reverse( $parents );

					foreach ( $parents as $parent ) {
						$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
						get_term_link( $item->slug, 'product_cat' ) . '"><span property="name">' . $item->name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
						$item_position++;
					}
				}
				$queried_object = $wp_query->get_queried_object();
				echo '<li>' . $queried_object->name . '</li>';
			} elseif ( is_tax( 'project_category' ) || is_post_type_archive( 'portfolio' ) ) {
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

				if ( ! empty( $term->parent ) ) {
					$parents = array();
					$parent = $term->parent;

					while ( $parent ) {
						$parents[] = $parent;
						$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						$parent = $new_parent->parent;
					}

					if ( ! empty( $parents ) ) {
						$parents = array_reverse( $parents );

						foreach ( $parents as $parent ) {
							$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
							echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
							get_term_link( $item->slug, 'project_category' ) . '"><span property="name">' . $item->name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
							$item_position++;
						}
					}
				}
				$queried_object = $wp_query->get_queried_object();
				$menuItem = $queried_object->name;
				//@wpk: #68 - Replace "portfolio" with the one set by the user in the permalinks page
				if ( 0 == strcasecmp( 'portfolio', $queried_object->name ) ) {
					$menuItem = $queried_object->rewrite['slug'];
				}
				echo '<li>' . $menuItem . '</li>';
			} elseif ( is_tax( 'documentation_category' ) ) {
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$parents = array();
				$parent = $term->parent;

				while ( $parent ) {
					$parents[] = $parent;
					$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
					$parent = $new_parent->parent;
				}

				if ( ! empty( $parents ) ) {
					$parents = array_reverse( $parents );

					foreach ( $parents as $parent ) {
						$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ) );
						echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
						get_term_link( $item->slug, 'documentation_category' ) . '"><span property="name">' . $item->name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
						$item_position++;
					}
				}
				$queried_object = $wp_query->get_queried_object();
				echo '<li>' . $queried_object->name . '</li>';
			} elseif ( is_search() ) {
				echo '<li>' . __( "Search results for ", 'zn_framework' ) . '"' . get_search_query() . '"</li>';
			} elseif ( is_day() ) {
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_year_link( get_the_time( 'Y' ) ) . '"><span property="name">' . get_the_time( 'Y' ) . '</span></a></li>';
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '"><span property="name">' . get_the_time( 'F' ) . '</span></a></li>';
				echo '<li>' . get_the_time( 'd' ) . '</li>';
			} elseif ( is_month() ) {
				echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"  href="' .
					get_year_link( get_the_time( 'Y' ) ) . '"><span property="name">' . get_the_time( 'Y' ) . '</span></a><meta property="position" content="' . $item_position . '"></li>';
				echo '<li>' . get_the_time( 'F' ) . '</li>';
				$item_position++;
			} elseif ( is_year() ) {
				echo '<li>' . get_the_time( 'Y' ) . '</li>';
			} elseif ( is_post_type_archive( 'product' ) && get_option( 'page_on_front' ) !== wc_get_page_id( 'shop' ) ) {
				$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : ucwords( get_option( 'woocommerce_shop_slug' ) );

				if ( is_search() ) {
					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_post_type_archive_link( 'product' ) . '"><span property="name">' . $_name . '</span></a><meta property="position" content="' . $item_position . '"></li><li>' .
						__( 'Search results for &ldquo;', 'zn_framework' ) . get_search_query() . '</li>';
					$item_position++;
				} elseif ( is_paged() ) {
					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_post_type_archive_link( 'product' ) . '"><span property="name">' . $_name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
					$item_position++;
				} else {
					echo '<li>' . $_name . '</li>';
				}
			} elseif ( is_single() && ! is_attachment() ) {
				if ( 'portfolio' == get_post_type() ) {
					// Show category name
					$cats = get_the_term_list( $post->ID, 'project_category', ' ', '|zn_preg|', '|zn_preg|' );
					$cats = explode( '|zn_preg|', $cats );
					if ( ! empty( $cats[0] ) ) {
						$result = '';
						$s_cat_name = strip_tags( $cats[0] );
						preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $cats[0], $result);
						if ( ! empty( $result['href'][0] ) ) {
							$s_cat_link = $result['href'][0];
							echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $s_cat_link . '"><span property="name">' . $s_cat_name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
							$item_position++;
						}
					}
					if ( $args['show_current'] ) {
						// Show post name
						echo '<li>' . get_the_title() . '</li>';
					}
				} elseif ( 'product' == get_post_type() ) {
					echo $prepend;
					if ( $args['show_home'] ) {
						$item_position++;
					}

					// 'orderby' => 'term_id': Fixes empty category when category and parent are not listed in the correct order
					if ( $terms = wp_get_object_terms( $post->ID, 'product_cat', array( 'orderby' => 'term_id' ) ) ) {
						$term = end( $terms );
						$parents = array();
						$parent = $term->parent;

						while ( $parent ) {
							$parents[] = $parent;
							$new_parent = get_term_by( 'id', $parent, 'product_cat' );
							$parent = $new_parent->parent;
						}

						if ( ! empty( $parents ) ) {
							$parents = array_reverse( $parents );

							foreach ( $parents as $parent ) {
								$item = get_term_by( 'id', $parent, 'product_cat' );
								echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
									get_term_link( $item->slug, 'product_cat' ) . '"><span property="name">' . $item->name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
								$item_position++;
							}
						}
						echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
							get_term_link( $term->slug, 'product_cat' ) . '"><span property="name">' . $term->name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
						$item_position++;
					}
					if ( $args['show_current'] ) {
						echo '<li>' . get_the_title() . '</li>';
					}
				} elseif ( 'post' != get_post_type() ) {
					$post_type = get_post_type_object( get_post_type() );
					$slug = $post_type->rewrite;

					echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $args['home_link'] . '/' . $slug['slug'] . '/"><span property="name">' . $post_type->labels->singular_name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
					$item_position++;

					if ( $args['show_current'] ) {
						echo '<li>' . get_the_title() . '</li>';
					}
				} else {
					if ( 'post' == get_post_type() ) {

						// If we are on the posts page and static page is set for blog, add the Post page name
						if ( 'page' == get_option( 'show_on_front' ) ) {
							$posts_page = get_option( 'page_for_posts' );
							if ( $posts_page && '' != $posts_page && is_numeric( $posts_page ) ) {
								$page = get_page( $posts_page );

								echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="' . esc_attr( get_the_title( $posts_page ) ) . '" href="' . esc_url( get_permalink( $posts_page ) ) . '"><span property="name">' . get_the_title( $posts_page ) . '</span></a><meta property="position" content="' . $item_position . '"></li>';
								$item_position++;
							}
						}
					}


					// Show category name
					$cat = get_the_category();
					//var_dump($cat);
					$cat = $cat[0];

					$cats = get_category_parents( $cat, true, '|zn_preg|' );

					if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
						$cats = explode( '|zn_preg|', $cats );
						foreach ( $cats as $s_cat ) {
							if ( ! empty( $s_cat ) ) {
								$result = '';
								$s_cat_name = strip_tags( $s_cat );
								preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $s_cat, $result);
								if ( ! empty( $result['href'][0] ) ) {
									$s_cat_link = $result['href'][0];
									echo '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' . $s_cat_link . '"><span property="name">' . $s_cat_name . '</span></a><meta property="position" content="' . $item_position . '"></li>';
									$item_position++;
								}
							}
						}
					}
					if ( $args['show_current'] ) {
						// Show post name
						echo '<li>' . get_the_title() . '</li>';
					}
				}
			} elseif ( ! is_single() && ! is_page() && 'post' != get_post_type() && ! is_404() ) {
				$post_type = get_post_type_object( get_post_type() );
				if ( ! empty( $post_type->labels->singular_name ) ) {
					echo '<li>' . $post_type->labels->singular_name . '</li>';
				}
			} elseif ( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				$cat = get_the_category( $parent->ID );
				if ( ! empty( $cat ) ) {
					$cat = $cat[0];
					$cats = get_category_parents( $cat, true, ' ' . $args['delimiter'] . ' ' );
					if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
						echo $cats;
					}
					echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>';
					echo '<li>' . get_the_title() . '</li>';
				} else {
					echo '<li>' . get_the_title() . '</li>';
				}
			} elseif ( is_page() && ! is_subpage() ) {
				if ( $args['show_current'] ) {
					echo '<li>' . get_the_title() . '</li>';
				}
			} elseif ( is_page() && is_subpage() ) {
				$parent_id = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page = get_post( $parent_id );
					$breadcrumbs[] = '<li property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="' .
						get_permalink( $page->ID ) . '"><span property="name">' . get_the_title( $page->ID ) . '</span></a><meta property="position" content="' . $item_position . '"></li>';
					$parent_id = $page->post_parent;
					$item_position++;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );

				for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					echo $breadcrumbs[$i];
				}

				if ( $args['show_current'] ) {
					echo '<li>' . get_the_title() . '</li>';
				}
			} elseif ( is_tag() ) {
				echo '<li>' . __( "Posts tagged ", 'zn_framework' ) . '"' . single_tag_title( '', false ) . '"</li>';
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				echo '<li>' . __( "Articles posted by ", 'zn_framework' ) . ( isset( $userdata->display_name ) ? $userdata->display_name : '' ) . '</li>';
			} elseif ( is_404() ) {
				echo '<li>' . __( "Error 404 ", 'zn_framework' ) . '</li>';
			}
			if ( get_query_var( 'paged' ) ) {
				echo '<li>' . __( 'Page', 'zn_framework' ) . ' ' . get_query_var( 'paged' ) . '</li>';
			}
			echo '</ul>';
		}
	}
}

/*
 * Check if this is a subpage
 */
if ( ! function_exists( 'is_subpage' ) ) {
	/**
	 * Check if this is a subpage
	 *
	 * @return bool|int
	 */
	function is_subpage() {
		global $post;                              // load details about this page
		if ( is_page() && $post->post_parent ) {   // test to see if the page has a parent
			return $post->post_parent;             // return the ID of the parent post
		}
		return false;
	}
}


/*
 * Login Form - Stop redirecting if ajax is used
 */
if ( ! function_exists( 'zn_stop_redirecting' ) ) {
	/**
	 * Login Form - Stop redirecting if ajax is used
	 *
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 * @hooked to login_redirect
	 *
	 * @see functions.php
	 *
	 * @return mixed
	 */
	function zn_stop_redirecting( $redirect_to, $request, $user ) {
		if ( empty( $_POST['ajax_login'] ) ) {
			return $redirect_to;
		}
	}
}

/*
 * Login system
 */
if ( ! function_exists( 'zn_do_login' ) ) {
	/**
	 * Login system
	 *
	 * @hooked to wp_ajax_nopriv_zn_do_login
	 *
	 * @see functions.php
	 */
	function zn_do_login() {
		// @wpk: pre-validate request
		$rm = strtoupper( $_SERVER['REQUEST_METHOD'] );
		if (
			'POST' !== $rm
			|| ! isset( $_POST['zn_form_action'] )
			|| ! in_array( $_POST['zn_form_action'], array( 'login', 'register', 'reset_pass' ) )
		) {
			wp_send_json_error(array(
				'message' => __( 'Invalid request.', 'zn_framework' ),
			));
		}

		// Allows the user to specify an URL to where the logged in user will be transfered
		$redirect_url = apply_filters( 'kallyas_login_redirect_url', false );

		if ( 'login' == $_POST['zn_form_action'] ) {
			$user = wp_signon();

			if ( is_wp_error( $user ) ) {
				wp_send_json_error(array(
					'message' => $user->get_error_message(),
				));
			} else {
				wp_send_json_success(array(
					'redirect_url' => $redirect_url,
				));
			}
		} elseif ( 'register' == $_POST['zn_form_action'] ) {
			$zn_error_message = array();

			// Defaults
			$password =
			$username =
			$username =
			$email = '';

			if ( ! empty( $_POST['user_login'] ) ) {
				if ( username_exists( $_POST['user_login'] ) ) {
					$zn_error_message[] = __( 'The username already exists', 'zn_framework' );
				} else {
					$username = $_POST['user_login'];
				}
			} else {
				$zn_error_message[] = __( 'Please enter an username', 'zn_framework' );
			}

			if ( ! empty( $_POST['user_password'] ) ) {
				$password = $_POST['user_password'];
			} else {
				$zn_error_message[] = __( 'Please enter a password', 'zn_framework' );
			}

			if ( ( empty( $_POST['user_password'] ) && empty( $_POST['user_password2'] ) ) || $_POST['user_password'] != $_POST['user_password2'] ) {
				$zn_error_message[] = __( 'Passwords do not match', 'zn_framework' );
			}

			if ( ! empty( $_POST['user_email'] ) ) {
				if ( ! email_exists( $_POST['user_email'] ) ) {
					if ( ! filter_var( $_POST['user_email'], FILTER_VALIDATE_EMAIL ) ) {
						$zn_error_message[] = __( 'Please enter a valid EMAIL address', 'zn_framework' );
					} else {
						$email = $_POST['user_email'];
					}
				} else {
					$zn_error_message[] = __( 'This email address has already been used', 'zn_framework' );
				}
			} else {
				$zn_error_message[] = __( 'Please enter an email address', 'zn_framework' );
			}

			// Check to see if all GDPR requirements are passed
			$zn_error_message = apply_filters('kallyas_validate_registration_form', $zn_error_message );

			//#! Validate ReCaptcha if enabled
			//#! @since v4.11
			if ( znhgReCaptchaEnabled() ) {
				$result = znhgReCaptchaValidate();
				if ( ! empty($result)) {
					foreach ( $result as $err ) {
						$zn_error_message[] = $err;
					}
				}
			}

			if ( ! empty( $zn_error_message ) ) {
				wp_send_json_error(array(
					'message' => implode( '<br />', $zn_error_message ),
				));
			} else {
				$user_data = array(
					'ID' => '',
					'user_pass' => $password,
					'user_login' => $username,
					'display_name' => $username,
					'user_email' => $email,
					// Use default role or another role, e.g. 'editor'
					'role' => get_option( 'default_role' ),
				);

				$user_id = wp_insert_user( $user_data );

				if ( znfw_is_woocommerce_active() ) {
					do_action( 'woocommerce_created_customer', $user_id, $user_data, $password );
				} else {
					if ( ! function_exists( 'wp_new_user_notification' ) ) {
						include_once( trailingslashit( ABSPATH ) . 'wp-includes/pluggable.php' );
					}

					wp_new_user_notification( $user_id );
				}

				wp_send_json_success( array(
					'message' => __( 'Your account has been created. <a href="#login_panel" class="kl-login-box">You can now login</a>.', 'zn_framework' ),
				));
			}
			exit;
		} elseif ( 'reset_pass' == $_POST['zn_form_action'] ) {
			echo do_action( 'login_form', 'resetpass' );
		}
	}
}


/*
 * Frontend: Load theme's default stylesheets
 */
if ( ! function_exists( 'wpkLoadGlobalStylesheetsFrontend' ) ) {
	/**
	 * Frontend: Load theme's default stylesheets
	 *
	 * @hooked to wp_enqueue_scripts.
	 *
	 * @see functions.php
	 */
	function wpkLoadGlobalStylesheetsFrontend() {
		wp_enqueue_style( 'kallyas-styles', get_stylesheet_uri(), false, ZN_FW_VERSION );
		wp_enqueue_style( 'th-bootstrap-styles', THEME_BASE_URI . '/css/bootstrap.min.css', false, ZN_FW_VERSION );

		$suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || defined( 'ZN_FW_DEBUG' ) && ZN_FW_DEBUG == true ) ? '' : '.min';
		wp_enqueue_style( 'th-theme-template-styles', THEME_BASE_URI . '/css/template' . $suffix . '.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}

if ( ! function_exists( 'wpkLoadPrintRtl' ) ) {
	/**
	 * Frontend: Load theme's print and RTL stylesheets
	 *
	 * @hooked to wp_enqueue_scripts.
	 *
	 * @see functions.php
	 */
	function wpkLoadPrintRtl() {
		// PRINT STYLESHEET
		wp_enqueue_style( 'th-theme-print-stylesheet', THEME_BASE_URI . '/css/print.css', array( 'kallyas-styles' ), ZN_FW_VERSION, 'print' );
		// RTL STYLESHEET
		if ( is_rtl() ) {
			wp_enqueue_style( 'kallyas-rtl', THEME_BASE_URI . "/css/rtl.css", array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
	}
}

if ( ! function_exists( 'wpkLoadPluginsCss' ) ) {
	/**
	 * Frontend: Load theme's plugins overrides stylesheets
	 *
	 * @hooked to wp_enqueue_scripts.
	 *
	 * @see functions.php
	 */
	function wpkLoadPluginsCss() {
		// Woocommerce Own Stylesheet
		if ( class_exists('WooCommerce') ) {
			wp_enqueue_style( 'woocommerce-overrides', THEME_BASE_URI . '/css/plugins/kl-woocommerce.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
		// BuddyPress Own Stylesheet
		if ( class_exists('BuddyPress') ) {
			wp_enqueue_style( 'buddypress-overrides', THEME_BASE_URI . '/css/plugins/kl-buddypress.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
		// BBpress Own Stylesheet
		if ( class_exists('bbPress') ) {
			wp_enqueue_style( 'bbpress-overrides', THEME_BASE_URI . '/css/plugins/kl-bbpress.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
		// Event Calendar WD Own Stylesheet
		if ( class_exists('ECWD') ) {
			wp_enqueue_style( 'ecwd-overrides', THEME_BASE_URI . '/css/plugins/kl-calendar.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
		// Bookly plugin
		if ( function_exists('bookly_loader') ) {
			wp_enqueue_style( 'bookly', THEME_BASE_URI . '/css/plugins/kl-bookly.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		}
		// print_z(get_option( 'active_plugins', array() ));
	}
}

/*
 * Frontend: Only load scripts and stylesheets when needed
 */
if ( ! function_exists( 'wpkSmartScriptLoaderFrontend' ) ) {
	/**
	 * Frontend: Only load scripts and stylesheets when needed
	 *
	 * @hooked to wp_enqueue_scripts.
	 *
	 * @see functions.php
	 */
	function wpkSmartScriptLoaderFrontend() {
		$suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || defined( 'ZN_FW_DEBUG' ) && ZN_FW_DEBUG == true ) ? '' : '.min';

		/*
		 * Load Kallyas Vendors
		 */
		wp_enqueue_script( 'kallyas_vendors', THEME_BASE_URI . '/js/plugins.min.js', array('jquery'), ZN_FW_VERSION, true );

		/*
		 * Loaded only if Zion builder not loaded
		 */
		if ( ! function_exists('ZNB') ) {
			wp_enqueue_script( 'kallyas_modernizr', THEME_BASE_URI . '/js/vendors/_modernizr.js', array('jquery'), ZN_FW_VERSION, true );
			wp_enqueue_script( 'kallyas_bootstrap', THEME_BASE_URI . '/js/vendors/bootstrap.js', array('jquery'), ZN_FW_VERSION, true );
		}

		/*
		 * Adds JavaScript to pages with the comment form to support sites with
		 * threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/*
		 * Register Slick without loading
		 */
		wp_register_script( 'slick', THEME_BASE_URI . '/addons/slick/slick.min.js', array( 'jquery' ), ZN_FW_VERSION, true );


		/**
		 * Register recaptcha script
		 */
		$lang = zget_option( 'recaptcha_lang', 'general_options' );
		if ( ! empty($lang)) {
			$lang = '&hl=' . esc_attr( wp_strip_all_tags($lang, true));
		}
		wp_register_script( 'kl-recaptcha', 'https://www.google.com/recaptcha/api.js?onload=kallyasOnloadCallback' . $lang, array( 'jquery' ), true );
		wp_localize_script( 'kl-recaptcha', 'zn_contact_form', array(
			'captcha_not_filled' => esc_html__( 'Please complete the Captcha validation', 'zn_framework' ),
		));

		/*
		 * Load Scrollmagic library
		 * TODO: Remove it
		 */
		wp_enqueue_script( 'scrollmagic', THEME_BASE_URI . '/addons/scrollmagic/scrollmagic.js', array( 'jquery' ), ZN_FW_VERSION, true );

		/*
		 * Main Kallyas scripts
		 */
		wp_enqueue_script( 'zn-script', THEME_BASE_URI . '/js/znscript' . $suffix . '.js', array('jquery', 'kallyas_vendors'), ZN_FW_VERSION, true );
		wp_localize_script( 'zn-script', 'zn_do_login', array(
			'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
			'add_to_cart_text' => __( 'Item Added to cart!', 'zn_framework' ),
		) );

		$res_menu_trigger = zget_option( 'header_res_width', 'general_options', false, 992 );
		wp_localize_script( 'zn-script', 'ZnThemeAjax', array(
			'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
			'zn_back_text' => __( 'Back', 'zn_framework' ),
			'zn_color_theme' => zget_option( 'mobile_menu_theme', 'general_options', false, 'light' ),
			'res_menu_trigger' => ( int )$res_menu_trigger,
			'top_offset_tolerance' => zget_option( 'top_offset_tolerance', 'general_options', false, apply_filters('zn_top_offset_tolerance', false) ),
			'logout_url' => wp_logout_url( home_url() ),
		) );

		/**
		 * Smooth Scroll Script
		 */
		$sm_scroll = zget_option( 'smooth_scroll', 'general_options', false, 'no' );
		if ( 'no' != $sm_scroll ) {
			wp_enqueue_script( 'smooth_scroll', THEME_BASE_URI . '/addons/smooth_scroll/SmoothScroll.min.js', array( 'jquery' ), ZN_FW_VERSION, true );
			wp_localize_script( 'zn-script', 'ZnSmoothScroll', array(
				'type' => $sm_scroll,
				'touchpadSupport' => zget_option( 'smooth_scroll_osx', 'general_options', false, 'no' ),
			) );
		}
	}
}


/*
 * Check if the coming soon option enabled and display the custom page set in theme options
 */
add_action( 'template_redirect', 'zn_coming_soon_page', 1000 );
if ( ! function_exists( 'zn_coming_soon_page' ) ) {
	/**
	 * Check if the coming soon option enabled and display the custom page set in theme options
	 *
	 * @hooked to template_redirect
	 */
	function zn_coming_soon_page() {
		global $pagenow;
		global $post;

		if ( 'yes' == zget_option( 'cs_enable', 'coming_soon_options', false, 'no' ) && ! is_user_logged_in() && ! is_admin() && 'wp-login.php' != $pagenow ) {
			$csTemplate = zget_option( 'cs_page_template', 'coming_soon_options', false, '__zn_default__' );

			if ( isset( $post->ID ) && $post->ID <> $csTemplate ) {
				wp_redirect( get_permalink( $csTemplate ) );
				exit;
			}
		}
	}
}

/*
 * Check if the coming soon option enabled and display the default template
 */
add_action( 'wp_loaded', 'zn_coming_soon_page_default', 26 );
if ( ! function_exists( 'zn_coming_soon_page_default' ) ) {
	/**
	 * Check if the coming soon option enabled and display the default template
	 *
	 * @hooked to wp_loaded
	 */
	function zn_coming_soon_page_default() {
		global $pagenow;

		if ( 'yes' == zget_option( 'cs_enable', 'coming_soon_options', false, 'no' ) && ! is_user_logged_in() && ! is_admin() && 'wp-login.php' != $pagenow ) {
			$csTemplate = zget_option( 'cs_page_template', 'coming_soon_options', false, '__zn_default__' );
			if ( '__zn_default__' == $csTemplate ) {
				get_template_part( 'page', 'coming-soon' );
				exit;
			}
		}
	}
}

add_action( 'admin_bar_menu', 'adminBarComingSoonNotice', 9999 );
function adminBarComingSoonNotice( $wp_admin_bar ) {

		// Add Coming Soon Flag
	if ( is_user_logged_in() && current_user_can( 'administrator' ) && 'yes' == zget_option( 'cs_enable', 'coming_soon_options', false, 'no' )  ) {
		$args = array(
			'id'    => 'znkl_coming_soon_btn',
			'title' => __('Coming Soon Enabled', 'zn_framework'),
			'href'  => admin_url('admin.php?page=zn_tp_coming_soon_options'),
			'meta'  => array( 'class' => 'znkl_comingsoon_on', 'title' => __('Click to access options and disable it.', 'zn_framework') ),
		);
		$wp_admin_bar->add_node( $args );
	}
}



/*
 * Check for boxed layout or full and add specific CSS class by filter
 */
if ( ! function_exists( 'zn_body_class_names' ) ) {
	/**
	 * Check for boxed layout or full and add specific CSS class by filter
	 *
	 * @param $classes
	 * @hooked to body_class
	 *
	 * @see functions.php
	 *
	 * @return array
	 */
	function zn_body_class_names( $classes ) {
		// [wpk] Flags
		// @since 4.0.9
		// Simple flag to indicate whether or not the class has been added to body
		$boxed = false;
		// Simple flag so we don't have to check again below
		$except = false;

		$zn_home_boxed_layout = zn_get_layout_option( 'zn_home_boxed_layout', 'layout_options', false, 'def' );
		if ( ( 'yes' == zn_get_layout_option( 'zn_boxed_layout', 'layout_options', false, 'no' ) ) ||
			( is_front_page() && 'yes' == $zn_home_boxed_layout )
		) {
			$classes[] = 'boxed';
			$boxed = true;
		}
		if ( is_front_page() && 'no' == $zn_home_boxed_layout ) {
			$classes = array_diff( $classes, array( "boxed" ) );
			$boxed = false;
			$except = true;
		}

		//#! Add custom classes set in the Page Layouts
		$customClasses = zn_get_layout_option( 'header_sticky_custom_css_class', 'general_options', false, '' );
		if ( ! empty($customClasses) ) {
			$c = explode( ' ', $customClasses );
			$c = array_map( 'trim', $c );
			$c = array_map( 'esc_attr', $c );
			$classes = wp_parse_args( $classes, $c );
		}

		// [wpk] Check boxed layout option for current page
		// @since v4.0.9
		if ( ! $except ) {
			$isBoxedLayout = ( 'yes' == zn_get_layout_option( 'zn_boxed_layout', 'layout_options', false, 'no' ) );
			$pageBoxedLayout = get_post_meta( get_the_ID(), 'zn_page_override_boxed_layout', true );

			if ( 'def' == $pageBoxedLayout ) {
				if ( ! $isBoxedLayout && $boxed ) {
					$classes = array_diff( $classes, array( "boxed" ) );
				}
			} elseif ( 'yes' == $pageBoxedLayout ) {
				if ( ! $isBoxedLayout && ! $boxed ) {
					$classes[] = 'boxed';
				}
			} elseif ( 'no' == $pageBoxedLayout ) {
				if ( $isBoxedLayout && $boxed ) {
					$classes = array_diff( $classes, array( "boxed" ) );
				}
			}
		}


		if ( '1170' == zget_option( 'zn_width', 'layout_options' ) ) {
			$classes[] = 'res1170';
		} elseif ( '960' == zget_option( 'zn_width', 'layout_options' ) ) {
			$classes[] = 'res960';
		}

		$menu_follow = zn_get_layout_option( 'menu_follow', 'general_options', false, 'no' );
		if ( 'yes' == $menu_follow ) {
			$classes[] = 'kl-follow-menu';
		} else {
			if ( 'sticky' == $menu_follow ) {
				$classes[] = 'kl-sticky-header';
			}
		}

		$classes[] = 'kl-skin--' . zget_option( 'zn_main_style', 'color_options', false, 'light' );

		if ( class_exists('ECWD') ) {
			$classes[] = 'ecwd-kallyas';
		}

		return $classes;
	}
}


//<editor-fold desc=">>> AFTER_BODY ACTIONS">
/*
 * Add page loading
 */
if ( ! function_exists( 'zn_add_page_loading' ) ) {
	/**
	 * Add page loading
	 *
	 * @hooked to zn_after_body
	 *
	 * @see functions.php
	 */
	function zn_add_page_loading() {
		zn_page_loading();
	}
}

/*
 * Open Graph
 */
if ( ! function_exists( 'zn_add_open_graph' ) ) {
	/**
	 * Open Graph
	 *
	 * @hooked to zn_after_body
	 *
	 * @see functions.php
	 */
	function zn_add_open_graph() {
		?>
		<div id="fb-root"></div>
		<script>(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<?php
	}
}

//</editor-fold desc=">>> AFTER_BODY ACTIONS">


//<editor-fold desc=">>> ZN_HEAD_RIGHT_AREA">


/**
 * Recursive wp_parse_args WordPress function which handles multidimensional arrays
 *
 * @url http://mekshq.com/recursive-wp-parse-args-wordpress-function/
 *
 * @param array &$a Args
 * @param array $b  Defaults
 */
function zn_wp_parse_args( &$a, $b ) {
	$a = (array)$a;
	$b = (array)$b;
	$result = $b;
	foreach ( $a as $k => &$v ) {
		if ( is_array( $v ) && isset( $result[$k] ) ) {
			$result[$k] = zn_wp_parse_args( $v, $result[$k] );
		} else {
			$result[$k] = $v;
		}
	}
	return $result;
}


//</editor-fold desc=">>> ZN_HEAD_RIGHT_AREA">


/*
 * Retrieve the post attachment URL
 */
if ( ! function_exists( 'echo_first_image' ) ) {
	/**
	 * Retrieve the post attachment URL
	 *
	 * @return bool|string
	 */
	function echo_first_image() {
		global $post;

		$id = $post->ID;

		// Check if the post has any images
		$post = get_post( $id );

		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

		if ( isset( $matches[1][0] ) ) {
			if ( ! empty( $matches[1][0] ) && 'trans.gif' != basename( $matches[1][0] ) ) {
				return esc_url( $matches[1][0] );
			} elseif ( isset( $matches[1][1] ) && ! empty( $matches[1][1] ) ) {
				return esc_url( $matches[1][1] );
			}
		}

		return '';
	}
}

//<editor-fold desc=">>> WPK">


/*
 * Display the dismissible admin notice regarding the usage of the Cute3D Slider
 */
if ( ! function_exists( 'kallyasShowCuteSliderNotice' ) ) {
	/**
	 * Display the notification regarding the usage of the 3D Cute Slider
	 */
	function kallyasShowCuteSliderNotice() {
		do_action( 'wpk_dismissible_notice',
			'error',
			__( 'The plugin 3D Cute Slider is no longer supported by its author and it was removed from Envato Marketplace.
			We will try to continue offering support for it as much as we can but we strongly recommend you to replace its
			usage with an other slider or continue using it at your own risk.', 'zn_framework' ),
			'kallyas-dismiss-notice' );
	}
}

/*
 * Add option to select custom header in Edit Category page
 */
if ( ! function_exists( 'wpk_zn_edit_category_form' ) ) {
	/**
	 * Add option to select custom header in Edit Category page
	 *
	 * @param $term
	 * @hooked to sanitize_text_field( $_REQUEST['taxonomy'] ) . '_edit_form'
	 *
	 * @see functions.php
	 */
	function wpk_zn_edit_category_form( $term )
	{ ?>
		<h2><?php echo ZNHGTFW()->getThemeName() . ' ' . _e( 'Options ', 'zn_framework' ); ?></h2>
		<table class="form-table">
			<tbody>
			<tr class="form-field form-required term-name-wrap">
				<th scope="row">
					<label for="wpk_zn_select_custom_header"><?php _e( 'Select Sub-header', 'zn_framework' ); ?></label>
				</th>
				<td>
					<?php
					// GET ALL CUSTOM HEADERS
					$allHeaders = WpkZn::getThemeHeaders( true );
					if ( ! empty( $allHeaders ) ) {
						echo '<select name="wpk_zn_select_custom_header" id="wpk_zn_select_custom_header">';
						// Check option to display the previously checked option
						$optData = get_option( 'wpk_zn_select_custom_header_' . $term->term_id );
						$selectedSlug = 'zn_def_header_style'; // use default by default
						if ( ! empty( $optData ) ) {
							$selectedSlug = $optData;
						}
						foreach ( $allHeaders as $slug => $name ) {
							echo '<option value="' . $slug . '"';
							if ( $slug == $selectedSlug ) {
								echo ' selected="selected"';
							}
							echo '>' . $name . '</option>';
						}
						echo '</select>';
					}
					?>
					<p class="description"><?php _e( 'The custom sub-header you want to display for this category. To create a subheader please access <strong>Kallyas options > Unlimited Sub-headers</strong> .', 'zn_framework' ); ?></p>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
}

/*
 * Save the custom header set in the edit category screen
 */
if ( ! function_exists( 'wpk_zn_filterProductCatPost' ) ) {
	/**
	 * Save the custom header set in the edit category screen
	 */
	function wpk_zn_filterProductCatPost() {
		if ( 'POST' == strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			if ( isset( $_POST['action'] ) && ( 'editedtag' == $_POST['action'] ) ) {
				if ( isset( $_POST['taxonomy'] ) ) {
					if ( isset( $_POST['wpk_zn_select_custom_header'] ) && ! empty( $_POST['wpk_zn_select_custom_header'] ) ) {
						if ( isset( $_POST['tag_ID'] ) && ! empty( $_POST['tag_ID'] ) ) {
							$customHeaderSlug = sanitize_text_field( $_POST['wpk_zn_select_custom_header'] );
							if ( 'zn_def_header_style' == $_POST['wpk_zn_select_custom_header'] ) {
								delete_option( 'wpk_zn_select_custom_header_' . absint( $_POST['tag_ID'] ) );
							} else {
								update_option( 'wpk_zn_select_custom_header_' . absint( $_POST['tag_ID'] ), $customHeaderSlug );
							}
						}
					}
				}
			}
		}
	}
}


//</editor-fold desc=">>> WPK">

/* Rev slider : hide update notices **/
if ( function_exists( 'set_revslider_as_theme' ) ) {
	add_action( 'init', 'zn_set_revslider_as_theme' );
	function zn_set_revslider_as_theme() {
		set_revslider_as_theme();
	}
}

/* Change default pagination prev and next text with icons */
add_filter( 'zn_pagination', 'zn_change_pagination_texts' );
function zn_change_pagination_texts( $args ) {
	$args['list_class'] = 'kl-pagination';
	$args['previous_text'] = '<span class="zn_icon" data-zniconfam="glyphicons_halflingsregular" data-zn_icon="&#xe257;"></span>';
	$args['older_text'] = '<span class="zn_icon" data-zniconfam="glyphicons_halflingsregular" data-zn_icon="&#xe258;"></span>';
	return $args;
}

// Chrome v45 admin menu fix
function chromefix_inline_css() {
	if ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome' ) ) {
		wp_add_inline_style( 'wp-admin', '#adminmenu { transform: translateZ(0); }' );
	}
}

add_action( 'admin_enqueue_scripts', 'chromefix_inline_css' );


if ( ! function_exists( 'th_wpml_get_url_for_language' ) ) {
	/**
	 * Retrieve the appropriate url for the specified $language. Requires WPML installed and active.
	 *
	 * @param string $original_url
	 * @param mixed  $post
	 * @see:
	 *
	 * @return string
	 */
	function th_wpml_get_url_for_language( $original_url, $post ) {
		// Check if WPML plugin active and get the View Page url for the selected language
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( empty( $post->ID ) ) {
				return $original_url;
			}

			$post_type = get_post_type( $post->ID );
			$lang_post_id = apply_filters( 'wpml_object_id', $post->ID, $post_type );
			$url = '';
			if ( 0 != $lang_post_id ) {
				$url = get_permalink( $lang_post_id );
			} else {
				// No page found, it's most likely the homepage
				global $sitepress;
				if ( isset( $sitepress ) && is_object( $sitepress ) ) {
					$url = $sitepress->language_url( ICL_LANGUAGE_CODE );
				}
			}
			return $url;
		}
		return $original_url;
	}
}

/*
 * Resolved pagination of custom queries on homepage
 */
add_filter( 'redirect_canonical', 'pif_disable_redirect_canonical' );
function pif_disable_redirect_canonical( $redirect_url ) {
	if ( is_front_page() && get_query_var( 'page' ) ) {
		$redirect_url = false;
	}
	return $redirect_url;
}

add_filter('zn_default_link_target_type', 'zn_add_link_targets');
if ( ! function_exists('zn_add_link_targets')):
	function zn_add_link_targets($targets) {
		return array_merge($targets, array(
			'modal' 		=> __( "Modal Image", 'zn_framework' ),
			'modal_iframe' 	=> __( "Modal Iframe", 'zn_framework' ),
			'modal_inline' 	=> __( "Modal Inline content", 'zn_framework' ),
			'modal_inline_dyn' => __( "Modal Inline Dynamic (eg: pass Title to Form)", 'zn_framework' ),
			'smoothscroll' 	=> __( "Smooth Scroll to Anchor", 'zn_framework' ),
		));
	}
endif;


add_filter('zn_default_link_target_html', 'zn_get_target_html', 1, 2);
if ( ! function_exists('zn_get_target_html')):
	function zn_get_target_html($link_target, $target) {
		if ( 'modal_image' == $target || 'modal' == $target ) {
			$link_target = 'data-lightbox="image"';
		} else {
			if ( 'modal_iframe' == $target ) {
				$link_target = 'data-lightbox="iframe"';
			} else {
				if ( 'modal_inline' == $target ) {
					$link_target = 'data-lightbox="inline"';
				} else {
					if ( 'modal_inline_dyn' == $target ) {
						$link_target = 'data-lightbox="inline-dyn"';
					} else {
						if ( 'smoothscroll' == $target ) {
							$link_target = 'data-target="smoothscroll"';
						}
					}
				}
			}
		}

		return $link_target;
	}
endif;


/*
 * Function to modify the youtube links for modal iframes
 *
 * This is needed so we can use youtu.be short links and other params for video links
 *
 * @since  v4.15.2
 */
add_filter('zn_process_link_extraction', 'zn_modify_link_url_for_modals');
if ( ! function_exists( 'zn_modify_link_url_for_modals' ) ) {
	function zn_modify_link_url_for_modals( $linkArray ) {
		// Check if the link target is set to modal iframe
		if ( isset( $linkArray['target'] ) && 'modal_iframe' == $linkArray['target'] ) {
			$ytshorturl = 'youtu.be/';
			$ytlongurl = 'www.youtube.com/watch?v=';

			// Check to see if this is a video url
			if (
				isset( $linkArray['url'] ) &&
				(
					false !== strpos( $linkArray['url'], $ytshorturl ) ||
					false !== strpos( $linkArray['url'], $ytlongurl )
				)
			) {

				// Convert short link to full youtube link
				if (false !== strpos($linkArray['url'], $ytshorturl)) {
					$linkArray['url'] = str_replace( '?', '&', $linkArray['url'] );
					$linkArray['url'] = str_replace($ytshorturl, $ytlongurl, $linkArray['url']);
				}

				// Get all parameters from URL
				parse_str( parse_url( $linkArray['url'], PHP_URL_QUERY ), $url );
				if ( empty( $url ) ) {
					$url = array();
				}

				// Add our own autoplay since Magnific Popup adds it with "?autoplay=1"
				$url['autoplay'] = 1;

				// Change start time to embed start time
				if ( array_key_exists('t', $url) ) {
					$url['start'] = $url['t'];
					unset($url['t']);
				}

				$url['dummy'] = 1;

				// Replace first occurence of '&' so that magnific popup works with this link
				$from = '/' . preg_quote('&', '/') . '/';
				$url = preg_replace($from, '?', http_build_query($url), 1);
				$linkArray['url'] = 'www.youtube.com/watch?' . $url;
			}
		}

		return $linkArray;
	}
}




/*
 * Function to add a JS with the color palette, for colorpickers
 * @since  v4.0.9
 */
add_action( 'znfw_scripts', 'zn_add_color_palette_js' );

if ( ! function_exists( 'zn_add_color_palette_js' ) ) {
	function zn_add_color_palette_js() {
		$palettejs = '';
		$palette = zget_option( 'zn_add_colors', 'color_options', false, '' );
		if ( ! empty( $palette ) && is_array( $palette ) ) {

			// Get last
			$plt_arrkeys = array_keys( $palette );
			$last_key = end( $plt_arrkeys );
			// Start JS
			$palettejs .= '<script type="text/javascript">/* <![CDATA[ */';
			$palettejs .= 'var zn_color_palette = [';
			// Add some default colors
			$palettejs .= "'" . zget_option( 'zn_main_color', 'color_options', false, '#cd2122' ) . "',";
			$palettejs .= "'#FFF',";
			$palettejs .= "'#000',";
			foreach ( $palette as $key => $value ) {
				$palettejs .= "'" . $value['zn_color'] . "'";
				// separate with comma
				if ( $key != $last_key ) {
					$palettejs .= ',';
				}
			}
			$palettejs .= '];';
			$palettejs .= '/* ]]> */</script>';
		}
		echo $palettejs;
	}
}

add_action( 'wp_ajax_nopriv_zn_loadmore', 'zn_loadmore' );
add_action( 'wp_ajax_zn_loadmore', 'zn_loadmore' );
function zn_loadmore() {
	global $zn_config;

	// We need to know the sorting attributes
	$zn_config['ptf_sortby_type'] = $_POST['ptf_sortby_type'];
	$zn_config['ptf_sort_dir'] = $_POST['ptf_sort_dir'];

	$queryArgs = array(
		'post_type' => 'portfolio',
		'post_status' => 'publish',
		'paged' => $_POST['offset'],
		'posts_per_page' => $_POST['ppp'],
		'orderby' => $zn_config['ptf_sortby_type'],
		'order' => $zn_config['ptf_sort_dir'],
	);

	if ( ! empty( $_POST['categories'] ) ) {
		$queryArgs['tax_query'] = array(
			array(
				'taxonomy' => 'project_category',
				'field' => 'id',
				'terms' => explode( ',', $_POST['categories'] ),
			),
		);
	}

	$zn_config['ptf_show_title'] = $_POST['show_item_title'];
	$zn_config['ptf_show_desc'] = $_POST['show_item_desc'];
	$zn_config['zn_link_portfolio'] = $_POST['zn_link_portfolio'];

	ob_start();
	query_posts( $queryArgs );
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'inc/loop', 'portfolio_sortable_content' );
		}
	}

	global $wp_query;
	$postsHtml = ob_get_clean();
	$current_page = $wp_query->get( 'paged' );
	if ( ! $current_page ) {
		$current_page = 1;
	}
	wp_send_json(array(
		'postsHtml' => $postsHtml,
		'isLastPage' => ( $wp_query->max_num_pages == $current_page ),
		'currentPage' => $current_page,
		'max' => $wp_query->max_num_pages,
	));
}

/*
 * Prepare individual sides css for boxmodel fields
 */
if ( ! function_exists( 'zn_add_boxmodel' ) ) {
	function zn_add_boxmodel( $boxmodel_val = '', $type = 'position' ) {
		$boxmodel_css = $before = $after = '';

		if ( 'position' == $type ) {
			$before = '';
		} elseif ( 'border-width' == $type ) {
			$before = 'border-';
			$after = '-width';
		} else {
			$before = $type . '-';
		}

		if ( is_array( $boxmodel_val ) ) {
			foreach ( $boxmodel_val as $edge => $val ) {
				if ( '' != $val && 'linked' != $edge ) {
					if ( is_numeric( $val ) ) {
						$val = $val . 'px';
					}
					$boxmodel_css .= $before . $edge . $after . ':' . $val . ';';
				}
			}
		}
		return $boxmodel_css;
	}
}

/*
 * Wrap CSS into media query
 */
if ( ! function_exists( 'zn_wrap_mediaquery' ) ) {
	function zn_wrap_mediaquery( $breakpoint = 'lg', $selector = '' ) {
		$mq = array();
		$mq['start'] = '';
		$mq['end'] = '';

		if ( ! empty( $selector ) ) {
			$selector = $selector . '{';
		}

		$mq_lg = $selector;
		$mq_md = '@media screen and (min-width: 992px) and (max-width: 1199px){';
		$mq_sm = '@media screen and (min-width: 768px) and (max-width:991px){';
		$mq_xs = '@media screen and (max-width: 767px){';
		$mq_end = '}';

		if ( 'lg' == $breakpoint ) {
			$mq['start'] = $mq_lg;
			$mq['end'] = $mq_end;
		} elseif ( 'md' == $breakpoint ) {
			$mq['start'] = $mq_md . $selector;
			$mq['end'] = $mq_end . $mq_end;
		} elseif ( 'sm' == $breakpoint ) {
			$mq['start'] = $mq_sm . $selector;
			$mq['end'] = $mq_end . $mq_end;
		} elseif ( 'xs' == $breakpoint ) {
			$mq['start'] = $mq_xs . $selector;
			$mq['end'] = $mq_end . $mq_end;
		}

		return $mq;
	}
}

/*
 * Create CSS of Boxmodel option-types
 */
if ( ! function_exists( 'zn_push_boxmodel_styles' ) ) {
	function zn_push_boxmodel_styles( $args = array() ) {
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

		if ( empty( $args['selector'] ) ) {
			return;
		}

		$brp = array( 'lg', 'md', 'sm', 'xs' );

		foreach ( $brp as $k ) {
			if ( isset( $args[$k] ) && ! empty( $args[$k] ) ) {
				$brp_css = zn_add_boxmodel( $args[$k], $args['type'] );

				if ( ! empty( $brp_css ) ) {
					$mq = zn_wrap_mediaquery( $k, $args['selector'] );
					$css .= $mq['start'];
					if ( ! empty( $brp_css ) ) {
						$css .= $brp_css;
					}
					$css .= $mq['end'];
				}
			}
		}

		return $css;
	}
}


/*
 * Create CSS for typography option-types (with breakpoints)
 */
if ( ! function_exists( 'zn_typography_css' ) ) {
	function zn_typography_css( $args = array() ) {
		$css = '';
		$defaults = array(
			'selector' => '',
			'lg' => array(),
			'md' => array(),
			'sm' => array(),
			'xs' => array(),
		);
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['selector'] ) ) {
			return;
		}

		$brp = array( 'lg', 'md', 'sm', 'xs' );

		foreach ( $brp as $k ) {
			if ( is_array( $args[$k] ) & ! empty( $args[$k] ) ) {
				$brp_css = '';
				foreach ( $args[$k] as $key => $value ) {
					if ( '' != $value ) {
						if ( 'font-family' == $key ) {
							$brp_css .= $key . ':' . zn_convert_font( $value ) . ';';
						} else {
							$brp_css .= $key . ':' . $value . ';';
						}
					}
				}

				if ( ! empty( $brp_css ) ) {
					$mq = zn_wrap_mediaquery( $k, $args['selector'] );
					$css .= $mq['start'];
					if ( ! empty( $brp_css ) ) {
						$css .= $brp_css;
					}
					$css .= $mq['end'];
				}
			}
		}

		return $css;
	}
}

add_filter( 'zn_get_button_styles', 'zn_get_button_styles' );
if ( ! function_exists( 'zn_get_button_styles' ) ) {
	function zn_get_button_styles() {
		$path = THEME_BASE_URI . '/images/admin/button_icons';
		return array(

			array(
				'value' => 'btn-fullcolor',
				'name'  => __( 'Flat (main color)', 'zn_framework' ),
				'image' => $path . '/01.flatmaincolor.jpg',
			),
			array(
				'value' => 'btn-fullcolor btn-custom-color',
				'name'  => __( 'Flat (custom color)', 'zn_framework' ),
				'image' => $path . '/02.flatcustomcolor.jpg',
			),
			array(
				'value' => 'btn-fullwhite',
				'name'  => __( 'Flat (white)', 'zn_framework' ),
				'image' => $path . '/03.flatwhite.jpg',
			),
			array(
				'value' => 'btn-fullblack',
				'name'  => __( 'Flat (black)', 'zn_framework' ),
				'image' => $path . '/04.flatblack.jpg',
			),
			array(
				'value' => 'btn-lined',
				'name'  => __( 'Lined (light)', 'zn_framework' ),
				'image' => $path . '/05.linedlight.jpg',
			),
			array(
				'value' => 'btn-lined lined-dark',
				'name'  => __( 'Lined (dark)', 'zn_framework' ),
				'image' => $path . '/06.lineddark.jpg',
			),
			array(
				'value' => 'btn-lined lined-gray',
				'name'  => __( 'Lined (gray)', 'zn_framework' ),
				'image' => $path . '/07.linedgrey.jpg',
			),
			array(
				'value' => 'btn-lined lined-custom',
				'name'  => __( 'Lined (main color)', 'zn_framework' ),
				'image' => $path . '/08.linedmaincolor.jpg',
			),
			array(
				'value' => 'btn-lined btn-custom-color',
				'name'  => __( 'Lined (custom color)', 'zn_framework' ),
				'image' => $path . '/09.linedcustomcolor.jpg',
			),
			array(
				'value' => 'btn-lined lined-full-light',
				'name'  => __( 'Lined-Full (light)', 'zn_framework' ),
				'image' => $path . '/10.linedfulllight.jpg',
			),
			array(
				'value' => 'btn-lined lined-full-dark',
				'name'  => __( 'Lined-Full (dark)', 'zn_framework' ),
				'image' => $path . '/11.linedfulldark.jpg',
			),
			array(
				'value' => 'btn-lined btn-skewed',
				'name'  => __( 'Lined-Skewed (light)', 'zn_framework' ),
				'image' => $path . '/12.linedskewedlight.jpg',
			),
			array(
				'value' => 'btn-lined btn-skewed lined-dark',
				'name'  => __( 'Lined-Skewed (dark)', 'zn_framework' ),
				'image' => $path . '/13.linedskeweddark.jpg',
			),
			array(
				'value' => 'btn-lined btn-skewed lined-gray',
				'name'  => __( 'Lined-Skewed (gray)', 'zn_framework' ),
				'image' => $path . '/14.linedskewedgrey.jpg',
			),
			array(
				'value' => 'btn-fullcolor btn-skewed',
				'name'  => __( 'Flat-Skewed (main color)', 'zn_framework' ),
				'image' => $path . '/15.flatskewedmaincolor.jpg',
			),
			array(
				'value' => 'btn-fullcolor btn-skewed btn-custom-color',
				'name'  => __( 'Flat-Skewed (custom color)', 'zn_framework' ),
				'image' => $path . '/16.flatskewedcustomcolor.jpg',
			),
			array(
				'value' => 'btn-fullwhite btn-skewed',
				'name'  => __( 'Flat-Skewed (white)', 'zn_framework' ),
				'image' => $path . '/17.flatskewedwhite.jpg',
			),
			array(
				'value' => 'btn-fullblack btn-skewed',
				'name'  => __( 'Flat-Skewed (black)', 'zn_framework' ),
				'image' => $path . '/18.flatskewedblack.jpg',
			),
			array(
				'value' => 'btn-fullcolor btn-bordered',
				'name'  => __( 'Flat Bordered (main color)', 'zn_framework' ),
				'image' => $path . '/19.flatborderdmaincolor.jpg',
			),
			array(
				'value' => 'btn-fullcolor btn-bordered btn-custom-color',
				'name'  => __( 'Flat Bordered (custom color)', 'zn_framework' ),
				'image' => $path . '/20.flatborderdcustomcolor.jpg',
			),
			array(
				'value' => 'btn-default',
				'name'  => __( 'Bootstrap - Default', 'zn_framework' ),
				'image' => $path . '/21.boostrapdefault.jpg',
			),
			array(
				'value' => 'btn-primary',
				'name'  => __( 'Bootstrap - Primary', 'zn_framework' ),
				'image' => $path . '/22.boostrapprimary.jpg',
			),
			array(
				'value' => 'btn-success',
				'name'  => __( 'Bootstrap - Success', 'zn_framework' ),
				'image' => $path . '/23.boostrapinfo.jpg',
			),
			array(
				'value' => 'btn-info',
				'name'  => __( 'Bootstrap - Info', 'zn_framework' ),
				'image' => $path . '/24.boostrapsuccess.jpg',
			),
			array(
				'value' => 'btn-warning',
				'name'  => __( 'Bootstrap - Warning', 'zn_framework' ),
				'image' => $path . '/25.boostrapwarning.jpg',
			),
			array(
				'value' => 'btn-danger',
				'name'  => __( 'Bootstrap - Danger', 'zn_framework' ),
				'image' => $path . '/26.boostrapdanger.jpg',
			),
			array(
				'value' => 'btn-link',
				'name'  => __( 'Bootstrap - Link', 'zn_framework' ),
				'image' => $path . '/27.boostraplink.jpg',
			),
			array(
				'value' => 'btn-text',
				'name'  => __( 'Simple linked text', 'zn_framework' ),
				'image' => $path . '/28.simplelinktext.jpg',
			),
			array(
				'value' => 'btn-text btn-custom-color',
				'name'  => __( 'Simple linked text (Custom Color)', 'zn_framework' ),
				'image' => $path . '/29.simplelinktextcustom.jpg',
			),
			array(
				'value' => 'btn-underline btn-underline--thin',
				'name'  => __( 'Simple Underline Thin', 'zn_framework' ),
				'image' => $path . '/30.simpleunderlinethin.jpg',
			),
			array(
				'value' => 'btn-underline btn-underline--thick',
				'name'  => __( 'Simple Underline Thick', 'zn_framework' ),
				'image' => $path . '/31.simpleunderlinethick.jpg',
			),

		);
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
if ( ! function_exists( 'vt_resize' ) ) {
	/**
	 * @param null $attach_id
	 * @param null $img_url
	 * @param int  $width
	 * @param int  $height
	 * @param bool $crop
	 *
	 * @return array
	 */
	function vt_resize( $attach_id = null, $img_url = null, $width = 0, $height = 0, $crop = false ) {
		$return = array(
			'width' => $width,
			'height' => $height,
		);

		if ( $attach_id ) {
			$img_url = wp_get_attachment_url( $attach_id );
		}
		// Workaround for SVG & GIFs
		$ext = pathinfo($img_url, PATHINFO_EXTENSION);
		if ( 'svg' == $ext || 'gif' == $ext ) {
			$return['url'] = $img_url;
			return $return;
		}

		$image = mr_image_resize( $img_url, $width, $height, true, 'c', false );

		if ( is_array( $image ) ) {
			if ( ! empty( $image['url'] ) ) {
				$return['url'] = $image['url'];
			}
		} else {
			$return['url'] = 'image_not_specified' != $image && 'getimagesize_error_common' != $image && '' != $image ? $image : $img_url;
		}

		return $return;
	}
}


/*
 * Add font mime types
 */
add_filter( 'upload_mimes', 'zn_add_webfont_upload_mimes' );
function zn_add_webfont_upload_mimes( $existing_mimes ) {
	$existing_mimes['woff'] = 'application/font-woff';
	$existing_mimes['woff2'] = 'application/font-woff2';
	$existing_mimes['ttf'] = 'application/font-ttf';
	$existing_mimes['eot'] = 'application/vnd.ms-fontobject';
	$existing_mimes['svg'] = 'image/svg+xml';
	return $existing_mimes;
}

/*--------------------------------------------------------------------------------------------------
		Set-up post data
	--------------------------------------------------------------------------------------------------*/


if ( ! function_exists( 'zn_setup_post_data' ) ) {
	function zn_setup_post_data( $post_format, $post_content = 'content' ) {
		global $post;

		$opt_archive_content_type = zget_option( 'sb_archive_content_type', 'blog_options', false, 'full' );

		// CHECK TO SEE IF WE NEED TO WRAP THE ARTICLE
		$post_data['before'] = ''; // Before the opening article tag
		$post_data['after'] = ''; // After the closing article tag

		$post_data['before_head'] = '';
		$post_data['after_head'] = '';

		$post_data['before_content'] = ''; // After the opening article tag
		$post_data['after_content'] = ''; // Before the closing article tag

		// Posts defaults
		$post_data['media'] = get_post_media();
		$post_data['title'] = get_the_title();
		$post_data['content'] = ( ( is_archive() || is_home() ) && 'excerpt' == $opt_archive_content_type ) ? get_the_excerpt() : get_the_content();

		if ( 'video' == $post_format || 'audio' == $post_format ) {
			$post_data['content'] = get_the_content();
		}

		// Separate post content and media
		$post_data = apply_filters( 'post-format-' . $post_format, $post_data );

		if ( ! empty( $post_data['content'] ) && 'excerpt' === $post_content ) {
			$post_data['content'] = get_the_excerpt();
		}

		$post_data['content'] = ( ( is_archive() || is_home() ) && 'excerpt' == $opt_archive_content_type ) ? get_the_excerpt() : apply_filters( 'the_content', $post_data['content'] );

		return $post_data;
	}
}

if ( ! function_exists( 'get_post_media' ) ) {
	function get_post_media() {
		$image = '';
		if ( is_single() ) {
			if ( has_post_thumbnail() ) {
				$thumb = get_post_thumbnail_id();
				$f_image = wp_get_attachment_url( $thumb );
				$alt = get_post_meta( $thumb, '_wp_attachment_image_alt', true );
				$title = get_the_title( $thumb );
				if ( $f_image ) {
					if ( 'yes' == zget_option( 'sb_use_full_image', 'blog_options', false, 'no' ) ) {
						$featured_image = wp_get_attachment_image_src( $thumb, 'full' );
						if ( isset( $featured_image[0] ) && ! empty( $featured_image[0] ) ) {
							$image = '<a data-lightbox="image" href="' . $featured_image[0] .
								'" class="hoverBorder pull-left full-width kl-blog-post-img"><img src="' .
								$featured_image[0] . '" ' . ZngetImageSizesFromUrl( $featured_image[0], true ) . ' alt="' . ZngetImageAltFromUrl( $featured_image[0] ) .
								'" title="' . ZngetImageTitleFromUrl( $featured_image[0] ) . '"/></a>';
						}
					} else {
						$feature_image = wp_get_attachment_url( $thumb );
						$image = vt_resize( '', $f_image, 420, 280, true );
						$image = '<a data-lightbox="image" href="' . $feature_image .
							'" class="hoverBorder pull-left kl-blog-post-img kl-blog-post--default-view" ><img src="' .
							$image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $alt . '" title="' . $title . '"/></a>';
					}
				}
			}
		} else {
			$usePostFirstImage = ( 'yes' == zget_option( 'zn_use_first_image', 'blog_options', false, 'yes' ) );
			$th_alt = '';
			$th_title = '';
			$hasFirstAttachedImage = false;
			if ( has_post_thumbnail() && ! post_password_required() ) {
				$thumb = get_post_thumbnail_id( get_the_id() );
				$f_image = wp_get_attachment_url( $thumb );
				$th_alt = get_post_meta( $thumb, '_wp_attachment_image_alt', true );
				$th_title = get_the_title( $thumb );
			} elseif ( $usePostFirstImage && ! post_password_required() ) {
				$f_image = echo_first_image();
				$th_alt = ZngetImageAltFromUrl( $f_image );
				$th_title = ZngetImageTitleFromUrl( $f_image );
				$hasFirstAttachedImage = true;
			}
			if ( ! empty( $f_image ) ) {
				$featPostClass = is_sticky( get_the_id() ) ? 'featured-post kl-blog--featured-post' : '';
				$sb_archive_use_full_image = zget_option( 'sb_archive_use_full_image', 'blog_options', false, 'no' );
				// Image Ratio
				$ratio = 1.77; // 16:9
				// $ratio = 1.33; // 4:3
				if ( ! empty( $featPostClass ) ) {
					$resized_image = vt_resize( '', $f_image, 1140, 480, true );
					$image = '<div class="zn_full_image kl-blog-full-image">';
					if ( isset( $resized_image['url'] ) && ! empty( $resized_image['url'] ) ) {
						$image .= '<img class="zn_post_thumbnail kl-blog-post-thumbnail" src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" alt="' . $th_alt . '" title="' . $th_title . '"/>';
					}
					$image .= '</div>';
				} elseif ( 'yes' == $sb_archive_use_full_image ) {
					if ( $hasFirstAttachedImage ) {
						$image_sizes = ZngetImageSizesFromUrl( $f_image, true );
						$image = '<div class="zn_full_image kl-blog-full-image"><a href="' . get_permalink() . '" class="kl-blog-full-image-link hoverBorder"><img class="kl-blog-full-image-img" src="' . $f_image . '" ' . $image_sizes . ' alt="' . $th_alt . '" title="' . $th_title . '" /></a></div>';
					} else {
						$image = '<div class="zn_full_image kl-blog-full-image"><a href="' . get_permalink() . '" class="kl-blog-full-image-link hoverBorder">' . get_the_post_thumbnail( get_the_id(), 'full-width-image', array( 'class' => 'kl-blog-full-image-img' ) ) . '</a></div>';
					}
				} else {
					$width = zget_option( 'sb_archive_def_cwidth', 'blog_options', false, '460' );
					$height = ceil( $width / $ratio );
					$resized_image = vt_resize( '', $f_image, $width, $height, true );
					$image = '<div class="zn_post_image kl-blog-post-image">';
					$image .= '<a href="' . get_permalink() . '" class="kl-blog-post-image-link hoverBorder pull-left">';
					if ( isset( $resized_image['url'] ) && ! empty( $resized_image['url'] ) ) {
						$image .= '<img class="zn_post_thumbnail kl-blog-post-thumbnail" src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" alt="' . $th_alt . '" title="' . $th_title . '" />';
					}
					$image .= '</a>';
					$image .= '</div>';
				}
			}
		}

		return $image;
	}
}

add_filter( 'post-format-standard', 'zn_post_standard', 10, 1 );
add_filter( 'post-format-video', 'zn_post_video' );
add_filter( 'post-format-audio', 'zn_post_audio' );
add_filter( 'post-format-quote', 'zn_post_quote' );
add_filter( 'post-format-link', 'zn_post_link' );
add_filter( 'post-format-gallery', 'zn_post_gallery', 10, 2 );

// STANDARD POST
if ( ! function_exists( 'zn_post_standard' ) ) {
	function zn_post_standard( $current_post ) {
		$current_post['title'] = zn_wrap_titles( $current_post['title'] );
		return $current_post;
	}
}


// VIDEO POST
if ( ! function_exists( 'zn_post_video' ) ) {
	function zn_post_video( $current_post ) {
		$current_post['content'] = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", $current_post['content'] );
		$current_post['title'] = zn_wrap_titles( $current_post['title'] );

		preg_match( "!\[embed.+?\]|\[video.+?video\]!", $current_post['content'], $match_video );

		if ( ! empty( $match_video ) ) {
			global $wp_embed;
			$video = $match_video[0];
			$current_post['before_head'] = '<div class="zn_iframe_wrap zn_post_media_container" ' . WpkPageHelper::zn_schema_markup( 'video' ) . '>' . do_shortcode( $wp_embed->run_shortcode( $video ) ) . '</div>';
			$current_post['media'] = '';
			$current_post['content'] = str_replace( $match_video[0], "", $current_post['content'] );
		}

		return $current_post;
	}
}


if ( ! function_exists( 'zn_post_audio' ) ) {
	function zn_post_audio( $current_post ) {
		$current_post['title'] = zn_wrap_titles( $current_post['title'] );
		//preg_match("!\[audio.+?\]\[\/audio\]!", $current_post['content'], $match_audio );
		preg_match( "!\[embed.+?\]|\[audio.+?audio\]!", $current_post['content'], $match_audio );
		if ( ! empty( $match_audio ) ) {
			global $wp_embed;
			$current_post['before_head'] = '<div class="zn_iframe_wrap zn_post_media_container" ' . WpkPageHelper::zn_schema_markup( 'audio' ) . '>' . do_shortcode( $wp_embed->run_shortcode( $match_audio[0] ) ) . '</div>';
			$current_post['media'] = '';
			$current_post['content'] = str_replace( $match_audio[0], "", $current_post['content'] );
		} else {
			preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $current_post['content'], $matches_iframe );

			if ( ! empty( $matches_iframe[0] ) ) {
				$current_post['before_head'] = '<div class="zn_iframe_wrap zn_post_media_container">' . $matches_iframe[0] . '</div>';
				$current_post['media'] = '';
				$current_post['content'] = str_replace( $matches_iframe[0], "", $current_post['content'] );
			}
		}

		return $current_post;
	}
}

// QUOTE POST
if ( ! function_exists( 'zn_post_quote' ) ) {
	function zn_post_quote( $current_post ) {
		$old_title = $current_post['title'];
		$current_post['title'] = '<div class="kl-quote-post"><blockquote class="kl-quote-post-blockquote"><p>' . $current_post['content'] . '</p><h5 class="kl-quote-post-title">' . $current_post['title'] . '</h5></blockquote></div>';
		$current_post['content'] = '';
		$current_post['media'] = '';

		return $current_post;
	}
}

// LINK POST
if ( ! function_exists( 'zn_post_link' ) ) {
	function zn_post_link( $current_post ) {
		$post_link = ( $has_url = get_url_in_content( $current_post['content'] ) ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
		$current_post['title'] = '<div class="kl-link-post"> <span class="kl-link-post-icon text-custom glyphicon glyphicon-link"></span> <a href="' . esc_url( $post_link ) . '" class="kl-link-post-url h3-typography"> ' . $current_post['title'] . ' </a> </div>';
		$current_post['content'] = '';
		$current_post['media'] = '';

		return $current_post;
	}
}

// GALLERY POST
if ( ! function_exists( 'zn_post_gallery' ) ) {
	function zn_post_gallery( $current_post ) {
		preg_match( "!\[(?:zn_)?gallery.+?\]!", $current_post['content'], $match_gallery );

		$current_post['title'] = zn_wrap_titles( $current_post['title'] );

		if ( ! empty( $match_gallery ) ) {
			$gallery = $match_gallery[0];

			if ( false === strpos( $gallery, 'zn_' ) ) {
				$gallery = str_replace( "gallery", 'zn_gallery', $gallery );
			}
			// if(strpos($gallery, 'style') === false) $gallery = str_replace("]", " size=\"$size\"]", $gallery);

			$current_post['before_head'] = do_shortcode( $gallery );
			$current_post['media'] = '';
			$current_post['content'] = str_replace( $match_gallery[0], "", $current_post['content'] );
		}

		return $current_post;
	}
}

/*--------------------------------------------------------------------------------------------------
	Gallery shortcode
--------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'zn_gallery' ) ) {
	function zn_gallery( $atts ) {
		extract( shortcode_atts( array(
			'order' => 'ASC',
			'ids' => '',
			'size' => 'col-sm-9',
			'style' => 'thumbnails',
			'columns' => 3,
		), $atts ) );

		$output = '';

		$attachments = get_posts( array(
			'include' => $ids,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $order,
			'orderby' => 'post__in',
		)
		);

		if ( ! empty( $attachments ) && is_array( $attachments ) ) {
			$slick_attributes = array(
				"infinite" => true,
				"slidesToShow" => 1,
				"slidesToScroll" => 1,
				"autoplay" => true,
				"autoplaySpeed" => 9000,
				"easing" => 'easeOutExpo',
				"fade" => true,
				"arrows" => true,
				"appendArrows" => '.znPostGallery-navigationPagination',
				"dots" => true,
				"appendDots" => '.znPostGallery-pagination',
			);

			$output .= '<div class="znPostGallery slick--showOnMouseover u-mb-30 ">';
			$output .= '<ul class="js-slick" data-slick=\'' . json_encode($slick_attributes) . '\'>';
			foreach ( $attachments as $attachment ) {
				$imgAttr = array( 'class' => "img-responsive" );
				$img = zn_get_image( $attachment->ID, '1170', '470', $imgAttr, true );

				$output .= '<li class="u-slick-show1stOnly">';
				$output .= $img;
				$output .= '</li>';
			}

			$output .= '	</ul>';

			$output .= '<div class="znPostGallery-navigationPagination znSlickNav znSlickNav--light">';
			$output .= '<div class="znPostGallery-pagination"></div>';
			$output .= '</div>';

			$output .= '	</div>';
		}

		return $output;
	}
}
add_shortcode( 'zn_gallery', 'zn_gallery' );

/*--------------------------------------------------------------------------------------------------
	Wrap post titles based on page
--------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'zn_wrap_titles' ) ) {
	function zn_wrap_titles( $title, $link = true ) {
		$title_tag = apply_filters( 'zn_blog_archive_titletag', 'h3' );

		if ( $link ) {
			$title = is_single() ? '<h1 class="page-title kl-blog-post-title entry-title" ' . WpkPageHelper::zn_schema_markup( 'title' ) . '>' . $title . '</h1>' : '<' . $title_tag . ' class="itemTitle kl-blog-item-title" ' . WpkPageHelper::zn_schema_markup( 'title' ) . '><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $title . '</a></' . $title_tag . '>';
		} else {
			$title = is_single() ? '<h1 class="page-title kl-blog-post-title entry-title" ' . WpkPageHelper::zn_schema_markup( 'title' ) . '>' . $title . '</h1>' : '<' . $title_tag . ' class="article_title entry-title">' . $title . '</' . $title_tag . '>';
		}

		return $title;
	}
}

// Enable font size & font family selects in the editor
if ( ! function_exists( 'zn_mce_buttons' ) ) {
	function zn_mce_buttons( $buttons ) {
		array_push( $buttons, 'fontselect' ); // Add Font Select
		array_push( $buttons, 'fontsizeselect' ); // Add Font Size Select
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'zn_mce_buttons' );

// Customize mce editor font sizes
if ( ! function_exists( 'zn_mce_text_sizes' ) ) {
	function zn_mce_text_sizes( $initArray ) {
		$initArray['fontsize_formats'] = "10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 48px";
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'zn_mce_text_sizes' );

if ( ! function_exists( 'zn_convert_font' ) ) {
	function zn_convert_font( $fontfamily ) {
		$fonts = array(
			'arial' => 'Arial, sans-serif',
			'verdana' => 'Verdana, Geneva, sans-serif',
			'trebuchet' => '"Trebuchet MS", Helvetica, sans-serif',
			'georgia' => 'Georgia, serif',
			'times' => '"Times New Roman", Times, serif',
			'tahoma' => 'Tahoma, Geneva, sans-serif',
			'palatino' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'helvetica' => 'Helvetica, Arial, sans-serif',
		);

		if ( array_key_exists( $fontfamily, $fonts ) ) {
			$fontfamily = $fonts[$fontfamily];
		} else {
			// Google Font
			$fontfamily = '"' . $fontfamily . '", Helvetica, Arial, sans-serif';
		}

		return $fontfamily;
	}
}


add_action( 'wp_head', 'zn_add_meta_color' );
if ( ! function_exists( 'zn_add_meta_color' ) ) {
	function zn_add_meta_color() {
		?>
		<meta name="theme-color"
			  content="<?php echo zget_option( 'zn_main_color', 'color_options', false, '#cd2122' ); ?>">
		<?php
	}
}

add_action( 'wp_head', 'zn_add_meta_viewport' );
if ( ! function_exists( 'zn_add_meta_viewport' ) ) {
	function zn_add_meta_viewport() {
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<?php
	}
}

if ( ! function_exists( 'zn_convert_bgcolor_overlay' ) ) {
	/**
	 * Function to convert fields stored with color and opacity, but separately, to convert into alpha supporting colorpicker and maintain backwards compatibility
	 *
	 * @param string $color   Color stored
	 * @param string $alpha   Opacity stored
	 * @param mixed  $options
	 * @param mixed  $default
	 *
	 * @return strong color string converted to rgba
	 */
	function zn_convert_bgcolor_overlay( $options = array(), $color, $alpha, $default = '' ) {
		$std_bgcolor_with_opacity = $default;

		if ( ! empty( $options ) ) {
			if ( isset( $color ) && ! empty( $color ) ) {
				if ( array_key_exists( $color, $options ) ) {
					$std_bgcolor_with_opacity = $options[$color];
					if ( isset( $alpha ) && ! empty( $alpha ) ) {
						if ( array_key_exists( $alpha, $options ) ) {
							$std_bgcolor_with_opacity = zn_hex2rgba_str( $std_bgcolor_with_opacity, $options[$alpha] );
						}
					}
				}
			}
		}

		return $std_bgcolor_with_opacity;
	}
}

if ( ! function_exists( 'zn_smart_slider_css' ) ) {
	/**
	 * Function to generate custom CSS based on breakpoints
	 *
	 * @param mixed $opt
	 * @param mixed $selector
	 * @param mixed $def_property
	 * @param mixed $def_unit
	 */
	function zn_smart_slider_css( $opt, $selector, $def_property = 'height', $def_unit = 'px' ) {
		$css = '';

		if ( is_array( $opt ) && ! empty( $opt ) ) {
			$breakp = isset( $opt['breakpoints'] ) ? $opt['breakpoints'] : '';
			$prop = isset( $opt['properties'] ) ? $opt['properties'] : $def_property;

			// Default Unit
			$unit_lg = isset( $opt['unit_lg'] ) ? $opt['unit_lg'] : $def_unit;

			$css .= $selector . ' {' . $prop . ':' . $opt['lg'] . $unit_lg . ';}';

			if ( ! empty( $breakp ) ) {
				if ( isset( $opt['md'] ) && ! empty( $opt['md'] ) ) {
					$unit_md = isset($opt['unit_md']) ? $opt['unit_md'] : $def_unit;
					$md_val = $opt['md'] . $unit_md;
					if ( 'auto' == $opt['md'] ) {
						$md_val = $opt['md'];
					}
					$css .= '@media (min-width:992px) and (max-width:1199px) {' . $selector . ' {' . $prop . ':' . $md_val . ';} }';
				}
				if ( isset( $opt['sm'] ) && ! empty( $opt['sm'] ) ) {
					$unit_sm = isset($opt['unit_sm']) ? $opt['unit_sm'] : $def_unit;
					$sm_val = $opt['sm'] . $unit_sm;
					if ( 'auto' == $opt['sm'] ) {
						$sm_val = $opt['sm'];
					}
					$css .= '@media (min-width:768px) and (max-width:991px) {' . $selector . ' {' . $prop . ':' . $sm_val . ';} }';
				}
				if ( isset( $opt['xs'] ) && ! empty( $opt['xs'] ) ) {
					$unit_xs = isset($opt['unit_xs']) ? $opt['unit_xs'] : $def_unit;
					$xs_val = $opt['xs'] . $unit_xs;
					if ( 'auto' == $opt['xs'] ) {
						$xs_val = $opt['xs'];
					}
					$css .= '@media (max-width:767px) {' . $selector . ' {' . $prop . ':' . $xs_val . ';} }';
				}
			}
		} else {
			if ( ! empty( $opt ) ) {
				$css .= $selector . ' {' . $def_property . ':' . $opt . $def_unit . ';}';
			}
		}

		return $css;
	}
}


/*
 *	Sets the theme version to 3.6.10 if this is an old installation
 *	The priority should be bellow 5 - at this point, the install script check for the version
 */
add_action( 'admin_init', 'zn_check_old_kallyas', 2 );
function zn_check_old_kallyas() {

	// Get the old field for kallyas options
	$saved_options = get_option( 'zn_kallyas_options' );
	if ( ! empty( $saved_options ) ) {
		$current_theme_version	= get_option( 'zn_kallyas_version' );
		$saved_options = get_option( 'zn_kallyas_optionsv4' );

		// This is possible to be an old installation of kallyas
		if ( empty( $current_theme_version ) && empty( $saved_options ) ) {
			// Update the theme versions
			update_option( 'kallyas_version', '3.6.10', false );
			update_option( 'zn_kallyas_optionsv4', array('dummy_array'), false );
		}
	}
}

/**
 * Function to get the old object parallax distance in various elements
 *
 * @param string $val The old value
 *
 * @return string value converted to new one
 */
function zn_obj_parallax_distance_std_legacy($val) {
	if ( 1 != $val ) {
		$val = ceil($val / 100);
	}
	return $val;
}


function zn_margin_padding_options( $uid = '', $args = array() ) {
	$def = array(
		'responsive' => true,
		'margin' => true,
		'padding' => true,
		'margin_selector' => '.' . $uid,
		'padding_selector' => '.' . $uid,
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

	if ( $args['responsive'] ) {
		$options[] = array(
			"name"        => __( "Edit element padding & margins for each device breakpoint. ", 'zn_framework' ),
			"description" => __( "This will enable you to have more control over the padding of the element on each device. Click to see <a href='http://hogash.d.pr/1f0nW' target='_blank'>how box-model works</a>.", 'zn_framework' ),
			"id"          => "spacing_breakpoints",
			"std"         => "lg",
			"tabs"        => true,
			"type"        => "zn_radio",
			"options"     => array(
				"lg"        => __( "LARGE", 'zn_framework' ),
				"md"        => __( "MEDIUM", 'zn_framework' ),
				"sm"        => __( "SMALL", 'zn_framework' ),
				"xs"        => __( "EXTRA SMALL", 'zn_framework' ),
			),
			"class"       => "zn_full zn_breakpoints",
		);
	}

	if ( $args['margin'] ) {
		// MARGINS
		$options[] = array(
			'id'          => 'margin_lg',
			'name'        => __('Margin (Large Breakpoints)', 'zn_framework'),
			'description' => __('Select the margin (in percent % or px) for this element. Accepts negative margin.', 'zn_framework'),
			'type'        => 'boxmodel',
			'std'	  	=> $args['margin_lg_std'],
			'placeholder' => '0px',
			"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('lg') ),
			'live' => array(
				'type'		=> 'boxmodel',
				'css_class' => $args['margin_selector'],
				'css_rule'	=> 'margin',
			),
		);
		if ( $args['responsive'] ) {
			$options[] = array(
				'id'          => 'margin_md',
				'name'        => __('Margin (Medium Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_md_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('md') ),
			);
			$options[] = array(
				'id'          => 'margin_sm',
				'name'        => __('Margin (Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_sm_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('sm') ),
			);
			$options[] = array(
				'id'          => 'margin_xs',
				'name'        => __('Margin (Extra Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the margin (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				'std'	  => 	$args['margin_xs_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('xs') ),
			);
		}
	}

	if ( $args['padding'] ) {
		// PADDINGS
		$options[] = array(
			'id'          => 'padding_lg',
			'name'        => __('Padding (Large Breakpoints)', 'zn_framework'),
			'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
			'type'        => 'boxmodel',
			"allow-negative" => false,
			'std'	  => $args['padding_lg_std'],
			'placeholder' => '0px',
			"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('lg') ),
			'live' => array(
				'type'		=> 'boxmodel',
				'css_class' => $args['padding_selector'],
				'css_rule'	=> 'padding',
			),
		);
		if ( $args['responsive'] ) {
			$options[] = array(
				'id'          => 'padding_md',
				'name'        => __('Padding (Medium Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('md') ),
			);
			$options[] = array(
				'id'          => 'padding_sm',
				'name'        => __('Padding (Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('sm') ),
			);
			$options[] = array(
				'id'          => 'padding_xs',
				'name'        => __('Padding (Extra Small Breakpoints)', 'zn_framework'),
				'description' => __('Select the padding (in percent % or px) for this element.', 'zn_framework'),
				'type'        => 'boxmodel',
				"allow-negative" => false,
				'std'	  => 	$args['padding_lg_std'],
				'placeholder'        => '0px',
				"dependency"  => array( 'element' => 'spacing_breakpoints', 'value'=> array('xs') ),
			);
		}
	}

	return $options;
}


if ( ! function_exists('zn_social_share_icons') ) {
	function zn_social_share_icons( $args = array() ) {
		$defaults = array(
			'share_title' => __( 'SHARE:', 'zn_framework'),
			'share_text' => sprintf( __( "Check out - %s", 'zn_framework' ), get_the_title() ),
			'share_url' => get_permalink(),
			'share_media' => '',
			'mail_body' => sprintf(
								__( "You can see it live here %s. %s Made by %s %s .", 'zn_framework' ),
								get_permalink() . '?utm_source=sharemail',
								"\n\n",
								get_bloginfo(),
								get_site_url()
							),
			'share_on_text' => __( "SHARE ON ", 'zn_framework' ),
			'echo' => false,
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters('zn_filter_social_share_args', $args);

		$icons = apply_filters('zn_filter_social_share_icons', array(
			'twitter' => array(
				'url'    => 'https://twitter.com/intent/tweet',
				'icon'   => array('family' => 'kl-social-icons', 'unicode' => 'ue82f'),
				'params' => array(
					'url'        => urlencode( $args['share_url'] . '?utm_source=sharetw' ),
					'text'       => $args['share_text'],
				),
				'popup' => true,
			),
			'facebook' => array(
				'url'    => 'https://www.facebook.com/sharer/sharer.php',
				'icon'   => array('family' => 'kl-social-icons', 'unicode' => 'ue83f'),
				'params' => array(
					'display'    => 'popup',
					'u'          => urlencode( $args['share_url'] . '?utm_source=sharefb' ),
				),
				'popup' => true,
			),
			'gplus' => array(
				'url'    => 'https://plus.google.com/share',
				'icon'   => array('family' => 'kl-social-icons', 'unicode' => 'ue808'),
				'params' => array(
					'url'        => urlencode( $args['share_url'] . '?utm_source=sharegp' ),
				),
				'popup' => true,
			),
			'pinterest' => array(
				'url'    => 'http://pinterest.com/pin/create/button',
				'icon'   => array('family' => 'kl-social-icons', 'unicode' => 'ue80e'),
				'params' => array(
					'url'         => urlencode( $args['share_url'] . '?utm_source=sharepi' ),
					'description' => $args['share_text'],
					'media'       => urlencode( $args['share_media'] ),
				),
				'popup' => true,
			),
			'mail' => array(
				'url'    => 'mailto:',
				'icon'   => array('family' => 'kl-social-icons', 'unicode' => 'ue836'),
				'params' => array(
					'subject' => $args['share_text'],
					'body'    => $args['mail_body'],
				),
				'popup' => false,
			),
		) );

		$html = '<div class="zn-shareIcons" data-share-title="' . esc_attr( $args['share_title'] ) . '">';

		foreach ($icons as $key => $icon) {
			$params = array_filter($icon['params']);
			$paramsJoined = array();
			foreach ($params as $param => $value) {
				$paramsJoined[] = "$param=$value";
			}
			$final_url = $icon['url'] . '?' . implode('&', $paramsJoined);

			if ( $icon['popup'] ) {
				$link = 'href="#" onclick="javascript:window.open(\'' . $final_url . '\',\'SHARE\',\'width=600,height=400\'); return false;"';
			} else {
				$link = 'href="' . $final_url . '"';
			}

			$html .= '<a ' . $link . ' title="' . $args['share_on_text'] . strtoupper($key) . '" class="zn-shareIcons-item zn-shareIcons-item--' . $key . '">';
			$html .= '<span ' . zn_generate_icon( $icon['icon'] ) . '></span>';
			$html .= '</a>';
		}
		$html .= '</div>';

		if ($args['echo']) {
			echo $html;
		} else {
			return $html;
		}
	}
}


/*
 * Remove Zion Builder's built in Bootstrap CSS
 */
add_action( 'wp_enqueue_scripts', 'hg_remove_hgfw_bootstrap_css', 99 );
function hg_remove_hgfw_bootstrap_css() {
	wp_dequeue_style('bootstrap-styles');
}

/**
 * Check to see whether or not ReCaptcha is enabled on General options > ReCaptcha options
 *
 * @return bool
 */
function znhgReCaptchaEnabled() {
	return ( 'yes' == zget_option( 'recaptcha_register', 'general_options', false, 'no' ) );
}

/**
 * Retrieve the style to use when displaying ReCaptcha
 *
 * @return string
 */
function znhgReCaptchaStyle() {
	return zget_option( 'rec_theme', 'general_options', false, 'light' );
}

/**
 * Retrieve the Site Key to use for ReCaptcha
 *
 * @return string
 */
function znhgReCaptchaSiteKey() {
	return zget_option( 'rec_pub_key', 'general_options' );
}

/**
 * Retrieve the Secret for ReCaptcha
 *
 * @return string
 */
function znhgReCaptchaSecret() {
	return zget_option( 'rec_priv_key', 'general_options' );
}

/**
 * validate the ReCaptcha on the registration form
 *
 * @return array
 */
function znhgReCaptchaValidate() {
	$captcha_val = $_POST['g-recaptcha-response'];
	$pvKey = znhgReCaptchaSecret();
	$response = wp_remote_request( "https://www.google.com/recaptcha/api/siteverify?secret=" . trim( $pvKey ) . "&response=" . trim( $captcha_val ) );
	$errors = array();

	if ( is_wp_error($response) ) {
		error_log('[ZNHG] Register Form ReCaptcha error: ' . $response->get_error_message() );
		$errors[] = esc_html__( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
	} elseif ( ! isset($response['body']) || empty( $response['body'] ) ) {
		$errors[] = esc_html__( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
	} else {
		$response = json_decode( $response['body'], true );
	}

	if ( ! is_array($response) || ! isset($response["success"]) ) {
		$errors[] = esc_html__( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
		$response = array(
			'success' => false,
		);
	}

	if ( true !== $response["success"] ) {
		if ( ! empty( $response['error-codes'] ) && is_array( $response['error-codes'] ) ) {
			foreach ( $response['error-codes'] as $key => $value ) {
				if ( 'missing-input-secret' == $value ) {
					$errors[] = esc_html__( 'The secret parameter is missing.', 'zn_framework' );
					continue;
				}
				if ( 'invalid-input-secret' == $value ) {
					$errors[] = esc_html__( 'The secret parameter is invalid or malformed.', 'zn_framework' );
					continue;
				}
				if ( 'missing-input-response' == $value ) {
					$errors[] = esc_html__( 'Please complete the captcha validation', 'zn_framework' );
					continue;
				}
				if ( 'invalid-input-response' == $value ) {
					$errors[] = esc_html__( 'The response parameter is invalid or malformed.', 'zn_framework' );
					continue;
				}
			}
		}
		// response == false, no error codes retrieved, meaning the ReCaptcha is not properly configured
		else {
			$errors[] = esc_html__( 'Error: ReCaptcha is not properly configured.', 'zn_framework' );
		}
	}
	return $errors;
}

/**
 * Alow videos on mobile
 */
function kallyas_enable_mobile_videos() {
	return 'yes' === zget_option( 'enable_video_mobile', 'advanced_options', false, 'no' );
}
add_filter( 'znb_enable_mobile_video', 'kallyas_enable_mobile_videos' );


/**
 * Alow videos on mobile
 */
function kallyas_force_image_resize() {
	return 'no' === zget_option( 'force_image_resize', 'advanced_options', false, 'yes' );
}
add_filter( 'hg_force_image_resize', 'kallyas_force_image_resize' );
