<?php if ( ! defined( 'ABSPATH' ) ) { return; }

class ZNB_ContactForm extends ZionElement {

	// Will allow multiple forms on a single page. It will be incremented on each form created
	static $form_id = 1;

	var $submit = true;

	var $form_fields;
	var $error_messages = array();

	/**
	 * Holds the result of wp_mail function call
	 * @see   form_send()
	 * @var bool
	 */
	private $_emailSent = false;
	/**
	 * Whether or not the form was submitted
	 * @var bool
	 */
	private $_formSubmitted = false;

	/**
	 * Element's options
	 * @return array
	 */
	function options() {
		$uid = $this->data['uid'];

		$datepicker_langs = array(
			''      => __('-- Default (English) --','zn_framework'),
			'af'    => __('Afrikaans','zn_framework'),
			'ar-DZ' => __('Algerian Arabic','zn_framework'),
			'ar'    => __('Arabic','zn_framework'),
			'az'    => __('Azerbaijani','zn_framework'),
			'be'    => __('Belarusian','zn_framework'),
			'bg'    => __('Bulgarian','zn_framework'),
			'bs'    => __('Bosnian','zn_framework'),
			'ca'    => __('Catal&#224;','zn_framework'),
			'cs'    => __('Czech','zn_framework'),
			'cy-GB' => __('Welsh/UK','zn_framework'),
			'da'    => __('Danish','zn_framework'),
			'de'    => __('German','zn_framework'),
			'el'    => __('Greek','zn_framework'),
			'en-AU' => __('English/Australia','zn_framework'),
			'en-GB' => __('English/UK','zn_framework'),
			'en-NZ' => __('English/New Zealand','zn_framework'),
			'eo'    => __('Esperanto','zn_framework'),
			'es'    => __('Espa&#241;ol','zn_framework'),
			'et'    => __('Estonian','zn_framework'),
			'eu'    => __('Basque','zn_framework'),
			'fa'    => __('Persian (Farsi)','zn_framework'),
			'fi'    => __('Finnish','zn_framework'),
			'fo'    => __('Faroese','zn_framework'),
			'fr-CA' => __('Canadian-French','zn_framework'),
			'fr-CH' => __('Swiss-French','zn_framework'),
			'fr'    => __('French','zn_framework'),
			'gl'    => __('Galician','zn_framework'),
			'he'    => __('Hebrew','zn_framework'),
			'hi'    => __('Hindi','zn_framework'),
			'hr'    => __('Croatian','zn_framework'),
			'hu'    => __('Hungarian','zn_framework'),
			'hy'    => __('Armenian','zn_framework'),
			'id'    => __('Indonesian','zn_framework'),
			'is'    => __('Icelandic','zn_framework'),
			'it'    => __('Italian','zn_framework'),
			'ja'    => __('Japanese','zn_framework'),
			'ka'    => __('Georgian','zn_framework'),
			'kk'    => __('Kazakh','zn_framework'),
			'km'    => __('Khmer','zn_framework'),
			'ko'    => __('Korean','zn_framework'),
			'ky'    => __('Kyrgyz','zn_framework'),
			'lb'    => __('Luxembourgish','zn_framework'),
			'lt'    => __('Lithuanian','zn_framework'),
			'lv'    => __('Latvian','zn_framework'),
			'mk'    => __('Macedonian','zn_framework'),
			'ml'    => __('Malayalam','zn_framework'),
			'ms'    => __('Malaysian','zn_framework'),
			'nb'    => __('Norwegian Bokm&#229;l','zn_framework'),
			'nl-BE' => __('Dutch (Belgium)','zn_framework'),
			'nl'    => __('Dutch','zn_framework'),
			'nn'    => __('Norwegian Nynorsk','zn_framework'),
			'no'    => __('Norwegian','zn_framework'),
			'pl'    => __('Polish','zn_framework'),
			'pt-BR' => __('Brazilian','zn_framework'),
			'pt'    => __('Portuguese','zn_framework'),
			'rm'    => __('Romansh','zn_framework'),
			'ro'    => __('Romanian','zn_framework'),
			'ru'    => __('Russian','zn_framework'),
			'sk'    => __('Slovak','zn_framework'),
			'sl'    => __('Slovenian','zn_framework'),
			'sq'    => __('Albanian','zn_framework'),
			'sr-SR' => __('Serbian','zn_framework'),
			'sr'    => __('Serbian 2','zn_framework'),
			'sv'    => __('Swedish','zn_framework'),
			'ta'    => __('Tamil','zn_framework'),
			'th'    => __('Thai','zn_framework'),
			'tj'    => __('Tajiki','zn_framework'),
			'tr'    => __('Turkish','zn_framework'),
			'uk'    => __('Ukrainian','zn_framework'),
			'vi'    => __('Vietnamese','zn_framework'),
			'zh-CN' => __('Chinese','zn_framework'),
			'zh-HK' => __('Chinese 2','zn_framework'),
			'zh-TW' => __('Chinese 3','zn_framework'),
		);

		$date_info = 'http://api.jqueryui.com/datepicker/#utility-formatDate';
		$recaptcha_language = esc_url( 'https://developers.google.com/recaptcha/docs/language' );

		$options = array(
			'has_tabs' => true,
			'general'  => array(
				'title'   => 'General options',
				'options' => array(

					array(
						'id'          => 'email',
						'name'        => __('Recepient(s) email address','zn_framework'),
						'description' => __('Please enter the email address where you want the form submissions to be sent. Note that you can enter multiple recipients separated by comma(,).','zn_framework'),
						'type'        => 'text',
						'placeholder' => 'eg: your.email@yourdomain.com',
						"class"       => "zn_input_xl",
						"std"       => get_option( 'admin_email' ),
					),

					array (
						"name"        => __( "From (Email Sender)", 'zn_framework' ),
						"description" => __( "It's highly recommended to use an email containing this domain name. It may not work otherwise for some hostings. <br><br> The <strong>'Reply-To'</strong> address is the field you validate as 'Value is Email' eg: <a href='http://hogash.d.pr/3OVS' target='_blank'>http://hogash.d.pr/3OVS</a> .", 'zn_framework' ),
						"id"          => "from_sender",
						"type"        => "text",
						"class"       => "zn_input_xl",
						"std"			=> $this->from_email(),
						// "std"			=> sprintf( '%s <%s>', get_bloginfo( 'name' ), $this->from_email() )
					),

					array(
						'id'          => 'email_subject',
						'name'        => __('Email subject text','zn_framework'),
						'description' => __('Please enter your desired text that will appear as the subject of the received email. <strong>Note that you can dynamically set the value of this field by specifying the dynamic field in the fields list.</strong>','zn_framework'),
						'std'         => __('New form submission','zn_framework'),
						'type'        => 'text',
						"class"       => "zn_input_xl",
					),
					array(
						'id'          => 'sent_message',
						'name'        => __('Mail sent message','zn_framework'),
						'description' => __('Please enter your desired text that will appear after the form is successfully sent','zn_framework'),
						'std'         => __('Thank you for contacting us','zn_framework'),
						'type'        => 'text',
						"class"       => "zn_input_xl",
					),
					array(
						'id'          => 'redirect_url',
						'name'        => __('Redirect url','zn_framework'),
						'description' => __('Using this option you can redirect the user after the form is succesfully submitted','zn_framework'),
						'std'         => '',
						'placeholder' => 'http://hogash.com',
						'type'        => 'text',
						"class"       => "zn_input_xl",
					),

					array(
						'id'          => 'captcha',
						'name'        => __('Show Captcha','zn_framework'),
						'description' => __('Select yes if you want to add a Xaptcha field.','zn_framework'),
						'type'        => 'zn_radio',
						'std'         => 'yes',
						'options'     => array( 'yes' => __('Yes','zn_framework'), 'no' => __('No','zn_framework') ),
						"class"        => "zn_radio--yesno",
					),

					array(
						'id'          => 'captcha_lang',
						'name'        => __('Captcha language','zn_framework'),
						'description' => sprintf( __( 'Enter the desired Captcha language code ( <a href="%s" target="_blank">you can get it from here</a> )', 'zn_framework' ), $recaptcha_language),
						'type'        => 'text',
						'placeholder' => 'en',
						'dependency'  => array( 'element' => 'captcha', 'value' => array( 'yes' ) )
					),


				),
			),

			'fields'   => array(
				'title'   => 'Fields',
				'options' => array(
					array(
						'id'            => 'fields',
						'name'          => __('Add your own, custom fields:','zn_framework'),
						'description'   => __('Here you can create your contact form fields','zn_framework'),
						'type'          => 'group',
						'sortable'      => true,
						'element_title' => 'name',
						'subelements'   => array(
							array(
								'id'          => 'name',
								'name'        => __('Field name','zn_framework'),
								'description' => __('Please enter a name for this field','zn_framework'),
								'type'        => 'text',
							),
							array(
								'id'          => 'type',
								'name'        => __('Field type','zn_framework'),
								'description' => __('Please select the field type','zn_framework'),
								'type'        => 'select',
								'options'     => array(
									'text'       => __('Text','zn_framework'),
									'textarea'   => __('Textarea','zn_framework'),
									'select'     => __('Select','zn_framework'),
									'radio'      => __('Radio','zn_framework'),
									'checkbox'   => __('Checkbox','zn_framework'),
									'plain_text' => __('Plain text (accepts HTML)','zn_framework'),
									'datepicker' => __('Datepicker & TimePicker','zn_framework'),
								)
							),
							array(
								'id'          => 'validation',
								'name'        => __('Field validation','zn_framework'),
								'description' => __('Please select the field validation','zn_framework'),
								'type'        => 'select',
								'std'         => 'not_empty',
								'options'     => array(
									'none'      => __('No validation','zn_framework'),
									'not_empty' => __('Value not empty','zn_framework'),
									'is_email'  => __('Value is Email','zn_framework'),
									'is_numeric'  => __('Value is numeric','zn_framework')
								),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array(
										'text',
										'textarea',
										'select',
										'checkbox',
										'datepicker'
									)
								)
							),
							array(
								'id'          => 'select_option',
								'name'        => __('Select/Radio option','zn_framework'),
								'description' => __('Please add your values for the select/radio option type in the following format : value:option name, value2:option name 2. For example "house:House, car:Car, piano:Piano"','zn_framework'),
								'type'        => 'textarea',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'select', 'radio' )
								)
							),
							array(
								'id'          => 'select_multiple',
								'name'        => __('Allow multiple select?','zn_framework'),
								'description' => __('Enable if you want the the user to select multiple options.','zn_framework'),
								'class'        => 'zn_radio--yesno',
								'type'        => 'zn_radio',
								'std'        => 'no',
								'options'        => array(
									'yes' => __( "Yes", 'zn_framework' ),
									'no' => __( "No", 'zn_framework' ),
								),
								'dependency'  => array('element' => 'type', 'value'   => array( 'select' ) )
							),
							array(
								'id'          => 'radio_inline',
								'name'        => __('Radio Options Inline?','zn_framework'),
								'description' => __('Enable if you want the radio options to appear inline, rather than blocks.','zn_framework'),
								'class'        => 'zn_radio--yesno',
								'type'        => 'zn_radio',
								'std'        => 'no',
								'options'        => array(
									'yes' => __( "Yes", 'zn_framework' ),
									'no' => __( "No", 'zn_framework' ),
								),
								'dependency'  => array('element' => 'type', 'value'   => array( 'radio' ) )
							),

							array(
								'id'          => 'placeholder',
								'name'        => __('Placeholder','zn_framework'),
								'description' => __('Please enter the placeholder value for this field','zn_framework'),
								'type'        => 'text',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'text', 'textarea', 'datepicker' )
								)
							),
							array(
								'id'          => 'datepicker_lang',
								'name'        => __('Datepicker Language','zn_framework'),
								'description' => __('Please select a datepicker language if needed. Needs page refresh!','zn_framework'),
								'std'         => '',
								'type'        => 'select',
								'options'     => $datepicker_langs,
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' )
								)
							),
							array (
								"name"           => __( "Date format", 'zn_framework' ),
								"description"    => __( "Enter your desired date format. More info about formatting the date can be found", 'zn_framework' ) .' <a target="_blank" href="'.$date_info.'">'.__('here', 'zn_framework').'</a>',
								"id"             => "date_format",
								"type"           => "text",
								"std"            => 'yy-mm-dd',
								"placeholdder"   => 'yy-mm-dd',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' )
								)
							),
							array(
								'id'          => 'time_picker',
								'name'        => __('Enable TimePicker?','zn_framework'),
								'description' => __('Do you want this datepicker field to display a time picker field as well?','zn_framework'),
								'type'        => 'toggle2',
								'std'         => '',
								'value'       => 'yes',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' )
								)
							),
							array(
								'id'          => 'tpicker_label',
								'name'        => __('Timepicker Label text','zn_framework'),
								'description' => __('Please add the label value for the timepicker field','zn_framework'),
								'type'        => 'text',
								'std'         => __('PICK TIME','zn_framework'),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' )
								)
							),
							array(
								'id'          => 'width',
								'name'        => __('Field width','zn_framework'),
								'description' => __('Please select the field width','zn_framework'),
								'type'        => 'select',
								'options'     => array(
									'col-sm-12' => __('Full width','zn_framework'),
									'col-sm-9'  => __('Three-Quarters width','zn_framework'),
									'col-sm-8'  => __('Two-Thirds width','zn_framework'),
									'col-sm-6'  => __('Half width','zn_framework'),
									'col-sm-4'  => __('One-Third width','zn_framework'),
									'col-sm-3'  => __('One-Quarter width','zn_framework'),
									'col-sm-2'  => __('One-Sixth width','zn_framework'),
								)
							),
							array(
								'id'          => 'is_dynamic_subject',
								'name'        => __('Use as dynamic email subject?', 'zn_framework'),
								'description' => __('Enable if this field is if you want to set the value of this field as the email subject.', 'zn_framework'),
								'type'        => 'zn_radio',
								'std'		  => 'no',
								'options'	  => array( 'yes' => __('Yes', 'zn_framework'), 'no' => __('No', 'zn_framework') ),
								"class" => 'zn_radio--yesno'
							),

						)
					)
				)
			),
			'style'    => array(
				'title'   => __('Style Options', 'zn_framework'),
				'options' => array(

					array(
						'id'          => 'title1',
						'name'        => __('Form Options','zn_framework'),
						'description' => __('These are options to customize the form inputs.','zn_framework'),
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					array(
						'id'          => 'cf_labels_pl',
						'name'        => __('Labels or Placeholders?','zn_framework'),
						'description' => __('Choose what to display, ','zn_framework'),
						'type'        => 'select',
						'std'         => ( $this->opt( 'cf_labels', 'yes' ) === 'yes' ? '1' : '4' ),
						'options'     => array( '1' => __('Labels','zn_framework'), '2' => __('Placeholders','zn_framework'), '3' => __('Both','zn_framework'), '4' => __('None','zn_framework') )
					),

					array(
						'id'          => 'gutter_size',
						'name'        => __('Gutter Size (Distance)', 'zn_framework'),
						'description' => __('Select the gutter distance between inputs', 'zn_framework'),
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							'' => __( 'Default (20px)', 'zn_framework' ),
							'gutter-xs' => __( 'Extra Small (5px)', 'zn_framework' ),
							'gutter-sm' => __( 'Small (15px)', 'zn_framework' ),
							'gutter-md' => __( 'Medium (30px)', 'zn_framework' ),
							'gutter-lg' => __( 'Large (50px)', 'zn_framework' ),
							'gutter-0' => __( 'No distance - 0px', 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.'.$uid.' .zn-contactForm-row'
						)
					),

					array(
						'id'          => 'title2',
						'name'        => __('Send Button Options','zn_framework'),
						'description' => __('These are options to customize the send button style.','zn_framework'),
						'type'        => 'zn_title',
						'class'        => 'zn_full zn-custom-title-large',
					),

					array(
						'id'          => 'submit_label',
						'name'        => __('Submit button label','zn_framework'),
						'description' => __('Enter a text for the submit button label.','zn_framework'),
						'std'         => __('Send message','zn_framework'),
						'type'        => 'text',
						"class"       => "zn_input_md",
					),

					array(
						"name"        => __( "Send button style", 'zn_framework' ),
						"description" => __( "Select a style for the button", 'zn_framework' ),
						"id"          => "button_style",
						"std"         => "btn-primary",
						"type"        => "smart_select",
						"options"     => znb_get_button_option_styles(),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .zn-formSubmit',
						),
					),

					array (
						"name"        => __( "Button Width", 'zn_framework' ),
						"description" => __( "Select a size for the button", 'zn_framework' ),
						"id"          => "button_width",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''                          => __( "Default", 'zn_framework' ),
							'btn-block btn-fullwidth'   => __( "Full width (100%)", 'zn_framework' ),
							'btn-halfwidth'             => __( "Half width (50%)", 'zn_framework' ),
							'btn-third'                 => __( "One-Third width (33%)", 'zn_framework' ),
							'btn-forth'                 => __( "One-forth width (25%)", 'zn_framework' ),
						),
						'live' => array(
							'type'           => 'class',
							'css_class'      => '.'.$uid.' .zn-formSubmit',
						),
					),

					array (
						"name"        => __( "Size", 'zn_framework' ),
						"description" => __( "Select a size for the button", 'zn_framework' ),
						"id"          => "button_size",
						"std"         => "",
						"type"        => "select",
						"options"     => array (
							''          => __( "Default", 'zn_framework' ),
							'btn-lg'    => __( "Large", 'zn_framework' ),
							'btn-md'    => __( "Medium", 'zn_framework' ),
							'btn-sm'    => __( "Small", 'zn_framework' ),
							'btn-xs'    => __( "Extra small", 'zn_framework' ),
						),
						'live' => array(
							'type'           => 'class',
							'css_class'      => '.'.$uid.' .zn-formSubmit',
						),
					),

					array (
						"name"        => __( "Button text Options", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the button.", 'zn_framework' ),
						"id"          => "button_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'weight', 'spacing', 'case' ),
						"type"        => "font",
						'live' => array(
							'type'      => 'font',
							'css_class' => '.'.$uid . ' .zn-formSubmit',
						),
					),

					array (
						"name"        => __( "Button Corners", 'zn_framework' ),
						"description" => __( "Select the button corners type for this button", 'zn_framework' ),
						"id"          => "button_corners",
						"std"         => "btn--square",
						"type"        => "select",
						"options"     => array (
							'btn--rounded'  => __( "Smooth rounded corner", 'zn_framework' ),
							'btn--round'    => __( "Round corners", 'zn_framework' ),
							'btn--square'   => __( "Square corners", 'zn_framework' ),
						),
						'live' => array(
							'type'           => 'class',
							'css_class'      => '.'.$uid . ' .zn-formSubmit',
						),
					),

					array (
						"name"        => __( "Button Alignment", 'zn_framework' ),
						"description" => __( "Select the alignment", 'zn_framework' ),
						"id"          => "btn_alignment",
						"std"         => "left",
						"type"        => "select",
						"options"     => array(
							"left" => __("Left",'zn_framework'),
							"center" => __("Center",'zn_framework'),
							"right" => __("Right",'zn_framework'),
						),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.'.$uid . ' .zn-formItem--submit',
							'val_prepend'  => 'text-',
						)
					),

					array (
						"name"        => __( "Labels Text Options", 'zn_framework' ),
						"description" => __( "Specify the typography properties for the labels.", 'zn_framework' ),
						"id"          => "labels_typo",
						"std"         => '',
						'supports'   => array( 'size', 'font', 'style', 'line', 'weight', 'spacing', 'case', 'mb' ),
						"type"        => "font",
						'live' => array(
							'type'      => 'font',
							'css_class' => '.'.$uid.' .zn-formItem-label',
						),
					),
				),
			),

			'advanced'   => array(
				'title'   => 'Advanced',
				'options' => array(

					array(
						'id'          => 'send_me_copy',
						'name'        => __('Show send me a copy','zn_framework'),
						'description' => __('Select yes if you want to allow users to send a copy of the message to themselves. This requires you to set the email field to have the "is email" validation type.','zn_framework'),
						'type'        => 'zn_radio',
						'std'         => 'no',
						'options'     => array( 'yes' => __('Yes','zn_framework'), 'no' => __('No','zn_framework')  ),
						"class"        => "zn_radio--yesno",
					),
					array(
						'id'          => 'cf_debug',
						'name'        => __('Enable debugging?','zn_framework'),
						'description' => __('If you have problems with the contact form, this option will help debug the problem by showing some errors into the response field.','zn_framework'),
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => '1',
					),

				),
			),

			'help'     => znpb_get_helptab( array(
				// 'video'   => 'https://my.hogash.com/video_category/',
				'docs'    => sprintf( '%s', esc_url('https://my.hogash.com/documentation/contact-form/') ),
				'copy'    => $uid,
				'general' => true,
				'custom_id' => true,
			) ),
		);

		return $options;
	}

	function show_labels(){
		//#! Backwards compatibility
		if( $this->opt( 'cf_labels', 'yes' ) == 'yes' ){
			return true;
		}
		$opt = $this->opt( 'cf_labels_pl', '1' );
		return in_array( $opt, array( '1', '3' ) );
	}

	function show_placeholder() {
		//#! Backwards compatibility
		if( $this->opt( 'cf_labels', 'yes' ) == 'yes' ){
			return false;
		}
		$opt = $this->opt( 'cf_labels_pl', '2' );
		return in_array( $opt, array( '2', '3' ) );
	}

	function get_label_style(){
		return $this->opt( 'cf_labels_pl', '1' );
	}

	function element() {

		$options = $this->data['options'];

		$fields = $this->opt( 'fields', '' );

		if ( empty( $fields ) ) {
			echo '<div class="zn-pb-notification">'. __('Please configure the element options and add your contact fields.','zn_framework') .'</div>';
			return;
		}

		$classes = $attributes = array();
		$uid = $this->data['uid'];

		$classes[] = $uid;
		$classes[] = zn_get_element_classes($options);
		$classes[] = 'zn-contactFormElement';

		$attributes[] = zn_get_element_attributes($options, $this->opt('custom_id', $uid));
		$attributes[] = 'class="'.zn_join_spaces($classes).'"';

		echo '<div '. zn_join_spaces($attributes ) .'>';

		$redirect_url = $this->opt( 'redirect_url', false );
		$debugEnabled = $this->_inDebugMode();

		echo '<form action="#" id="form_'.$uid.'" method="post" class="zn-contactForm" data-redirect="' . esc_attr( esc_url( $redirect_url ) ) . '">';

		echo '<div class="row zn-contactForm-row '.$this->opt('gutter_size','').'">';

		if ( $debugEnabled ) {
			echo '<div class="col-sm-12">';
			echo '<div class="zn-formItem-wrapper zn-formItem--debug">';
			echo __('<strong>DEBUG IS ENABLED!</strong> Debug mode is only for development mode and shouldn\'t be enabled in production mode.','zn_framework');
			echo '</div>';
			echo '</div>';
		}

		if ( $this->opt( 'captcha', 'no' ) == 'yes' ) {
			$fields[] = array(
				'name'       => 'zn_captcha',
				'type'       => 'captcha',
				'validation' => 'captcha',
				'width'      => 'col-sm-12'
			);
		}

		$fields[] = array(
			'name'       => 'zn_pb_form_submit_' . self::$form_id,
			'validation' => 'none',
			'value'      => 1,
			'type'       => 'hidden',
			'width'      => 'col-sm-12'
		);

		$this->form_fields = $fields;

		/**
		 * Display Fields
		 */
		$this->create_form_elements();

		/**
		 * Display Send Copy Field
		 */
		if ( $this->opt( 'send_me_copy', 'no' ) == 'yes' ) {

			$cid = 'send_me_copy_' . $this->data['uid'];

			echo '<div class="col-sm-12">';
			echo '<div class="zn-formItem-wrapper zn-formItem--checkbox">';

			echo sprintf('<input type="checkbox" value="yes" name="%s" id="%s" class="zn-formItem-field zn-formItem-field--checkbox " />', $cid, $cid );

			if ( $this->show_labels() ) {
				echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $cid, __( 'Send me a copy', 'zn_framework' ) );
			}

			echo '</div>';
			echo '</div>';
		}

		echo '<div class="col-sm-12">';

		$response = '';

		if ( isset( $_POST[ 'zn_pb_form_submit_' . self::$form_id ] ) )
		{
			if ( ! empty( $this->error_messages ) )
			{
				$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
				foreach ( $this->error_messages as $key => $value ) {
					$response .= $value;
				}
				$response .= '</div>';
			}
			else
			{
				$result = $this->submit ? $this->form_send() : '';

				// This way, $result will always be a boolean value
				$emailSent = ( $debugEnabled ? $this->_emailSent : $result );

				if ( $this->submit && $emailSent ) {
					$response = '<div class="alert alert-success alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $this->opt( 'sent_message', __( 'New Contact Form submission', 'zn_framework' ) );
					if ( $debugEnabled ) {
						$response .= '<pre>1</pre>';
					}
					$response .= '</div>';
				}
				else {
					$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
					$response .= __( 'There was a problem submitting your message. Please try again.', 'zn_framework' );
					if ( $debugEnabled ) {
						// Here, the $result will contain the error message(s)
						$response .= '<pre>' . $this->form_send() . '</pre>';
					}
					$response .= '</div>';
				}
			}
		}
		else {
			// Only display the message when form was submitted. Prevents the element from sending the email
			// when debug mode active
			if($this->_formSubmitted) {
				$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
				$response .= __( 'There was a problem submitting your message. Please try again.', 'zn_framework' );
				if ( $debugEnabled ) {
					$response .= '<pre>' . $this->form_send() . '</pre>';
				}
				$response .= '</div>';
			}
		}

		// Response Container
		echo '<div class="zn-formItem-wrapper zn-formItem--response">';
		echo '<div class="zn_contact_ajax_response" id="zn_form_id' . self::$form_id . '" >' . $response . '</div>';
		echo '</div>';


		// Button Container
		$btn_classes[] = $this->opt( 'button_style', 'btn-primary' );
		$btn_classes[] = $this->opt( 'button_width', '' );
		$btn_classes[] = $this->opt( 'button_size', '' );
		$btn_classes[] = $this->opt( 'button_corners','btn--square' );

		echo '<div class="zn-formItem-wrapper zn-formItem--submit text-'.$this->opt( 'btn_alignment', 'left' ) .'">';
		echo '<button class="zn-formSubmit btn ' . zn_join_spaces($btn_classes) . '" type="submit">' . $this->opt( 'submit_label', __('Send message', 'zn_framework') ) . '</button>';
		echo '</div>';

		echo '</div>'; // END of .col-sm-12

		echo '</div>'; // .row
		echo '</form>';
		echo '</div>';

		self::$form_id ++;
	}

	function scripts() {
		if ( $this->opt( 'captcha', 'no' ) == 'yes' ) {
			$captcha_lang = $this->opt( 'captcha_lang' ) ? '&hl=' . $this->opt( 'captcha_lang' ) : '';
			wp_enqueue_script( 'znb_recaptcha', 'https://www.google.com/recaptcha/api.js?onload=znCaptchaOnloadCallback&render=explicit' . $captcha_lang, array( 'jquery', 'zion-frontend-js' ), ZNB()->getVersion(), true );
			wp_localize_script( 'znb_recaptcha', 'zn_contact_form', array(
				'captcha_not_filled' => __( 'Please complete the Captcha validation', 'zn_framework' ),
			) );
		}
		foreach ( $this->opt( 'fields', array() ) as $key => $field ) {
			if ( isset( $field['type'] ) && $field['type'] == 'datepicker' ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'zn_timepicker', ZNHGFW()->getFwUrl('assets/dist/js/jquery.timepicker.min.js'), array( 'jquery' ), ZNB()->getVersion(), true );
				if ( isset( $field['datepicker_lang'] ) && $field['datepicker_lang'] != '' ) {
					wp_enqueue_script( 'jquery-ui-datepicker-i18n', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/i18n/jquery-ui-i18n.min.js', array( 'jquery-ui-datepicker' ), ZNB()->getVersion(), true );
				}
			}
		}
	}

	function css(){

		$uid = $this->data['uid'];
		$css = '';

		// typography styles
		$btn_typo = array();
		$btn_typo['lg'] = $this->opt('button_typo', '' );
		if( !empty($btn_typo) ){
			$btn_typo['selector'] = '.'.$uid . ' .zn-formSubmit';
			$css .= zn_typography_css( $btn_typo );
		}

		// typography styles for labels
		$labels_typo = array();
		$labels_typo['lg'] = $this->opt('labels_typo', '' );
		if( !empty($labels_typo) ){
			$labels_typo['selector'] = '.'.$uid . ' .zn-formItem-label';
			$css .= zn_typography_css( $labels_typo );
		}

		return $css;
	}

	/*--------------------------------------------------------------------------------------------------
		Sanitize string
	--------------------------------------------------------------------------------------------------*/
	function zn_sanitize_string( $string , $prepend = false , $not_empty = false ){

		$string = remove_accents( $string );
		$string = preg_replace(array('~[\W\s]~' , '/_+/'), '_', $string);
		$string = strtolower($string);

		if( $not_empty )
		{
			if(str_replace('_', '', $string) == '') return;
		}

		if ( $prepend ) {
			$string = $prepend . $string;
		}

		return $string;
	}


	function create_form_elements() {

		// THIS WILL BE INCREMENTED IF THE GENERATED ID IS NOT OK
		$i = 0;
		foreach ( $this->form_fields as $key => $field ) {
			if ( isset( $field['type'] ) && method_exists( $this, $field['type'] ) ) {
				$value = $validation_class = '';
				// SET THE FIELD ID FROM NAME AND FALLBACK TO THE INCREMENTED ID
				$id = $this->zn_sanitize_string( $field['name'], false, true );
				if ( $field['type'] != 'hidden' ) {
					$id = 'zn_form_field_' . $id .  self::$form_id . '_' . $i;
				}
				$i ++;
				//$validation_class = $field['validation'] != 'none' ? $field['validation'] : '';
				// ADD THE VALUE IF IT'S SET
				if ( ! empty( $_POST[ $id ] ) ) {
					$value = $_POST[ $id ];
				}
				if ( empty( $value ) && ( isset( $field['value'] ) && ! empty( $field['value'] ) ) ) {
					$value = $field['value'];
				}
				// PERFORM THE VALUE VALIDATION
				if ( $field['validation'] != 'none' && isset( $_POST[ $id ] ) ) {
					$validation_class .= ' ' . $this->validate_field( $field, $id, $value );
				}

				// Special case for recaptcha
				if ( $field['validation'] == 'captcha' ) {
					if( isset( $_POST['g-recaptcha-response'] ) ){
						$validation_class .= ' ' . $this->validate_field( $field, $id, $_POST['g-recaptcha-response'] );
					}
					else{
						$this->submit = false;
					}

				}
				echo '<div class="' . $field['width'] . '">';
				echo '<div class="zn-formItem-wrapper zn-formItem--'.$field['type'].' ' . $validation_class . '">';

				/*
				 * Some servers are not allowing this type of function call:
				 * 		$this->$field['type']( $field , $id , $value );
				 */
				call_user_func( array( $this, $field['type'] ), $field, $id, $value );

				echo '</div>';
				echo '</div>';
			}
		}
	}

	/* WILL OUTPUT A TEXT FIELD */
	function text( $field, $id, $value ) {
		$classes[] = 'zn-formItem-field zn-formItem-field--' . $field['type'] . ' form-control';
		$classes[] = 'zn_validate_' . $field['validation'];

		$placeholder = ( $this->show_placeholder() ? $field['placeholder'] : '' );

		if ( $this->show_labels()  ) {
			echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}

		printf('<input type="text" name="%s" id="%s" placeholder="%s" value="%s" class="%s"/>',
			$id,
			$id,
			esc_attr( $placeholder ),
			esc_attr( $value ),
			zn_join_spaces($classes)
		);
	}

	/* Will output plain text to be used as title or just text */
	function plain_text( $field, $id, $value ) {
		$ptext = $field['name'];
		if ( ! empty( $ptext ) ) {
			echo $ptext;
		}
	}


	function hidden( $field, $id, $value ) {
		$v = ( isset( $value ) && ! empty( $value ) ) ? esc_attr( $value ) : '1';
		echo sprintf('<input type="hidden" name="%s" id="%s" value="%s" class="%s"/>',
			$id,
			$id,
			esc_attr( $v ),
			'zn-formItem-field zn-formItem-field--' . $field['type'] . ' zn_validate_' . $field['validation']
		);
	}

	function checkbox( $field, $id, $value ) {
		echo sprintf('<input %s type="checkbox" name="%s" id="%s" value="true" class="%s"/>',
			( true === $value ? 'checked="checked"' : '' ),
			$id,
			$id,
			'zn-formItem-field zn-formItem-field--' . $field['type'] . ' zn_validate_' . $field['validation']
		);

		if ( $this->show_labels() ) {
			echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}
	}

	function select( $field, $id, $value ) {
		if ( $this->show_labels() ) {
			echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}

		$separator_symbol = apply_filters('zn_contactform_select_separator', ',');
		$multiple = ( isset( $field['select_multiple'] ) && $field['select_multiple'] === 'yes' );
		$multiple_markup = $multiple ? ' multiple' : '';
		$field_name = $multiple ? $id.'[]' : $id;

		$select_options = explode( $separator_symbol, $field['select_option'] );
		if ( is_array( $select_options ) ) {
			echo '<select name="' . $field_name . '" '.$multiple_markup.' id="' . $id . '" class="zn-formItem-field zn-formItem-field--' . $field['type'] . ' form-control zn_validate_' . $field['validation'] . '">';
			foreach ( $select_options as $key => $value ) {
				$options = explode( ':', $value );
				if ( is_array( $options ) ) {
					$select_key   = trim( $options[0] );
					$select_value = trim( $options[1] );
					$selected     = $select_key == $value ? 'selected="selected"' : '';
					echo '<option value="' . esc_attr( $select_key ) . '" ' . $selected . '>' . $select_value . '</option>';
				}
			}
			echo '</select>';
		}
	}

	function radio( $field, $id, $value ) {
		if ( $this->show_labels() ) {
			echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}

		$separator_symbol = apply_filters('zn_contactform_radio_separator', ',');
		$select_options = explode( $separator_symbol, $field['select_option'] );

		$inline = isset( $field['radio_inline'] ) && $field['radio_inline'] == 'yes' ? 'is-inline' : '';

		if ( is_array( $select_options ) ) {
			$i = 0;
			foreach ( $select_options as $key => $value ) {
				$options = explode( ':', $value );
				if ( is_array( $options ) ) {
					$select_key     = trim( $options[0] );
					$select_value   = trim( $options[1] );
					$selected       = $select_key == $value ? 'checked="checked"' : '';
					$incremented_id = $id . $i;
					echo '<div class="zn-formItem-fieldWrapper '.$inline.'">';
					echo '<input type="radio" class="zn-formItem-field zn-formItem-field--' . $field['type'] . '" name="' . $id . '" id="' . $incremented_id . '" value="' .esc_attr( $select_key ) . '" ' . $selected . '>';
					echo '<label for="' . $incremented_id . '" class="zn-radioField-label">' . $select_value . '</label>';
					echo '</div>';
					$i ++;
				}
			}
		}
	}

	function captcha( $field, $id, $value ) {
		$siteKey = zget_option( 'rec_pub_key', 'general_options' );
		$pvKey   = zget_option( 'rec_priv_key', 'general_options' );
		if ( empty( $siteKey ) || empty( $pvKey ) ) {
			_e( 'Please enter the reCaptcha public and private keys inside the admin panel!', 'zn_framework' );
			return;
		}
		echo '<span class="zn-recaptcha" data-sitekey="' . $siteKey . '" id="zn_recaptcha_' . self::$form_id . '"></span>';
	}

	function textarea( $field, $id, $value ) {

		if ( $this->show_labels() ) {
			echo sprintf('<label for="%s" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}

		$classes[] = 'zn-formItem-field zn-formItem-field--' . $field['type'] . ' form-control';
		$classes[] = 'zn_validate_' . $field['validation'];

		$placeholder = ( $this->show_placeholder() ? $field['placeholder'] : '' );

		printf( '<textarea name="%s" id="%s" placeholder="%s" class="%s" cols="40" rows="6">%s</textarea>',
			$id,
			$id,
			esc_attr( $placeholder ),
			zn_join_spaces( $classes ),
			esc_attr( $value )
		);
	}

	function datepicker( $field, $id, $value ) {
		$date_val = isset( $value['date'] ) ? stripslashes( $value['date'] ) : '';
		$time_val = isset( $value['time'] ) ? stripslashes( $value['time'] ) : '';
		$datepicker_lang = isset( $field['datepicker_lang'] ) && $field['datepicker_lang'] != '' ? 'data-datepickerlang="' . $field['datepicker_lang'] . '"' : '';
		$date_format = isset( $field['date_format'] ) && $field['date_format'] != '' ? 'data-dateformat="' . $field['date_format'] . '"' : '';

		echo '<div class="zn-formItem-fieldWrapper">';

		$string = '<input type="text" name="%s[date]" id="%s[date]" placeholder="%s" value="%s" class="%s" %s/>';

		$showLabel = $this->show_labels();

		if ( $showLabel ) {
			echo sprintf('<label for="%s[date]" class="zn-formItem-label">%s</label>', $id, esc_attr( $field['name'] ) );
		}

		$placeholder = ( $this->show_placeholder() ? $field['placeholder'] : '' );

		echo sprintf($string,
			$id,
			$id,
			esc_attr( $placeholder ),
			esc_attr( $date_val ),
			'zn-formItem-field zn-formItem-field--' . $field['type'] . ' form-control zn_validate_' . $field['validation'],
			$datepicker_lang . ' ' . $date_format
		);

		echo '</div>';

		if ( isset( $field['time_picker'] ) && $field['time_picker'] == 'yes' ) {

			echo '<div class="zn-formItem-fieldWrapper">';

			if ( $showLabel && isset( $field['tpicker_label'] ) && ! empty( $field['tpicker_label'] ) ) {
				echo sprintf('<label for="%s[time]" class="zn-formItem-label">%s</label>', $id, esc_attr($field['tpicker_label']) );
			}

			echo sprintf('<input type="text" name="%s[time]" id="%s[time]" value="%s" class="%s" data-timeformat="%s" />',
				$id,
				$id,
				esc_attr( $time_val ),
				'zn-formItem-field zn-formItem-field--timepicker form-control zn_validate_' . $field['validation'],
				get_option( 'time_format', 'h:i A' )
			);

			echo '</div>';
		}

	}

	function validate_field( $field, $id, $value )
	{
		if ( ! isset( $_POST[ 'zn_pb_form_submit_' . self::$form_id ] ) ) {
			// do nothing if the current form wasn't submitted
			return '';
		}

		// Validate field
		if(! isset($field['validation'])){
			return '';
		}

		$validationRule = trim($field['validation']);

		if('not_empty' == $validationRule){
			if ( ! empty( $value ) ) {
				return "zn-formItem--valid";
			}
		}

		if( 'is_numeric' == $validationRule) {
			if ( is_numeric( $value ) ) {
				return "zn-formItem--valid";
			}
		}

		if( 'is_email' == $validationRule) {
			if ( is_email( $value ) ) {
				return "zn-formItem--valid";
			}
		}

		if('captcha' == $validationRule)
		{
			$captcha_val = $_POST['g-recaptcha-response'];
			$pvKey = zget_option( 'rec_priv_key', 'general_options' );
			$response = wp_remote_request( "https://www.google.com/recaptcha/api/siteverify?secret=" . trim( $pvKey ) . "&response=" . trim( $captcha_val ) );

			if ( is_wp_error($response) ) {
				error_log('[PageBuilder] Contact Form error: '.$response->get_error_message() );
				$this->error_messages[] = __( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
			}
			elseif( !isset($response['body']) || empty( $response['body'] ) ){
				$this->error_messages[] = __( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
			}
			else {
				$response = json_decode( $response['body'], true );
			}

			if( !is_array($response) || ! isset($response["success"]) ){
				$this->error_messages[] = __( 'An error occurred. Please try again in a few moments.', 'zn_framework' );
				$response = array(
					'success' => false,
				);
			}

			if ( $response["success"] !== true )
			{
				if ( ! empty( $response['error-codes'] ) && is_array( $response['error-codes'] ) )
				{
					foreach ( $response['error-codes'] as $key => $value ) {
						if ( $value == 'missing-input-secret' ) {
							$this->error_messages[] = __( 'The secret parameter is missing.', 'zn_framework' );
							continue;
						}
						if ( $value == 'invalid-input-secret' ) {
							$this->error_messages[] = __( 'The secret parameter is invalid or malformed.', 'zn_framework' );
							continue;
						}
						if ( $value == 'missing-input-response' ) {
							$this->error_messages[] = __( 'Please complete the captcha validation', 'zn_framework' );
							continue;
						}
						if ( $value == 'invalid-input-response' ) {
							$this->error_messages[] = __( 'The response parameter is invalid or malformed.', 'zn_framework' );
							continue;
						}
					}
				}
				// response == false, no error codes retrieved, meaning the ReCaptcha is not properly configured
				else {
					$this->error_messages[] = __( 'Error: ReCaptcha is not properly configured.', 'zn_framework' );
				}

				// There was a problem. Don't submit the form'
				$this->submit = false;
				return 'zn-formItem--invalid';
			}
			// All good
			else {
				return "zn-formItem--valid";
			}
		}

		$this->submit = false;
		return 'zn-formItem--invalid';
	}

	private $_testing = false;

	function form_send()
	{
		// Whether or not the form was submitted
		$this->_formSubmitted = true;

		$to = $this->opt( 'email' ) ? trim( $this->opt( 'email' ) ) : '';
		if ( false !== ( $pos = strpos( $to, ',' ) ) ) {
			// trim out multiple spaces
			$to = preg_replace( '/\s+/', ' ', $to );
			$to = explode( ',', $to );
		}
		$subject     = $this->opt( 'email_subject' ) ? $this->opt( 'email_subject' ) : __( 'New form submission', 'zn_framework' );
		$message     = '';
		$attachments = '';
		$i = 0;
		$sc       = 'send_me_copy_' . $this->data['uid'];
		$sendCopy = ( isset( $_REQUEST[ $sc ] ) && $_REQUEST[ $sc ] == 'true' );
		$dynamic_email = '';

		//#! Allow exporting fields to CF templates
		$ZnContactFormFieldsArray = array();

		foreach ( $this->form_fields as $field ) {
			// SET THE FIELD ID FROM NAME AND FALLBACK TO THE INCREMENTED ID
			$id = $this->zn_sanitize_string( $field['name'], false, true );
			if ( $field['type'] != 'hidden' ) {
				$id = 'zn_form_field_' . $id .  self::$form_id . '_' . $i;
			}
			$i ++;
			if ( isset( $_POST[ $id ] ) ) {
				$ignored_field_types = array(
					'hidden',
					'captcha',
				);
				if ( ! in_array( $field['type'], $ignored_field_types ) ) {
					$val = $_POST[ $id ];
					if ( is_array( $val ) ) {
						$val = implode( ' / ', $_POST[ $id ] );
					} else {
						$val = nl2br( $val );
					}
					$fieldName = $field['name'];
					$ZnContactFormFieldsArray[ "$fieldName" ] = $val;
				}
				// Check if form has email field
				if ( isset( $field['validation'] ) && $field['validation'] == 'is_email' ) {
					$dynamic_email = nl2br( $_POST[ $id ] );
				}
				//#! Check to see whether or not we need to set the dynamic subject
				if( isset($field['is_dynamic_subject']) && ('yes' == $field['is_dynamic_subject'])){
					$subject = wp_strip_all_tags( $_POST[ $id ] );
				}
			}
		}

		// SENDER
		$from = $this->opt( 'from_sender', $this->from_email() );

		// GENERATE THE FINAL HEADER AND SEND THE FORM
		$headers[] = 'From: ' . $from . ' <' . $from . '>';

		// Dynamic Sender as Reply-To
		if( !empty($dynamic_email) && is_email( $dynamic_email ) ){
			$headers[] = 'Reply-To: ' . $dynamic_email . ' <' . $dynamic_email . '>';
		}

		if ( $sendCopy && is_email( $dynamic_email ) ) {
			$headers[] = 'Bcc: ' . $dynamic_email . ' <' . $dynamic_email . '>';
		}

		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'MIME-Version: 1.0';

		//#! Allow using the contact form templates
		$templateFilePath = apply_filters( 'znhgfw_contact_form_template_path', dirname(__FILE__).'/cf-template.php');
		$templateFilePath = realpath( wp_normalize_path( $templateFilePath ) );
		if( is_file( $templateFilePath ) ){
			ob_start();
			include( $templateFilePath );
			$message = ob_get_contents();
			ob_end_clean();
		}

		$result = $this->_emailSent = wp_mail( $to, $subject, $message, $headers );

		/**
		 * Provides more information when debug mode is enabled
		 */
		if ( ! $result &&  $this->_inDebugMode()) {
			global $ts_mail_errors;
			global $phpmailer;
			if ( ! isset( $ts_mail_errors ) ) {
				$ts_mail_errors = array();
			}
			if ( isset( $phpmailer ) ) {
				$ts_mail_errors[] = $phpmailer->ErrorInfo;
			}
			$result = 'Errors:<br>' . implode( '<br>', $ts_mail_errors );

			// Fixes array to string conversion when more than one recipient is provided
			if ( ! empty( $to ) && is_array( $to ) ) {
				$to = implode( ', ', $to );
			}
			// Display info
			$result .= '<br><br>-- INFO: (Debug enabled!) ----<br><br>' . implode( '<br><br>', array(
					'TO: ' . $to,
					'MSG: <br><em>' . $message . '</em>',
					'HEADERS: ' . var_export( $headers, 1 )
				) );
		}

		return $result;
	}

	/**
	 * Utility method to check whether or not the Debug Mode is enabled
	 * @return bool
	 */
	private function _inDebugMode(){
		return ($this->opt( 'cf_debug', '' ) == 1);
	}

	public static function is_localhost() {
		$server_name = strtolower( $_SERVER['SERVER_NAME'] );
		return in_array( $server_name, array( 'localhost', '127.0.0.1' ) );
	}

	public static function from_email() {
		$admin_email = get_option( 'admin_email' );
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );

		if ( self::is_localhost() ) {
			return $admin_email;
		}

		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		if ( strpbrk( $admin_email, '@' ) == '@' . $sitename ) {
			return $admin_email;
		}

		return 'wordpress@' . $sitename;
	}

}

ZNB()->elements_manager->registerElement( new ZNB_ContactForm(array(
	'id' => 'ZnContactForm',
	'name' => __('Contact Form', 'zn_framework'),
	'description' => __('This element will generate a contact form.', 'zn_framework'),
	'level' => 3,
	'category' => 'Content',
	'styles' => true,
	'legacy' => false,
	'keywords' => array('mail', 'email'),
)));
