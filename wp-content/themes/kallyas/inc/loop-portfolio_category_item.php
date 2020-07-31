<?php if(! defined('ABSPATH')){ return; }
global $zn_config, $colWidth,$zn_link_portfolio,$ports_num_columns,$extra_content;

		$title_link_start = '';
		$title_link_end = '';
		if ( $zn_link_portfolio != 'no_all' ) {
			$title_link_start = '<a href="'. get_permalink() .'">';
			$title_link_end = '</a>';
		}

		$ptf_show_title = isset($zn_config['ptf_show_title']) && !empty($zn_config['ptf_show_title']) ? $zn_config['ptf_show_title'] : 'yes';
		$ptf_show_desc = isset($zn_config['ptf_show_desc']) && !empty($zn_config['ptf_show_desc']) ? $zn_config['ptf_show_desc'] : 'yes';

		// $i += $colWidth;
		echo '<div class="col-xs-12 col-sm-4 col-lg-'.$colWidth.'">';

			echo '<div class="portfolio-item kl-has-overlay portfolio-item--overlay" '.WpkPageHelper::zn_schema_markup('creative_work').'>';

				if( $ports_num_columns == 1 ){
					echo '<div class="row">';
					echo '<div class="col-sm-6">';
				}

				// Portfolio media
				include( locate_template( 'inc/loop-portfolio-media.php' ) );

				// If we have only 1 column
				if( $ports_num_columns == 1 ){
					echo '</div>';
					echo '<div class="col-sm-6">';
				}

				echo '<div class="portfolio-entry kl-ptf-catlist-details">';

					if($ptf_show_title == 'yes') {
						echo '<h3 class="title kl-ptf-catlist-title" '.WpkPageHelper::zn_schema_markup('title').'>';
							echo $title_link_start;
							echo get_the_title();
							echo $title_link_end;
						echo '</h3>';
					}

					if($ptf_show_desc == 'yes') {
					echo '<div class="pt-cat-desc kl-ptf-catlist-desc">';

						if (preg_match('/<!--more(.*?)?-->/', $post->post_content)) {
							the_content('');
						}
						else {
							$exc = get_the_excerpt();
							echo wpautop( wp_trim_words($exc, 10) );
						}

					echo '</div><!-- pt cat desc -->';
					}

				if( $ports_num_columns == 1 && $extra_content == 'yes' ){
					get_template_part( 'inc/details', 'portfolio' );
				}

				echo '</div><!-- End portfolio-entry -->';

				// If we have only 1 column
				if( $ports_num_columns == 1 ){
					echo '</div>'; // End col-sm-6
					echo '</div>'; // End row
				}

			echo '</div><!-- END portfolio-item -->';
		echo '</div>';