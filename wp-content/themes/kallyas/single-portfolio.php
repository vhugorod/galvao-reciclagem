<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}
/*
 * Template layout for single entries
 * @package  Kallyas
 * @author   Team Hogash
 */
get_header();

/*** USE THE NEW HEADER FUNCTION **/
WpkPageHelper::zn_get_subheader();

/*--------------------------------------------------------------------------------------------------
	CONTENT AREA
--------------------------------------------------------------------------------------------------*/
$main_class = zn_get_sidebar_class( 'portfolio_sidebar' );
if ( strpos( $main_class, 'right_sidebar' ) !== false || strpos( $main_class, 'left_sidebar' ) !== false ) {
	$zn_config['sidebar'] = true;
} else {
	$zn_config['sidebar'] = false;
}
$sidebar_size = zget_option( 'sidebar_size', 'unlimited_sidebars', false, 3 );
$content_size = 12 - (int)$sidebar_size;
$zn_config['size'] = $zn_config['sidebar'] ? 'col-sm-8 col-md-' . $content_size : 'col-sm-12';

?>

<section id="content" class="site-content" >
	<div class="container">
		<div class="row">

		<div id="mainbody" class="<?php echo esc_attr( $main_class ); ?>"  <?php echo WpkPageHelper::zn_schema_markup( 'main' ); ?>>
			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'inc/page', 'content-view-portfolio.inc' );
				endwhile;
			?>
		</div>
		<?php get_sidebar(); ?>

		</div><!--// End .row -->
	</div><!--// End .container -->
</section><!--// #content -->
<?php get_footer(); ?>
