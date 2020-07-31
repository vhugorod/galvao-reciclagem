<?php if(! defined('ABSPATH')){ return; }
/**
 * This file contains helper functions for sensei plguin by WooThemes
 */

add_filter( 'sjb_content_wrapper_start_template', 'zn_sjb_before_main_content' );
add_action( 'sjb_content_wrapper_end_template', 'zn_sjb_after_main_content' );
function zn_sjb_before_main_content(){

ob_start();
	WpkPageHelper::zn_get_subheader();

	global $zn_config;
	$zn_config['force_sidebar'] = 'blog_sidebar';
	$main_class = zn_get_sidebar_class( 'blog_sidebar' );
	if( strpos( $main_class , 'right_sidebar' ) !== false || strpos( $main_class , 'left_sidebar' ) !== false ) { $zn_config['sidebar'] = true; } else { $zn_config['sidebar'] = false; }
	$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
	$content_size = 12 - (int)$sidebar_size;
	$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-'.$content_size : 'col-sm-12';

	?>
	<section id="content" class="site-content shop_page">
		<div class="container">
			<div class="row">
				<div class="<?php echo esc_attr( $main_class ); ?>">
	<?php

	return ob_get_clean();
}

function zn_sjb_after_main_content(){
	ob_start();
	?>

				</div>
				<!-- sidebar -->
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
