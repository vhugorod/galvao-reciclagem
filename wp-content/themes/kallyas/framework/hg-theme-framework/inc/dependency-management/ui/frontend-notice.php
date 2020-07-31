<?php
	add_filter( 'wp_title filter', 'hgtfw_filter_wp_title', 9000 );
	function hgtfw_filter_wp_title() {
		return esc_html__( 'Admin action required', 'zn_framework' );
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php wp_title(); ?></title>
		<link href="<?php echo ZNHGTFW()->getFwUrl( 'assets/css/bootstrap-normalize-grid-typography.min.css' );?>" rel="stylesheet">
		<link href="<?php echo ZNHGTFW()->getFwUrl( 'assets/css/frontend_dependency_ui.css' );?>" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h3 class="znhgtfw-fDeps-title"><?php esc_html_e( 'An administration action is required!', 'zn_framework' ); ?></h3>
					<?php
					if( current_user_can( 'install_plugins' ) ): ?>
					<p><a href="<?php echo ZNHGTFW()->getComponent('utility')->get_options_page_url() . '#zn-about-tab-addons-dashboard'; ?>"><img src="<?php echo ZNHGTFW()->getFwUrl( 'assets/img/dependency_frontend.jpg' );?>" class="znhgtfw-fDeps-img" alt="<?php esc_attr_e('Click to Access Theme Addons', 'zn_framework');?>"></a></p>
					<p><a href="<?php echo ZNHGTFW()->getComponent('utility')->get_options_page_url() . '#zn-about-tab-addons-dashboard'; ?>" class="znhgtfw-fDeps-btn"><?php esc_html_e( 'Access Theme Addons', 'zn_framework' ); ?></a></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</body>
</html>
