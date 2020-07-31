<?php if(! defined('ABSPATH')){ return; }
/*--------------------------------------------------------------------------------------------------

	File: functions-frontend.php

	Description: This file contains various functions that can be used for all themes
	Please be carefull when editing this file

--------------------------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------
	CHECK TO SEE WHAT ARCHIVE TITLE WE NEED TO DISPLAY ( a better alternative for post_type_archive_title(); )
--------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'get_the_archive_title' ) ) {
	function get_the_archive_title(){
		return zn_archive_title();
	}
}


if ( ! function_exists( 'zn_archive_title' ) ) {
	function zn_archive_title()
	{
		$title = '';
		if ( is_category() ) {
			$title = sprintf( esc_html__( 'Category: %s', 'zn_framework'  ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( esc_html__( 'Tag: %s', 'zn_framework'  ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( esc_html__( 'Author: %s', 'zn_framework'  ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			$title = sprintf( esc_html__( 'Year: %s', 'zn_framework'  ), get_the_date( _x( 'Y', 'yearly archives date format', 'zn_framework'  ) ) );
		} elseif ( is_month() ) {
			$title = sprintf( esc_html__( 'Month: %s', 'zn_framework'  ), get_the_date( _x( 'F Y', 'monthly archives date format', 'zn_framework'  ) ) );
		} elseif ( is_day() ) {
			$title = sprintf( esc_html__( 'Day: %s', 'zn_framework'  ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'zn_framework' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'zn_framework' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'zn_framework' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( esc_html__( 'Archives: %s', 'zn_framework' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( esc_html__( '%1$s: %2$s', 'zn_framework' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = esc_html__( 'Archives', 'zn_framework' );
		}

		/**
		 * Filter the archive title.
		 *
		 * @since 4.1.0
		 *
		 * @param string $title Archive title to be displayed.
		 */
		return apply_filters( 'get_the_archive_title', $title );

	}
}


/*--------------------------------------------------------------------------------------------------
	Show the menu by location
--------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'zn_show_nav' ) ) {
	function zn_show_nav( $location, $class = null , $args = array() ) {

		$defaults = array(
					'theme_location' => $location,
					'link_before'=> '',
					'link_after' => '',
					'container' => '',
					'menu_class'      => $class,
					'fallback_cb' => '',
					'echo' => true
				);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'zn_menu_location', $args );

		return wp_nav_menu( $args );

	}
}

/*--------------------------------------------------------------------------------------------------
	Pagination Functions
--------------------------------------------------------------------------------------------------*/

// Add Custom class
if ( ! function_exists( 'zn_posts_link_attributes_next' ) ) {
	add_filter('next_posts_link_attributes', 'zn_posts_link_attributes_next' );
	function zn_posts_link_attributes_next() {
		return 'class="pagination-item-link pagination-item-next-link"';
	}
}
// Add Custom class
if ( ! function_exists( 'zn_posts_link_attributes_prev' ) ) {
	add_filter('previous_posts_link_attributes', 'zn_posts_link_attributes_prev' );
	function zn_posts_link_attributes_prev() {
		return 'class="pagination-item-link pagination-item-prev-link"';
	}
}

if ( ! function_exists( 'zn_pagination' ) ) {
	function zn_pagination( $args = array() )
	{
		global $paged,$wp_query;

		$defaults = array(
			'range' => 3,
			'showitems' => 7,
			'paged' => empty( $paged ) ? 1 : $paged,
			'method' => 'get_pagenum_link',
			'pages' => !$wp_query->max_num_pages ? 1 : $wp_query->max_num_pages,
			'previous_text' => esc_html__('Newer posts', 'zn_framework'),
			'older_text' => esc_html__('Older posts', 'zn_framework'),
			'list_class' => 'kl-pagination',
			'echo' => true,
			'is_comment_pagination' => false
		);

		$output = '';

		// LET THE THEME FILTER THE DEFAULTS
		$defaults = apply_filters( 'zn_pagination', $defaults );
		$args = wp_parse_args( $args, $defaults );

		if( (int)$args['pages'] > 1 )
		{
			$isComment = $args['is_comment_pagination'];
			$output .= '<ul class="zn-paginationList '.$args['list_class'].'">';

			if ( false !== $args['previous_text'] ) {
				if( 1 != $args['paged'] ) {
					$prevText = $isComment ? get_previous_comments_link( $args['previous_text'] ) : get_previous_posts_link($args['previous_text']);
					$output .= '<li class="pagination-item pagination-item-prev pagination-prev">'. $prevText .'</li>';
				}
				else{
					$output .= '<li class="pagination-item pagination-item-prev pagination-prev"><span class="pagination-item-span pagination-item-span-prev">'. $args['previous_text'] .'</span></li>';
				}
			}

			for ( $i=1; $i <= $args['pages']; $i++) {
				if ( !($i >= $args['paged']+$args['range']+1 || $i <= $args['paged']-$args['range']-1) || $args['pages'] <= $args['showitems']  ) {
					$output .= ($args['paged'] == $i)? '<li class="pagination-item pagination-item-active active"><span class="pagination-item-span pagination-item-active-span">'.$i.'</span></li>':'<li class="pagination-item"><a class="pagination-item-link" href="'.$args['method']($i).'"><span class="pagination-item-span">'.$i.'</span></a></li>';
				}
			}

			if ( false !== $args['older_text'] ) {

				if ( $args['paged'] < $args['pages'] ) {
					$text = $isComment ? get_next_comments_link( $args['older_text'] ) : get_next_posts_link( $args['older_text'] );
					$output .= '<li class="pagination-item pagination-item-next pagination-next">' . $text . '</li>';
				}
				else {
					$output .= '<li class="pagination-item pagination-item-next pagination-next"><span class="pagination-item-span pagination-item-span-next">' . $args['older_text'] . '</span></li>';
				}
			}
			$output .= '</ul>';
		}

		if ($args['echo']) {
			echo ''.$output;
		}

		return $output;
	}
}
