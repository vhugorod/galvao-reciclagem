<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ht_year
 *
 * @usage [ht_year]
 */
class ht_year extends HG_Shortcode
{
	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public  function getInfo()
	{
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Current year', 'zn_framework' ),
			'section' => __( 'Marketing', 'zn_framework' ),
			'hasContent' => false,
			'params' => array(),
		);
	}


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public  function getTag()
	{
		return 'ht_year';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public  function render( $atts, $content = null )
	{
		// Return the proper year based on the local time
		return date_i18n('Y', current_time('timestamp'));
	}
}
