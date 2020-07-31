<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_accordion
 *
 * @usage [accordion title="My title" style="" collapsed="true"] Content [/accordion]
 */
class zn_accordion extends HG_Shortcode
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
			'name' => __( 'Accordion', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Accordion title', 'zn_framework' ),
					'id' => 'title',
					'description' => __( 'Enter the desired title for this accordion.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'Accordion title', 'zn_framework' ),
				),
				array(
					'name' => __( 'Accordion content', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the desired content for this accordion.', 'zn_framework' ),
					'type' => 'textarea',
					'placeholder' => __( 'Accordion content', 'zn_framework' ),
				),
				array(
					'name' => __( 'Style', 'zn_framework' ),
					'id' => 'style',
					'description' => __( 'Choose the desired style.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'default-style',
					'options' => array(
						'default-style' => __( 'Default style', 'zn_framework'),
						'style2' => __( 'Style 2', 'zn_framework'),
						'style3' => __( 'Style 3', 'zn_framework'),
					),
				),
				array(
					'name' => __( 'Collapsed ?', 'zn_framework' ),
					'id' => 'collapsed',
					'description' => __( 'Choose the initial state of the accordion pane.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'false',
					'options' => array(
						'false' => __( 'Closed', 'zn_framework'),
						'true' => __( 'open', 'zn_framework'),
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
		return 'accordion';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [accordion title="My title" style="" collapsed="true"] Content [/accordion]
		$style = $collapsed = $title = '';
		extract( shortcode_atts( array( "title" => '',
			"style" => 'default-style',
			"size" => '140',
			"collapsed" => '' ), $atts ) );
		$link = '';
		if ( $style == 'style2' ) {
			$link = 'btn-link';
		}
		$iscollapsed = $collapsed == 'true' ? 'in' : '';
		$uid = uniqid();
		$return = '';
		$return .= '<div class="zn_accordion--shortcode acc--' . esc_attr($style) . ' panel-group ">';
		$return .= '<div class="acc-group ">';
		$return .= '<button data-toggle="collapse" data-target="#acc' . esc_attr($uid) . '" class="acc-tgg-button text-custom collapsed ' .
			esc_attr($link) . '">' . $title . '<span class="acc-icon"></span></button>';
		$return .= '<div id="acc' . esc_attr($uid) . '" class="acc-panel-collapse collapse ' . esc_attr($iscollapsed) . '">';
		$return .= '<div class="acc-content">';
		$return .= do_shortcode( $content );
		$return .= '</div><!-- /.acc-content -->';
		$return .= '</div>'; // .acc-panel-collapse
		$return .= ' </div><!-- end /.acc-group -->';
		$return .= ' </div><!-- end /.acc--style -->';
		return $return;
	}

	public function scripts() {
		// Load Stylesheet from PB element
		wp_enqueue_style( 'accordion-css', THEME_BASE_URI . '/pagebuilder/elements/TH_Accordion/style.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
	}
}
