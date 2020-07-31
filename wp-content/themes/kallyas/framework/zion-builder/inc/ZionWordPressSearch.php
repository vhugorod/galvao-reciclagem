<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionWordPressSearch
 *
 * This class extends the WordPress' search functionality to include the Page Builder's meta field into the search query
 */
class ZionWordPressSearch
{
	/**
	 * Class constructor
	 */
	function __construct()
	{
		/**
		 * Extend the search query to include the Page Builder elements
		 * @since v1.0.0
		 */
		add_action( 'pre_get_posts', array( $this, 'updateSearchQuery' ) );
	}

	/**
	 * Updates the search query to include the Page Builder elements
	 *
	 * @param WP_Query $query
	 * @since v1.0.0
	 * @return WP_Query
	 */
	function updateSearchQuery( $query )
	{
		$canSearch = ( !is_admin() && $query->is_main_query() && is_search() );
		if ( $canSearch ) {
			add_filter( 'posts_search', array( $this, 'searchPb' ), 99, 2 );
			add_filter( 'posts_join', array( $this, 'searchPbJoin' ), 99, 1 );
			add_filter( 'posts_distinct_request', array( $this, 'searchDistinct' ), 99, 1 );
		}
		return $query;
	}

	/**
	 * Updates the search SQL to include PageBuilder data
	 *
	 * @param string $search
	 * @param WP_Query $query
	 * @since v1.0.0
	 * @return mixed
	 */
	public function searchPb( $search, WP_Query $query )
	{
		global $wpdb;
		$postTitleCol = "{$wpdb->posts}.post_title";
		$search = $wpdb->remove_placeholder_escape($search);
		$search = preg_replace_callback( "/$postTitleCol LIKE \'(.*?)\'/", array( $this, 'searchCallback' ), $search );
		$search = $wpdb->add_placeholder_escape($search);

		remove_filter( 'posts_search', array( $this, 'searchPb' ), 99 );
		return $search;
	}

	/**
	 * Update the search query to include the pb_meta.meta_value field
	 * @param array $matches
	 * @return string
	 */
	public function searchCallback( $matches )
	{
		if( isset($matches[0]) && !empty($matches[0]) && isset($matches[1]) && !empty($matches[1]) ) {
			$matches = "{$matches[0]}) OR (pb_meta.meta_value LIKE '{$matches[1]}'";
		}
		return $matches;
	}

	/**
	 * Updates the search where query to include the meta fields
	 *
	 * @param string $join
	 * @since v1.0.0
	 * @return mixed
	 */
	public function searchPbJoin( $join )
	{
		global $wpdb;
		$join .= " LEFT JOIN {$wpdb->postmeta} AS pb_meta ON {$wpdb->posts}.ID = pb_meta.post_id ";
		remove_filter( 'posts_join', array( $this, 'searchPbJoin' ), 99 );
		return $join;
	}

	/**
	 * Updates the search to return only distinct results
	 *
	 * @since v1.0.0
	 * @return string
	 */
	public function searchDistinct()
	{
		remove_filter( 'posts_distinct_request', array( $this, 'searchDistinct' ), 99 );
		return "DISTINCT";
	}
}

return new ZionWordPressSearch();
