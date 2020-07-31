<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Znb_WordPress_Revisions
 */
class Znb_WordPress_Revisions extends Znb_Integration
{
	/**
	 * Check if we can load this integration or not
	 * @return bool
	 */
	static public function can_load()
	{
		return defined( 'WP_POST_REVISIONS' );
	}


	function initialize()
	{
		/**
		 * Save the PB data as a post revision
		 * @since v4.0.12
		 */
		add_action( 'znpb_save_page', array( $this, 'save_post_revision' ) );
		add_action( 'wp_restore_post_revision', array( $this, 'restore_post_revision' ), 10, 2 );
	}

	/**
	 * Restore the PB data as a post revision
	 * @since v4.0.12
	 */
	function restore_post_revision( $post_id, $revision_id )
	{
		$revision = get_post( $revision_id );
		$pb_data  = get_metadata( 'post', $revision->ID, 'zn_page_builder_els', true );

		if ( false !== $pb_data ) {
			update_post_meta( $post_id, 'zn_page_builder_els', $pb_data );
		}
		else {
			delete_post_meta( $post_id, 'zn_page_builder_els' );
		}
	}

	/**
	 * Save the PB data as a post revision
	 * @since v4.0.12
	 */
	function save_post_revision()
	{
		add_action( 'save_post', array( $this, 'update_post_revision' ), 10, 2 );
		add_action( '_wp_put_post_revision', array( $this, 'update_post_revision_modified_time' ), 10 );
		add_action( 'wp_revisions_to_keep', array( $this, 'wp_revisions_to_keep' ), 1, 2 );
		add_filter( 'wp_save_post_revision_check_for_changes', array( $this, 'force_save_revision' ) );
		wp_save_post_revision( ZNB()->utility->getPostID() );
	}

	/**
	 * Add the pagebuilder content to a post revision upon saving
	 * @since v4.0.12
	 */
	function update_post_revision( $post_id, $post )
	{
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$parent  = get_post( $parent_id );
			$pb_data = get_post_meta( $parent->ID, 'zn_page_builder_els', true );
			if ( false !== $pb_data ) {
				add_metadata( 'post', $post_id, 'zn_page_builder_els', $pb_data );
			}
		}
	}

	function update_post_revision_modified_time( $revisionID )
	{
		$time = current_time( 'mysql' );
		wp_update_post(
			array(
				'ID' => $revisionID, // ID of the post to update
				'post_modified' => $time,
				'post_modified_gmt' => get_gmt_from_date( $time )
			)
		);
	}

	/**
	 * Limit the post revisions to 5 if they are enabled
	 * @since v4.0.12
	 */
	function wp_revisions_to_keep( $num, $post )
	{
		return ( $num > 0 ? $num : 5 );
	}

	/**
	 * Disable the revision change check for PB content page save
	 * @since v4.0.12
	 */
	function force_save_revision()
	{
		return false;
	}
}
