<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_skills
 *
 * @usage [skills main_text="skills" main_color="#193340" text_color="#ffffff"] Content [/skills]
 */
class zn_shortcode_skills extends HG_Shortcode {

	/**
	 * Retrieve the information about this shortcode
	 * @see hg-framework/assets/src/js/admin/shortcodes/shortcodes.js
	 * @return array
	 */
	public function getInfo() {
		return array(
			'id'         => $this->getTag(),
			'name'       => __( 'Skills', 'zn_framework' ),
			'section'    => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params'     => array(
				array(
					'name'        => __( 'Main text', 'zn_framework' ),
					'id'          => 'main_text',
					'description' => __( 'Enter the main text', 'zn_framework' ),
					'type'        => 'text',
				),
				array(
					'name'        => __( 'Main color', 'zn_framework' ),
					'id'          => 'main_color',
					'description' => __( 'Main color', 'zn_framework' ),
					'type'        => 'colorpicker',
				),
				array(
					'name'        => __( 'Text color', 'zn_framework' ),
					'id'          => 'text_color',
					'description' => __( 'Text color', 'zn_framework' ),
					'type'        => 'colorpicker',
				),
			),
		);
	}


	/**
	 * Retrieve the shortcode tag
	 * @return string
	 */
	public function getTag() {
		return 'skills';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null ) {
		$main_color = '#193340';
		$text_color = '#ffffff';
		$main_text  = __( 'Skills', 'zn_framework');

		extract( shortcode_atts( array(
			'main_text'  => $main_text,
			'main_color' => $main_color,
			'text_color' => $text_color,
		), $atts ) );

		$return  = '';
		$content = wp_strip_all_tags( $content );
		if ( ! empty($content) ) {
			$return = '<div id="skills_diagram" class="hidden-xs">';
			$return .= '<div class="legend">';
			$return .= '<h4>' . __( 'Legend:', 'zn_framework' ) . '</h4>';
			$return .= '<ul class="skills">';
			$return .= do_shortcode( $content );
			$return .= '</ul><!-- end the skills -->';
			$return .= '</div>';
			$return .= '<div id="thediagram"
							data-width="600"
							data-height="600"
							data-maincolor="' . $main_color . '"
							data-maintext="' . $main_text . '"
							data-fontsize="20px Arial"
							data-textcolor="' . $text_color . '"></div>';
			$return .= '</div><!-- end skills diagram -->';
		}
		return $return;
	}

	function scripts() {
		// Load scripts
		wp_enqueue_style( 'skills_diagram-css', THEME_BASE_URI . '/css/shortcodes/skills_diagram.css', array( 'kallyas-styles' ), ZN_FW_VERSION );
		wp_enqueue_script( 'raphael', THEME_BASE_URI . '/pagebuilder/elements/TH_Skills/raphael-min.js', array ( 'jquery' ), ZN_FW_VERSION, true );
		wp_enqueue_script( 'raphael_diagram-init', THEME_BASE_URI . '/addons/raphael_diagram/init.js', array( 'jquery' ), ZN_FW_VERSION );
	}
}
