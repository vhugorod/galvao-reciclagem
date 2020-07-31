<?php

/**
 * This class will include different utility functions
 */
class ZnHgFw_Utility {

	/**
	 * Holds a refference to an instance of WP_Filesystem_Direct class
	 * @var null|WP_Filesystem_Direct
	 */
	private $_fileSystemDefault = null;

	/**
	 * This is a wrapper for WP_Filesystem_Direct
	 * @return WP_Filesystem_Direct an instance of WP_Filesystem_Direct
	 */
	public function getFileSystem(){
		if( is_null( $this->_fileSystemDefault ) ) {
			$this->loadWpFileSystemDirect();
			$this->_fileSystemDefault = new WP_Filesystem_Direct( array() );
		}
		return $this->_fileSystemDefault;
	}

	/**
	 * Load the WP_Filesystem_Direct class into the current execution scope
	 */
	public function loadWpFileSystemDirect()
	{
		//#! Try with WP File System first
		if ( !class_exists( 'WP_Filesystem_Base' ) ) {
			require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-base.php' );
		}
		if ( !class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-direct.php' );
		}
		if( ! defined('FS_CHMOD_DIR') ) {
			define( 'FS_CHMOD_DIR', ( 0755 & ~umask() ) );
		}
		if( ! defined('FS_CHMOD_FILE') ) {
			define( 'FS_CHMOD_FILE', ( 0644 & ~umask() ) );
		}
	}
}

return new ZnHgFw_Utility();
