<?php
get_header();

	// We don't need to perform the loop as it will be made by the archive elements
	if( is_archive() || is_home() ){
		ZNB()->frontend->renderContentByArea();
	}
	else{
		while ( have_posts() ) :
			the_post();
			ZNB()->frontend->renderContentByArea();
		endwhile;
	}

get_footer();
