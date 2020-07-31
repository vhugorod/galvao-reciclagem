<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_accordion_panel
 *
 * This class generates the html needed to output an Accordion Pane.
 *
 * @usage:
 *
 * <code>[accordion_pane title="Accordion" collapsed="true"] Content [/accordion_pane]</code>
 * <code>[accordion_pane title="Accordion" collapsed="true" parent_id="a1234"] Content [/accordion_pane]</code>
 *
 * @param array       $atts    The list of arguments the shortcode accepts
 * @param string|null $content The content to display inside the accordion pane
 *
 * @since 4.0.2
 * @return string
 */
class zn_accordion_panel extends HG_Shortcode
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
		return 'accordion_pane';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{

		// [accordion_pane title="Accordion" collapsed="true"] Content [/accordion_pane]
		// [accordion_pane title="Accordion" collapsed="true" parent_id="a1234"] Content [/accordion_pane]

		$collapsed = $title = $type = $parent_id = '';
		extract( shortcode_atts( array(
			"title" => __( 'Accordion', 'zn_framework' ),
			"parent_id" => '',
			// the ID for the accordion. if omitted, it will be auto-generated
			"collapsed" => 'true',
		), $atts ) );

		$isCollapsed = ( $collapsed == 'false' ? 'in' : '' );
		// if accordion, add data-parent data attr
		$dataParent = '';
		if ( !empty( $parent_id ) ) {
			$dataParent = 'data-parent="#' . esc_attr($parent_id) . '"';
		}

		$pid = uniqid( 'acc_' );
		$out = '
<div class="panel acc-group">
	<div class="acc-panel-title">
		<a ' . $dataParent . ' data-toggle="collapse" href="#' . esc_attr($pid) . '" class="acc-tgg-button text-custom collapsed">' . $title . '<span class="acc-icon"></span></a>
	</div>
	<div id="' . esc_attr($pid) . '" class="acc-panel-collapse collapse ' . esc_attr($isCollapsed) . '">
		<div class="acc-content">' . do_shortcode( $content ) . '</div>
	</div>
</div>';
		return $out;
	}

	public function scripts()
	{
		// Load scripts
		wp_enqueue_style('accordion-css', THEME_BASE_URI . '/pagebuilder/elements/TH_Accordion/style.css', array('kallyas-styles'), ZN_FW_VERSION);
	}
}
