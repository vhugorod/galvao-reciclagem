<?php if(! defined('ABSPATH')){ return; }

class ZNB_CustomHTML extends ZionElement
{


	/**
	 * This method is used to display the output of the element.
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-customHtml clearfix';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.implode(' ', $classes).'"';

		echo '<div '. zn_join_spaces( $attributes ) .'>';
		echo force_balance_tags( $this->opt( 'custom_html' ) );
		echo '</div>';

	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];

		$options = array(
			'has_tabs'  => true,
			'general' => array(
				'title' => __('General options','zn_framework'),
				'options' => array(

					array(
						'id'          => 'custom_html',
						'name'        => __('Custom HTML','zn_framework'),
						'description' => __('Using this option you can enter you own custom HTML code. If you plan on adding CSS or JavaScript, wrap the codes into &lt;style type="text/css"&gt;...&lt;/style&gt; respectively &lt;script&gt;...&lt;/script&gt; . <strong>Please make sure your JS code is fully functional</strong> as it might break the entire page!!','zn_framework'),
						'type'        => 'custom_html',
						'class'       => 'zn_full',
						'editor_type' => 'html',
					)

				)
			),

			'help' => znpb_get_helptab( array(
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);
		return $options;
	}
}

ZNB()->elements_manager->registerElement( new ZNB_CustomHTML(array(
	'id' => 'ZnCustomHtml',
	'name' => __('Custom HTML', 'zn_framework'),
	'description' => __('This element will render HTML code specified by the user.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'legacy' => false,
	'keywords' => array('html', 'css', 'javascript', 'code'),
)));
