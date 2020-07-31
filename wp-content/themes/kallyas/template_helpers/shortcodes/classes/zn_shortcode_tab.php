<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_tab
 *
 * @usage [tab title="TAB_NAME"] CONTENT [/tab]
 */
class zn_shortcode_tab extends HG_Shortcode
{

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo()
	{
		return array(
			'id' => $this->getTag(),
			'name' => __( 'Single tab', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Tab title', 'zn_framework' ),
					'id' => 'title',
					'type' => 'text',
					'description' => __( 'Enter the desired tab title', 'zn_framework' ),
					'placeholder' => __( 'Title', 'zn_framework' ),
				),
				array(
					'name' => __( 'Tab content', 'zn_framework' ),
					'id' => 'content',
					'type' => 'textarea',
					'description' => __( 'Enter the desired tab content', 'zn_framework' ),
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
		return 'tab';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$title = '';
		global $hgShTabs_divs, $hgShTabs_num;
		$defaults = array( 'title' => __('Tab', 'zn_framework') );
		extract( shortcode_atts( $defaults, $atts ) );
		$id = 'side-tab' . rand( 100, 999 );
		$active = ($hgShTabs_num == 0 ? 'active' : '');
		$output = '
	<li class="' . esc_attr($active) . '">
		<a href="#' . esc_attr($id) . '" data-toggle="tab">' . $title . '</a>
	</li>
';
		$hgShTabs_divs .= '<div id="' . esc_attr($id) . '" class="tab-pane ' . esc_attr($active) . '">' . do_shortcode( $content ) . '</div>';
		$hgShTabs_num++;
		return $output;
	}
}