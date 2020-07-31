<?php if(! defined('ABSPATH')){ return; }

	// Get portfolio fields
	get_template_part( 'inc/details', 'portfolio-fields' );

?>
<div class="portfolio-item-otherdetails clearfix">
	<?php

	// Social Sharing
	$sp_show_social = get_post_meta( get_the_ID(), 'zn_sp_show_social', true );
	if ( ! empty ( $sp_show_social ) && $sp_show_social == 'yes' ) {
		?>
		<div class="portfolio-item-share clearfix">
			<?php
				echo zn_social_share_icons(array(
					'share_media' => !empty( $portfolio_image ) ? $portfolio_image : '',
					'share_text' => sprintf( __( "Check out this awesome project: %s", 'zn_framework' ) , get_the_title() ),
				));
			?>
		</div><!-- social links -->
		<?php
	}

	$sp_link = get_post_meta( get_the_ID(), 'zn_sp_link', true );
	$sp_link_ext = zn_extract_link($sp_link, 'btn btn-lined lined-custom');

	if ( ! empty ( $sp_link_ext['start'] ) ) {

		$button_text = get_post_meta( get_the_ID(), 'zn_sp_link_text', true );
		$button_text = ! empty( $button_text ) ? $button_text : __( "PROJECT LIVE PREVIEW", 'zn_framework' );

		echo '
		<div class="portfolio-item-livelink">
			'.$sp_link_ext['start'].'
				<span class="visible-sm visible-xs visible-lg">' . $button_text . '</span>
				<span class="visible-md">' . __( "PREVIEW", 'zn_framework' ) . '</span>
			'.$sp_link_ext['end'].'
		</div>';
	}
	?>

</div><!-- /.portfolio-item-otherdetails -->
