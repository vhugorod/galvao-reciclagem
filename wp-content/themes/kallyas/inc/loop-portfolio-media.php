<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! isset( $zn_link_portfolio ) ) {
	global $zn_link_portfolio;
}

echo '<div class="img-intro portfolio-item-overlay-imgintro">';
$port_media = get_post_meta( $post->ID, 'zn_port_media', true );
if ( ! empty( $port_media ) && is_array( $port_media ) ) {
	$size      = zn_get_size( 'eight' );
	$has_image = false;

	// Modified portfolio display
	// Check to see if we have images
	$portfolio_image = $port_media[0]['port_media_image_comb'];
	if ( $portfolio_image ) {
		if ( is_array( $portfolio_image ) ) {
			$saved_image = $portfolio_image['image'];
			if ( $saved_image ) {
				if ( ! empty( $portfolio_image['alt'] ) ) {
					$saved_alt = $portfolio_image['alt'];
				} else {
					$saved_alt = '';
				}
				if ( ! empty( $portfolio_image['title'] ) ) {
					$saved_title = 'title="' . $portfolio_image['title'] . '"';
				} else {
					$saved_title = '';
				}
				$has_image = true;
			}
		} else {
			$saved_image = $portfolio_image;
			$has_image   = true;
			$saved_alt   = ZngetImageAltFromUrl( $saved_image );
			$saved_title = ZngetImageTitleFromUrl( $saved_image, true );
		}

		if ( $has_image ) {
			$image = vt_resize( '', $saved_image, $size['width'], '', true );
		}
	}

	// Check to see if we have video
	$portfolio_media = $port_media[0]['port_media_video_comb'];

	// Display the media
	if ( ! empty( $saved_image ) && $portfolio_media ) {
		echo '<a href="' . esc_attr( $portfolio_media ) . '" data-mfp="iframe" data-lightbox="iframe" class="portfolio-item-link hoverLink"></a>';
		echo '<img class="kl-ptf-catlist-img" src="' . esc_attr( $image['url'] ) . '" width="' . esc_attr( $image['width'] ) . '" height="' . esc_attr( $image['height'] ) . '" alt="' . esc_attr( $saved_alt ) . '" ' . esc_attr( $saved_title ) . ' />';
		echo '<div class="portfolio-item-overlay">';
		echo '<div class="portfolio-item-overlay-inner">';
		echo '<span class="portfolio-item-overlay-icon glyphicon glyphicon-play"></span>';
		echo '</div>';
		echo '</div>';
	}
	elseif ( ! empty( $saved_image ) ) {

		$overlay = '
		<div class="portfolio-item-overlay">
			<div class="portfolio-item-overlay-inner">
				<span class="portfolio-item-overlay-icon glyphicon glyphicon-picture"></span>
			</div>
		</div>';

		if ( 'yes' === $zn_link_portfolio ) {
			echo '<a href="' . esc_attr( get_permalink() ) . '" class="portfolio-item-link hoverLink"></a>';
			echo '<img class="kl-ptf-catlist-img" src="' . esc_attr( $image['url'] ) . '" width="' . esc_attr( $image['width'] ) . '" height="' . esc_attr( $image['height'] ) . '" alt="' . esc_attr( $saved_alt ) . '" ' . esc_attr( $saved_title ) . ' />';
			echo $overlay;
		}
		else {
			echo '<a href="' . esc_attr( $saved_image ) . '" data-type="image" data-lightbox="image" class="portfolio-item-link hoverLink"></a>';
			echo '<img class="kl-ptf-catlist-img" src="' . esc_attr( $image['url'] ) . '" width="' . esc_attr( $image['width'] ) . '" height="' . esc_attr( $image['height'] ) . '" alt="' . esc_attr( $saved_alt ) . '" ' . esc_attr( $saved_title ) . ' />';
			echo $overlay;
		}
	} elseif ( $portfolio_media ) {
		echo get_video_from_link( $portfolio_media, '', $size['width'], $size['height'] );
	}
}
echo '</div><!-- img intro -->';
