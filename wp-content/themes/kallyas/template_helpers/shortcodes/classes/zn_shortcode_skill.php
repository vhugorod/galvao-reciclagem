<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_skills
 *
 * @usage [skill main_color="#97BE0D" percentage="95"] Content [/skill]
 */
class zn_shortcode_skill extends HG_Shortcode
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
			'name' => __( 'Single skill', 'zn_framework' ),
			'section' => __( 'Content', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Skill title', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the desired skill title', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => __( 'My awesome skill', 'zn_framework' ),
				),
				array(
					'name' => __( 'Main color', 'zn_framework' ),
					'id' => 'main_color',
					'description' => __( 'Choose the main color you want to use', 'zn_framework' ),
					'type' => 'colorpicker',
					'value' => '#193340',
				),
				array(
					'name' => __( 'Skill percentage', 'zn_framework' ),
					'id' => 'percentage',
					'description' => __( 'Enter the skill percentage value', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => '90',
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
		return 'skill';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		$percentage = $main_color = '';
		extract( shortcode_atts( array(
			"main_color" => '#97BE0D',
			"percentage" => '95'
		), $atts ) );
		$return = '<li data-percent="' . $percentage . '" style="background-color:' . $main_color . ';">' . $content . '</li>';
		return $return;
	}

}
