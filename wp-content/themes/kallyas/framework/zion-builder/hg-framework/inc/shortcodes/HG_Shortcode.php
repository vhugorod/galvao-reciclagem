<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class HG_Shortcode
 *
 * Base class. All derived classes must implement the getTag and render methods.
 */
class HG_Shortcode
{
//<editor-fold desc=":: ADMIN ::">
	/**
	 * Retrieve the shortcode tag. Derived classes MUST implement this method.
	 * @return string
	 */
	public function getTag()
	{
		return '';
	}

	/**
	 * Retrieve the information about this shortcodes. Derived classes MUST implement this method.
	 * @see zn_framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo()
	{
		return array();
	}
//</editor-fold desc=":: ADMIN ::">

//<editor-fold desc=":: FRONTEND ::">

	/**
	 * Retrieve the shortcode content. Derived classes MUST implement this method.
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function _render( $atts, $content = null )
	{
		$this->scripts();
		return $this->render($atts, $content);
	}

	/**
	 * Allow derived classes to enqueue their own scripts in the page footer
	 */
	public function scripts(){}

//</editor-fold desc=":: FRONTEND ::">
}
