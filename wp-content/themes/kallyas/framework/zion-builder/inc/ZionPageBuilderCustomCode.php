<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZionPageBuilderCustomCode
 * This class handles all functionality for the page builder frontend editor
 *
 * @category   PageBuilder
 * @package    ZnFramework
 * @author     Balasa Sorin Stefan ( ThemeFuzz )
 * @copyright  Copyright (c) Balasa Sorin Stefan
 * @link       http://themeforest.net/user/ThemeFuzz
 */
class ZionPageBuilderCustomCode
{
	function __construct() {
		add_action( 'wp' , array( $this, 'addInlineCode') );
	}

	function addInlineCode(){
		$postID = ZNB()->utility->getPostID();
		$css = get_post_meta( $postID, 'zn_page_custom_css', true );
		$js  = get_post_meta( $postID, 'zn_page_custom_js', true );

		if ( ! empty( $css ) ) {
			ZNHGFW()->getComponent('scripts-manager')->add_inline_css( $css );
		}
		if ( ! empty( $js ) ) {
			ZNHGFW()->getComponent('scripts-manager')->add_inline_js(
				array(
					'zn_page_custom_js' => $js
				)
			);
		}
	}
}

return new ZionPageBuilderCustomCode();
