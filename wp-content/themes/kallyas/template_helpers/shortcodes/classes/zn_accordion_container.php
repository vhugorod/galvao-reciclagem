<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_accordion_container
 *
 * This class generates the html needed to output the wrapper for an Accordion or a Toggle element.
 *
 * @usage:
 *
 * <code>[accordion_container style="default-style" title="Toggle" type="toggle"] Accordion Pane shortcode
 * [/accordion_container]</code>
 * <code>[accordion_container style="style1" title="Accordion" type="accordion" id="a1234"] Accordion Pane shortcode
 * [/accordion_container]</code>
 *
 * @since 4.0.2
 * @return string
 */
class zn_accordion_container extends HG_Shortcode
{

	/**
	 * Internal flag to know whether or not the scripts were loaded
	 * @var bool
	 */
	private $_scriptsLoaded = false;

	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'accordion_container';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{

		// [accordion_container style="default-style" title="Toggle" type="toggle"] Content [/accordion_container]
		// [accordion_container style="style1" title="Accordion" type="accordion" id="a1234"] Content [/accordion_container]

		$style = $title = $type = $id = '';
		extract( shortcode_atts( array(
			"style" => 'default-style',
			"title" => 'Accordion',
			// The title
			"type" => 'accordion',
			// possible values: accordion, toggle. value accordion requires ID
			// The ID for the accordion. Only required for type = accordion. If omitted a toggle accordion will be generated
			"id" => '',
		), $atts ) );

		if ( 'toggle' == strtolower( $type ) ) {
			$id = ''; // the id is not needed for this type
		}
		else {
			$id = 'id="'. ( empty( $id ) ? uniqid('acc-') : $id ).'"';
		}
		$out = '';
		$out .= '<div class="zn_accordion_element zn_accordion--container zn-acc--' . esc_attr($style) . '">';
		$out .= '<h3 class="acc-title">' . $title . '</h3>';
		$out .= '<div ' . esc_attr($id) . ' class="acc--' . esc_attr($style) . ' panel-group">' . do_shortcode( $content ) . '</div>';
		$out .= '</div>';
		return $out;
	}

	public function scripts() {
		// Load scripts
		wp_enqueue_style( 'accordion-css', THEME_BASE_URI . '/pagebuilder/elements/TH_Accordion/style.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}
