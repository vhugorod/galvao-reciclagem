<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class zn_shortcode_blockquote
 *
 * @usage [blockquote author="" align="left"] Content [/blockquote]
 */
class zn_shortcode_blockquote extends HG_Shortcode
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
			'name' => __( 'Blockquote', 'zn_framework' ),
			'section' => __( 'Typography', 'zn_framework' ),
			'hasContent' => true,
			'params' => array(
				array(
					'name' => __( 'Author', 'zn_framework' ),
					'id' => 'author',
					'description' => __( 'Enter the quote author name.', 'zn_framework' ),
					'type' => 'text',
					'placeholder' => 'John Doe',
				),
				array(
					'name' => __( 'Quote', 'zn_framework' ),
					'id' => 'content',
					'description' => __( 'Enter the quote.', 'zn_framework' ),
					'type' => 'textarea',
				),
				array(
					'name' => __( 'Alignment', 'zn_framework' ),
					'id' => 'align',
					'description' => __( 'Choose the quote alignment.', 'zn_framework' ),
					'type' => 'select',
					'value' => 'left',
					'options' => array(
						'left' => __( 'Left', 'zn_framework'),
						'right' => __( 'Right', 'zn_framework'),
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
		return 'blockquote';
	}

	/**
	 * Retrieve the shortcode content
	 * @param array       $atts
	 * @param string|null $content
	 * @return string
	 */
	public function render( $atts, $content = null )
	{
		// [blockquote author="" align="left"] Content [/blockquote]
		$align = $author = '';
		extract( shortcode_atts( array( "author" => '',
			"align" => '' ), $atts ) );
		if ( $align == 'right' ) {
			$align = 'pull-right';
		}
		$quote = '<blockquote class="' . $align . '"><p>' . strip_tags( $content ) . '</p>';
		if ( !empty ( $author ) ) {
			$quote .= '<small>' . strip_tags( $author ) . '</small>';
		}
		$quote .= '</blockquote>';
		return str_replace( "\r\n", '', $quote );
	}

}
