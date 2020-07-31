<?php if(! defined('ABSPATH') ){ return; }
/*
 * This element won't load if the theme Kallyas is active.
 * @see: kallyas/framework/compatibility/framework.php
 * @see add_action( 'znb:elements:init', 'hg_ZionBuilderElementsInit', 25 );
 */

/**
 * Class ZNB_Newsletter
 */
class ZNB_Newsletter extends ZionElement
{

	/**
	 * Holds the nonce to validate when submitting the form
	 * @var null|string
	 */
    private $_nonce = null;

    function __construct( array $args = array() )
	{
		parent::__construct( $args );
		$this->_nonce = wp_create_nonce( 'zn_hg_mailchimp' );
	}

	/**
	 * This method is used to retrieve the configurable options of the element.
	 * @return array The list of options that compose the element and then passed as the argument for the render() function
	 */
	function options()
	{
		$uid = $this->data['uid'];
		$mail_lists = array (''=>__('Select List ID','hogash-mailchimp'));

		$mcApiKey = zget_option( 'mailchimp_api', 'general_options' );
		$double_opt_in = zget_option( 'mailchimp_double_opt_in', 'general_options', false, 'no' );

		if ( ! empty( $mcApiKey ) ) {

			Hg_Mailchimp::loadHgMcApiClass();
			$mcapi   = new HG_MCAPI( $mcApiKey, array(
				'opt_in' => $double_opt_in === 'yes' ? true : false
			));
			$lists   = $mcapi->getLists();

			if ( ! empty( $lists['lists'] ) ) {
				foreach ( $lists['lists'] as $key => $value ) {
					$mail_lists[ $value['id'] ] = $value['name'];
				}
			}
		}

		$defaultStyles = array (
			'normal'  => __( 'White input and filled button', 'hogash-mailchimp' ),
			'normal2' => __( 'White input and transparent button', 'hogash-mailchimp' ),
			'transparent'  => __( 'Transparent input and filled button', 'hogash-mailchimp' ),
			'transparent2' => __( 'Transparent input and transparent button', 'hogash-mailchimp' ),
			'lined_light'  => __( 'White Lined Input + filled button', 'hogash-mailchimp' ),
			'lined_dark'  => __( 'Dark Lined Input + filled button', 'hogash-mailchimp' ),
		);
		$defaultStyles = apply_filters( 'hg_mailchimp_pb_element_styles', $defaultStyles );

		return array (
			'has_tabs'  => true,
			'general' => array(
				'title' => 'General options',
				'options' => array(

					array (
						"name"        => __( "Mailchimp List ID", 'hogash-mailchimp' ),
						"description" => sprintf(__( 'Please enter your Mailchimp list id. In order to make Mailchimp work, you should also add your Mailchimp API key in the theme\'s admin page. <br><br><span class="dashicons dashicons-share-alt2 u-v-mid"></span> <a href="%s" target="_blank">Access Mailchimp options in %s Options.</a>', 'hogash-mailchimp' ), admin_url('admin.php?page=zn_tp_general_options#mailchimp_options'), ZNHGTFW()->getThemeName() ),
						"id"          => "mlid",
						"std"         => "",
						"type"        => "select",
						'options'     => $mail_lists,
					),

					array (
						"name"        => __( "Email field placeholder", 'hogash-mailchimp' ),
						"description" => __( "Please add the placeholder for the email field", 'hogash-mailchimp' ),
						"id"          => "email_pl",
						"std"         => "your.address@email.com",
						"type"        => "text",
					),

					array (
						"name"        => __( "Button text type", 'hogash-mailchimp' ),
						"description" => __( "Choose the button text or icon", 'hogash-mailchimp' ),
						"id"          => "btn_type",
						"std"         => "text",
						"type"        => "select",
						"options"     => array (
							'icon'  => __( 'Icon', 'hogash-mailchimp' ),
							'text' => __( 'Custom text', 'hogash-mailchimp' )
						)
					),

					array (
						"name"        => __( "Submit field placeholder", 'hogash-mailchimp' ),
						"description" => __( "Please add the placeholder for the submit button", 'hogash-mailchimp' ),
						"id"          => "btn_text",
						"std"         => "JOIN US",
						"type"        => "text",
						"dependency"  => array( 'element' => 'btn_type' , 'value'=> array('text') )
					),

					array (
						"name"        => __( "Select Icon for Submit button", 'hogash-mailchimp' ),
						"description" => __( "Select an icon to display.", 'hogash-mailchimp' ),
						"id"          => "btn_icon",
						"std"         => "",
						"type"        => "icon_list",
						"dependency"  => array( 'element' => 'btn_type' , 'value'=> array('icon') ),
					),

				),
			),

			'style' => array(
				'title' => 'Style Options',
				'options' => array(

					array (
						"name"        => __( "Form Style", 'hogash-mailchimp' ),
						"description" => __( "Choose a style", 'hogash-mailchimp' ),
						"id"          => "style",
						"std"         => "normal",
						"type"        => "select",
						"options"     => $defaultStyles,
						'live' => array(
						   'type'        => 'class',
						   'css_class' => '.'.$uid,
						   'val_prepend'   => 'zn-mcNl zn-mcNl--style-',
						)
					),

					array (
						"name"        => __( "Form Layout", 'hogash-mailchimp' ),
						"description" => __( "Choose a form field layout", 'hogash-mailchimp' ),
						"id"          => "layout",
						"std"         => "single",
						"type"        => "select",
						"options"     => array (
							'single'  => __( 'Single Block', 'hogash-mailchimp' ),
							'separate' => __( 'Separately with a distance between fields.', 'hogash-mailchimp' ),
							'rows'  => __( 'On separate rows', 'hogash-mailchimp' ),
							'rows-full'  => __( 'On separate rows full ', 'hogash-mailchimp' ),
						),
						'live' => array(
						   'type'        => 'class',
						   'css_class' => '.'.$uid,
						   'val_prepend'   => 'zn-mcNl zn-mcNl--layout-',
						)
					),

					array (
						"name"        => __( "Add Shadow", 'hogash-mailchimp' ),
						"description" => __( "Add shadow to the form.", 'hogash-mailchimp' ),
						"id"          => "shadow",
						"std"         => "zn-mcNl--shadow",
						"value"       => "zn-mcNl--shadow",
						"type"        => "toggle2",
						"dependency"  => array( 'element' => 'layout' , 'value'=> array('single') ),
						'live'        => array(
							'type'    => 'class',
							'css_class' => '.'.$uid,
						),
					),

					array (
						"name"        => __( "Form height", 'hogash-mailchimp' ),
						"description" => __( "Specify the form height", 'hogash-mailchimp' ),
						"id"          => "form_height",
						"std"         => "55",
						'type'        => 'slider',
						'helpers'     => array(
							'min' => '20',
							'max' => '100',
							'step' => '1'
						),
						'live' => array(
							'multiple' => array(
								array(
									'type'        => 'css',
									'css_class' => '.'.$uid.' .zn-mcNl-submit',
									'css_rule'  => 'height',
									'unit'      => 'px'
								),
								array(
									'type'        => 'css',
									'css_class' => '.'.$uid.' .zn-mcNl-input',
									'css_rule'  => 'height',
									'unit'      => 'px'
								),
							)
						)
					),

					array (
						"name"        => __( "Input & Button Corners", 'hogash-mailchimp' ),
						"description" => __( "Select the input and button corners type", 'hogash-mailchimp' ),
						"id"          => "corners",
						"std"         => "square",
						"type"        => "select",
						"options"     => array (
							'rounded'  => __( "Smooth rounded corner", 'hogash-mailchimp' ),
							'round'    => __( "Round corners", 'hogash-mailchimp' ),
							'square'   => __( "Square corners", 'hogash-mailchimp' ),
						),
						'live' => array(
							'type'           => 'class',
							'css_class'      => '.'.$uid,
							'val_prepend'	 => 'zn-mcNl--radius-'
						),
					),

				),
			),

			'button' => array(
				'title' => 'Button options',
				'options' => array(

					array (
						"name"        => __( "Button Color", 'hogash-mailchimp' ),
						"description" => __( "Choose the button's color", 'hogash-mailchimp' ),
						"id"          => "btn_color",
						"std"         => "",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'style' , 'value'=> array('normal','transparent', 'lined_light', 'lined_dark') ),
						'live' => array(
							'type'        => 'css',
							'css_class' => '.'.$uid.' .zn-mcNl-submit',
							'css_rule'  => 'background-color',
							'unit'      => ''
						)
					),

					array (
						"name"        => __( "Button Hover Color", 'hogash-mailchimp' ),
						"description" => __( "Choose the button's hover color", 'hogash-mailchimp' ),
						"id"          => "btn_color_hov",
						"std"         => "#000000",
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'style' , 'value'=> array('normal','transparent', 'lined_light', 'lined_dark') )
					),

					array (
						"name"        => __( "Font Size", 'hogash-mailchimp' ),
						"description" => __( "Select the size of the button text or icon.", 'hogash-mailchimp' ),
						"id"          => "font_size",
						"std"         => "16",
						'type'        => 'slider',
						// 'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '10',
							'max' => '48',
							'step' => '1'
						),
						'live' => array(
							'type'        => 'css',
							'css_class' => '.'.$uid.' .zn-mcNl-submit',
							'css_rule'  => 'font-size',
							'unit'      => 'px'
						)
					),

					array (
						"name"        => __( "Button width (px)", 'hogash-mailchimp' ),
						"description" => __( "Add a button width", 'hogash-mailchimp' ),
						"id"          => "btn_width",
						"std"         => "130",
						"type"        => "slider",
						// 'class'       => 'zn_full',
						'helpers'     => array(
							'min' => '20',
							'max' => '1000',
							'step' => '5'
						),
					),

				)
			),

			'help' => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#O03njJEtSNQ',
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			)),

		);
	}


	/**
	 * This method is used to display the output of the element.
	 *
	 * @return void
	 */
	function element()
	{
		$options = $this->data['options'];
		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));

		//#! Add classes
		array_push( $classes, $uid, zn_get_element_classes($options), 'zn-mcNl', 'zn-mcNl--style-'.$this->opt('style', 'normal'));

		// Layout
		$layout = $this->opt('layout', 'single');
		$classes[] = 'zn-mcNl--layout-'.$layout;

		// Shadow for single layout
		if($layout == 'single'){
			$classes[] = $this->opt('shadow', 'zn-mcNl--shadow');
		}
		// Corners
		$classes[] = 'zn-mcNl--radius-'.$this->opt('corners', 'rounded');

		echo '<div class="'.zn_join_spaces($classes).'" '. zn_join_spaces($attributes ) .'>';

			$nl_id = $this->opt('mlid','');
			$btn_type = $this->opt('btn_type','text');

			if ( !empty ( $nl_id ) ) { ?>

				<div class="zn-mcNl-result js-mcForm-result"></div>

				<form method="post" class="js-mcForm zn-mcNl-form clearfix" data-url="<?php echo esc_url(home_url('/')); ?>" name="newsletter_form">
					<input type="email" name="mc_email" class="zn-mcNl-input js-mcForm-email form-control"
                           value="" placeholder="<?php echo esc_attr($this->opt('email_pl')); ?>" required="required" />
					<button type="submit" name="submit" class="zn-mcNl-submit zn-mcNl-submit--<?php echo esc_attr($btn_type); ?>">
						<?php
							if( $btn_type == 'text' ){
								echo $this->opt('btn_text','JOIN US');
							}
							else {
								echo '<span class="zn-mcNl-icon" '. zn_generate_icon( $this->opt('btn_icon') ) .'></span>';
							}
						?>
					</button>
					<input type="hidden" name="mailchimp_list" class="nl-lid" value="<?php echo esc_attr($nl_id); ?>" />
					<input type="hidden" name="action" value="hg_mailchimp_register" />
					<?php

					// Add consent boxes
					Hg_Mailchimp_GDPR::getConsentMarkup();

					/*
					 * The form is validated through AJAX.
					 * @see js/znscript.js
					 */
					echo '<input type="hidden" name="nonce" value="'.esc_attr($this->_nonce).'" class="zn_hg_mailchimp"/>';
					?>
				</form>
			<?php }
		echo '</div>';
	}


	/**
	 * Output the inline css to head or after the element in case it is loaded via ajax
	 */
	function css(){
		$css = '';
		$uid = $this->data['uid'];
		$btn_css = '';

		// width of the button
		$width = (int) $this->opt('btn_width', '130');
		if( $width != 130 ){
			$btn_css .= "width:". $width."px;";
			$css .= '.'.$uid.'.zn-mcNl--layout-separate .zn-mcNl-input {width:calc(100% - '. ($width + 10).'px);}';
			$css .= '.'.$uid.'.zn-mcNl--layout-single .zn-mcNl-input {width:calc(100% - '. $width.'px);}';
		}
		// height of the form
		$height = (int) $this->opt('form_height', '55');
		if( $height != 55 ) {
			$btn_css .= "height:". $height ."px;";
			$css .= ".$uid .zn-mcNl-input {height:". $height ."px}";
		}

		$btn_color = $this->opt('btn_color','');
		if( $btn_color != '' ){
			$btn_css .= "background-color:". $btn_color.";";
		}

		if($font_size = $this->opt('font_size', '16')){
			$btn_css .= "font-size:". $font_size."px;";
		}

		if(!empty($btn_css)){
			$css .= ".$uid .zn-mcNl-submit{". $btn_css.";}";
		}

		$btn_color_hov = $this->opt('btn_color_hov','#000000');
		if( $btn_color_hov != '#000000' ){
			$css .= ".$uid .zn-mcNl-submit:hover {background-color:". $btn_color_hov.";}";
		}

		return $css;
	}
}
