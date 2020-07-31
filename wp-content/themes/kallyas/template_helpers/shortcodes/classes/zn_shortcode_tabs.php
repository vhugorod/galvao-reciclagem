<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}
/*
 * Global vars
 * #! do not remove
 * @see: zn_shortcode_tab.php
 */
$hgShTabs_divs = '';
$hgShTabs_num = 0;

/**
 * Class zn_shortcode_tabs
 *
 * @usage [tabs style=""]  [tab title="TAB_NAME"] CONTENT [/tab]  [tab title="TAB_NAME"] CONTENT [/tab] [/tabs]
 */
class zn_shortcode_tabs extends HG_Shortcode
{
	/**
	 * Internal flag to know whether or not the scripts were loaded
	 * @var bool
	 */
	private $_scriptsLoaded = false;

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo()
	{
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Tabs container', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Style', 'zn_framework' ),
					'id' => 'style',
					'description' => __( 'Choose the desired style', 'zn_framework' ),
					'type' => 'select',
					'value' => 'style1',
					'options' => array(
						'style1' => __( 'Style 1', 'zn_framework' ),
						'style2' => __( 'Style 2', 'zn_framework' ),
						'style3' => __( 'Style 3', 'zn_framework' ),
						'style4' => __( 'Style 4', 'zn_framework' ),
					),
				),
			),
		);
	}


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag()
	{
		return 'tabs';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{

		// [tabs style=""]  [tab title="TAB_NAME"] CONTENT [/tab]  [tab title="TAB_NAME"] CONTENT [/tab]  [tab title="TAB_NAME"] CONTENT [/tab][/tabs]
		$style = $tabdirection = $tabtype = '';
		global $hgShTabs_divs, $hgShTabs_num;
		extract( shortcode_atts( array(
			'tabtype' => '',
			'style' => 'style1',
			'tabdirection' => 'vertical',
			), $atts ) );
		$hgShTabs_divs = '';
		$output = '<div class="tabbable tabs_' . esc_attr($style) . ' tabs-' . esc_attr($tabdirection) . '">
						<ul class="nav ' . esc_attr($tabtype) . '" id="custom-tabs-' . rand( 1, 100 ) . '">';
		$output .= do_shortcode( $content ) . '</ul>';
		$output .= '<div class="tab-content">' . $hgShTabs_divs . '</div></div>';
		$hgShTabs_num = 0;
		return $output;
	}

	public function scripts() {
		// Load scripts
		wp_enqueue_style( 'tabs-css', THEME_BASE_URI . '/pagebuilder/elements/TH_HorizontalTabs/style.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}