<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/*
	Name: Contact Form
	Description: This element will generate a contact form
	Class: ZnContactForm
	Category: content
	Level: 3
	Styles: true
	Keywords: mail, email
*/

class ZnContactForm extends ZnElements {

	// Will allow multiple forms on a single page. It will be incremented on each form created
	static $form_id = 1;

	var $submit = true;

	var $form_fields;
	var $error_messages = array();

	/**
	 * Holds the result of wp_mail function call
	 *
	 * @since v4.1.5
	 * @see   form_send()
	 *
	 * @var bool
	 */
	private $_emailSent = false;
	/**
	 * Whether or not the form was submitted
	 *
	 * @since v4.1.5
	 *
	 * @var bool
	 */
	private $_formSubmitted = false;

	/**
	 * Element's options
	 *
	 * @return array
	 */
	function options() {
		$uid              = $this->data['uid'];
		$datepicker_langs = array(
			''      => '-- Default (English) --',
			'af'    => 'Afrikaans',
			'ar-DZ' => 'Algerian Arabic',
			'ar'    => 'Arabic',
			'az'    => 'Azerbaijani',
			'be'    => 'Belarusian',
			'bg'    => 'Bulgarian',
			'bs'    => 'Bosnian',
			'ca'    => 'Catal&#224;',
			'cs'    => 'Czech',
			'cy-GB' => 'Welsh/UK',
			'da'    => 'Danish',
			'de'    => 'German',
			'el'    => 'Greek',
			'en-AU' => 'English/Australia',
			'en-GB' => 'English/UK',
			'en-NZ' => 'English/New Zealand',
			'eo'    => 'Esperanto',
			'es'    => 'Espa&#241;ol',
			'et'    => 'Estonian',
			'eu'    => 'Basque',
			'fa'    => 'Persian (Farsi)',
			'fi'    => 'Finnish',
			'fo'    => 'Faroese',
			'fr-CA' => 'Canadian-French',
			'fr-CH' => 'Swiss-French',
			'fr'    => 'French',
			'gl'    => 'Galician',
			'he'    => 'Hebrew',
			'hi'    => 'Hindi',
			'hr'    => 'Croatian',
			'hu'    => 'Hungarian',
			'hy'    => 'Armenian',
			'id'    => 'Indonesian',
			'is'    => 'Icelandic',
			'it'    => 'Italian',
			'ja'    => 'Japanese',
			'ka'    => 'Georgian',
			'kk'    => 'Kazakh',
			'km'    => 'Khmer',
			'ko'    => 'Korean',
			'ky'    => 'Kyrgyz',
			'lb'    => 'Luxembourgish',
			'lt'    => 'Lithuanian',
			'lv'    => 'Latvian',
			'mk'    => 'Macedonian',
			'ml'    => 'Malayalam',
			'ms'    => 'Malaysian',
			'nb'    => 'Norwegian Bokm&#229;l',
			'nl-BE' => 'Dutch (Belgium)',
			'nl'    => 'Dutch',
			'nn'    => 'Norwegian Nynorsk',
			'no'    => 'Norwegian',
			'pl'    => 'Polish',
			'pt-BR' => 'Brazilian',
			'pt'    => 'Portuguese',
			'rm'    => 'Romansh',
			'ro'    => 'Romanian',
			'ru'    => 'Russian',
			'sk'    => 'Slovak',
			'sl'    => 'Slovenian',
			'sq'    => 'Albanian',
			'sr-SR' => 'Serbian',
			'sr'    => 'Serbian 2',
			'sv'    => 'Swedish',
			'ta'    => 'Tamil',
			'th'    => 'Thai',
			'tj'    => 'Tajiki',
			'tr'    => 'Turkish',
			'uk'    => 'Ukrainian',
			'vi'    => 'Vietnamese',
			'zh-CN' => 'Chinese',
			'zh-HK' => 'Chinese 2',
			'zh-TW' => 'Chinese 3',
		);
		$mail_lists    = array('' => __( 'Select List ID', 'zn_framework'));
		$mailchimp_api = zget_option( 'mailchimp_api', 'general_options' );
		if ( ! empty( $mailchimp_api ) ) {
			Hg_Mailchimp::loadHgMcApiClass();

			$mcapi = new HG_MCAPI( $mailchimp_api );

			$lists = $mcapi->getLists();
			if ( ! empty( $lists['lists'] ) ) {
				foreach ( $lists['lists'] as $key => $value ) {
					$mail_lists[$value['id']] = $value['name'];
				}
			}
		}

		$date_info = 'http://api.jqueryui.com/datepicker/#utility-formatDate';
		$options   = array(
			'has_tabs' => true,
			'general'  => array(
				'title'   => 'General options',
				'options' => array(

					array(
						'id'          => 'email',
						'name'        => 'Recepient(s) email address',
						'description' => 'Please enter the email address where you want the form submissions to
							be sent. Note that you can enter multiple recipients separated by comma(,).',
						'type' => 'text',
					),
					array(
						'id'          => 'cf_from',
						'name'        => 'Email Sender (From)',
						'description' => 'Select which email address should be the sender address.',
						'std'         => 'noreply',
						// 'type'        => 'select',
						// 'options'     => array(
						// 	'noreply' 		=> 'No Reply ( noreply@your_domain )',
						// 	'custom'  		=> 'Custom email address',
						// 	'dynamic' 		=> 'Dynamic (sender\'s email address)',
						// 	'dynamic_self'	=> 'Dynamic (sender\'s email address)',
						// ),
						"options" => array(
							array(
								'value' => 'noreply',
								'name'  => __( 'No Reply ( noreply@your_domain )', 'zn_framework' ),
								'desc'  => __( 'Will just send the email with this email address as sender. This works in most cases and on most servers as it uses your websites domain. ', 'zn_framework' ),
							),
							array(
								'value' => 'custom',
								'name'  => __( 'Custom email address', 'zn_framework' ),
								'desc'  => __( 'Will send emails using custom email address. This may or may NOT work depending on your hosting server settings.', 'zn_framework' ),
							),
							array(
								'value' => 'dynamic',
								'name'  => __( 'Dynamic as Reply Address', 'zn_framework' ),
								'desc'  => __( 'Will use your visitor\'s email address as reply-to address.', 'zn_framework' ),
							),
						),
						"type"  => "smart_select",
						"class" => "zn-smartselect--md",
					),

					array(
						'id'          => 'cf_force_dynamic',
						'name'        => 'Force Dynamic as sender? (Not recommended!)',
						'description' => 'By default the dynamic sender is added as reply-to address. This will force the sender to be the visitor email address. Careful! This may or may NOT work depending on your hosting server settings.',
						'std'         => '',
						'type'        => 'toggle2',
						'value'       => 'yes',
						'dependency'  => array('element' => 'cf_from', 'value' => array('dynamic'),
						),
					),

					array(
						'id'          => 'cf_custom_sender',
						'name'        => 'Custom sender email',
						'description' => 'Specify the custom email address to use. In most cases it needs to be the same as your domain name, otherwise the server might block the mail function.',
						'std'         => '',
						'placeholder' => __( 'your_name@example.com', 'zn_framework' ),
						'type'        => 'text',
						'dependency'  => array(
							'element' => 'cf_from',
							'value'   => array(
								'custom',
							),
						),
					),
					array(
						'id'          => 'redirect_url',
						'name'        => 'Redirect url',
						'description' => 'Using this option you can redirect the user after the form is succesfully submitted',
						'std'         => '',
						'placeholder' => 'http://hogash.com',
						'type'        => 'text',
					),
					array(
						'id'          => 'submit_label',
						'name'        => 'Submit button label',
						'description' => 'Enter a text for the submit button label.',
						'std'         => 'Send message',
						'type'        => 'text',
					),
					array(
						'id'          => 'email_subject',
						'name'        => 'Email subject text',
						'description' => 'Please enter your desired text that will appear as the subject of the received email. <strong>Note that you can dynamically set the value of this field by specifying the dynamic field in the fields list.</strong>',
						'std'         => 'New form submission',
						'type'        => 'text',
					),
					array(
						'id'          => 'sent_message',
						'name'        => 'Mail sent message',
						'description' => 'Please enter your desired text that will appear after the form is successfully sent',
						'std'         => 'Thank you for contacting us',
						'type'        => 'text',
					),
					array(
						'id'          => 'captcha',
						'name'        => 'Show captcha',
						'description' => 'Select yes if you want to add a captcha field.',
						'type'        => 'zn_radio',
						'std'         => '0',
						'options'     => array( '1' => 'Yes', '0' => 'No' ),
						"class"       => "zn_radio--yesno",
					),
					//@since v4.0.12
					array(
						'id'          => 'send_me_copy',
						'name'        => 'Show send me a copy',
						'description' => 'Select yes if you want to allow users to send a copy of the message to themselves. This requires you to set the email field to have the "is email" validation type.',
						'type'        => 'zn_radio',
						'std'         => 'no',
						'options'     => array( 'yes' => 'Yes', 'no' => 'No'  ),
						"class"       => "zn_radio--yesno",
					),
					array(
						'id'          => 'mailchimp_subscribe',
						'name'        => 'Show mailchimp subscribe ?',
						'description' => 'Select yes if you want to let your users subscribe to mailchimp when completing the form.',
						'type'        => 'zn_radio',
						'std'         => 'no',
						'options'     => array( 'yes' => 'Yes', 'no' => 'No' ),
						"class"       => "zn_radio--yesno",
					),
					array(
						"name"        => __( "Mailchimp List ID", 'zn_framework' ),
						"description" => __( "Please select the mailchimp list ID you want to use. Please note that in order for the theme to display your list id's ,you will need to enter your Mailchimp API id in the General options > Mailchimp API option", 'zn_framework' ),
						"id"          => "mailchimp_list_id",
						"std"         => "",
						"type"        => "select",
						"options"     => $mail_lists,
						'dependency'  => array( 'element' => 'mailchimp_subscribe', 'value' => array( 'yes' ) ),
					),
					array(
						'id'          => 'cf_labels_pl',
						'name'        => 'Labels or Placeholders?',
						'description' => 'Choose what to display, ',
						'type'        => 'select',
						'std'         => '1',
						'options'     => array( '1' => 'Labels', '2' => 'Placeholders', '3' => 'Both' ),
					),
					array(
						'id'          => 'description',
						'name'        => 'Text above the form',
						'description' => 'If needed, add some text above the form.',
						'type'        => 'visual_editor',
						'class'       => 'zn_full',
						'std'         => '',
					),
					array(
						'id'          => 'cf_debug',
						'name'        => 'Enable debugging?',
						'description' => 'If you have problems with the contact form, this option will help debug the problem by showing some errors into the response field.',
						'type'        => 'toggle2',
						'std'         => '',
						'value'       => '1',
					),
				),
			),
			'fields' => array(
				'title'   => 'Fields',
				'options' => array(
					array(
						'id'            => 'fields',
						'name'          => 'Add your own, custom fields:',
						'description'   => 'Here you can create your contact form fields',
						'type'          => 'group',
						'sortable'      => true,
						'element_title' => 'name',
						'subelements'   => array(
							array(
								'id'          => 'name',
								'name'        => 'Field name',
								'description' => 'Please enter a name for this field',
								'type'        => 'text',
								// 'dependency' => array(
								// 	'element' => 'type' ,
								// 	'value'=> array('text', 'textarea', 'select', 'checkbox', 'dynamic' )
								// )
							),
							array(
								'id'          => 'type',
								'name'        => 'Field type',
								'description' => 'Please select the field type',
								'type'        => 'select',
								'options'     => array(
									'text'       => 'Text',
									'textarea'   => 'Textarea',
									'select'     => 'Select',
									'radio'      => 'Radio',
									'checkbox'   => 'Checkbox',
									'dynamic'    => 'Dynamic Field',
									'plain_text' => 'Plain text (accepts HTML)',
									'datepicker' => 'Datepicker & TimePicker',
								),
							),
							array(
								'id'          => 'select_option',
								'name'        => 'Select/Radio option',
								'description' => 'Please add your values for the select/radio option type in the following format : value:option name, value2:option name 2. For example "house:House, car:Car, piano:Piano"',
								'type'        => 'textarea',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'select', 'radio' ),
								),
							),
							array(
								'id'          => 'select_placeholder',
								'name'        => 'Select placeholder',
								'description' => 'Enter a text that will appear as the select placeholder.',
								'type'        => 'text',
								'std'         => '',
								'dependency'  => array(
									array(
										'element' => 'type',
										'value'   => array( 'select' ),
									),
									array(
										'element' => 'select_multiple',
										'value'   => array( 'no' ),
									),
								),
							),
							array(
								'id'          => 'select_multiple',
								'name'        => 'Allow multiple select?',
								'description' => 'Enable if you want the the user to select multiple options.',
								'class'       => 'zn_radio--yesno',
								'type'        => 'zn_radio',
								'std'         => 'no',
								'options'     => array(
									'yes' => __( "Yes", 'zn_framework' ),
									'no'  => __( "No", 'zn_framework' ),
								),
								'dependency' => array('element' => 'type', 'value' => array( 'select' ) ),
							),
							array(
								'id'          => 'radio_inline',
								'name'        => 'Radio Options Inline?',
								'description' => 'Enable if you want the radio options to appear inline, rather than blocks.',
								'class'       => 'zn_radio--yesno',
								'type'        => 'zn_radio',
								'std'         => 'no',
								'options'     => array(
									'yes' => __( "Yes", 'zn_framework' ),
									'no'  => __( "No", 'zn_framework' ),
								),
								'dependency' => array('element' => 'type', 'value' => array( 'radio' ) ),
							),

							array(
								'id'          => 'placeholder',
								'name'        => 'Placeholder',
								'description' => 'Please enter the placeholder value for this field',
								'type'        => 'text',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'text', 'textarea', 'datepicker' ),
								),
							),
							array(
								'id'          => 'datepicker_lang',
								'name'        => 'Datepicker Language',
								'description' => 'Please select a datepicker language if needed. Needs page refresh!',
								'std'         => '',
								'type'        => 'select',
								'options'     => $datepicker_langs,
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' ),
								),
							),
							array(
								"name"         => __( "Date format", 'zn_framework' ),
								"description"  => __( "Enter your desired date format. More info about formatting the date can be found", 'zn_framework' ) . ' <a target="_blank" href="' . $date_info . '">' . __( 'here', 'zn_framework') . '</a>',
								"id"           => "date_format",
								"type"         => "text",
								"std"          => 'yy-mm-dd',
								"placeholdder" => 'yy-mm-dd',
								'dependency'   => array(
									'element' => 'type',
									'value'   => array( 'datepicker' ),
								),
							),
							array(
								'id'          => 'time_picker',
								'name'        => 'Enable TimePicker?',
								'description' => 'Do you want this datepicker field to display a time picker field as well?',
								'type'        => 'toggle2',
								'std'         => '',
								'value'       => 'yes',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' ),
								),
							),
							array(
								'id'          => 'tpicker_label',
								'name'        => 'Timepicker Label text',
								'description' => 'Please add the label value for the timepicker field',
								'type'        => 'text',
								'std'         => 'PICK TIME',
								'dependency'  => array(
									'element' => 'type',
									'value'   => array( 'datepicker' ),
								),
							),
							array(
								'id'          => 'width',
								'name'        => 'Field width',
								'description' => 'Please select the field width',
								'type'        => 'select',
								'options'     => array(
									'col-sm-12' => 'Full width',
									'col-sm-9'  => 'Three-Quarters width',
									'col-sm-8'  => 'Two-Thirds width',
									'col-sm-6'  => 'Half width',
									'col-sm-4'  => 'One-Third width',
									'col-sm-3'  => 'One-Quarter width',
									'col-sm-2'  => 'One-Sixth width',
								),
							),

							array(
								'id'          => 'validation',
								'name'        => 'Field validation',
								'description' => 'Please select the field validation',
								'type'        => 'select',
								'std'         => 'not_empty',
								'options'     => array(
									'none'          => 'No validation',
									'not_empty'     => 'Value not empty',
									'is_email'      => 'Value is email',
									'is_numeric'    => 'Value is numeric',
									'is_letters_ws' => 'Value contain only letters and whitespace',

								),
								'dependency' => array(
									'element' => 'type',
									'value'   => array(
										'text',
										'textarea',
										'select',
										'checkbox',
										'dynamic',
										'datepicker',
									),
								),
							),

							array(
								'id'          => 'is_required',
								'name'        => __( 'Required?', 'zn_framework'),
								'description' => __( 'Enable if this field is required to be selected.', 'zn_framework'),
								'type'        => 'zn_radio',
								'std'         => 'yes',
								'options'     => array( 'yes' => __( 'Yes', 'zn_framework'), 'no' => __( 'No', 'zn_framework') ),
								'dependency'  => array(
									'element' => 'type',
									'value'   => array('radio' ),
								),
								"class" => 'zn_radio--yesno',
							),

							array(
								'id'          => 'is_dynamic_subject',
								'name'        => __( 'Use as dynamic email subject?', 'zn_framework'),
								'description' => __( 'Enable if this field is if you want to set the value of this field as the email subject.', 'zn_framework'),
								'type'        => 'zn_radio',
								'std'         => 'no',
								'options'     => array( 'yes' => __( 'Yes', 'zn_framework'), 'no' => __( 'No', 'zn_framework') ),
								"class"       => 'zn_radio--yesno',
							),
						),
					),
				),
			),
			'style' => array(
				'title'   => 'Style Options',
				'options' => array(
					array(
						'id'          => 'element_scheme',
						'name'        => 'Element Color Scheme',
						'description' => 'Select the color scheme of this element',
						'type'        => 'select',
						'std'         => '',
						'options'     => array(
							''      => 'Inherit from Kallyas options > Color Options [Requires refresh]',
							'light' => 'Light (default)',
							'dark'  => 'Dark',
						),
						'live' => array(
							'multiple' => array(
								array(
									'type'        => 'class',
									'css_class'   => '.' . $uid,
									'val_prepend' => 'cf--',
								),
								array(
									'type'        => 'class',
									'css_class'   => '.' . $uid,
									'val_prepend' => 'element-scheme--',
								),
								array(
									'type'        => 'class',
									'css_class'   => '.' . $uid . ' .form-control',
									'val_prepend' => 'form-control--',
								),
							),
						),
					),

					array(
						'id'          => 'title1',
						'name'        => 'Form Options',
						'description' => 'These are options to customize the form inputs.',
						'type'        => 'zn_title',
						'class'       => 'zn_full zn-custom-title-large',
					),

					array(
						"name"        => __( "Input Style", 'zn_framework' ),
						"description" => __( "Select the form's input styles.", 'zn_framework' ),
						"id"          => "cf_form_styles",
						"std"         => "fancy",
						"type"        => "select",
						"options"     => array(
							'fancy'    => __( "Fancy", 'zn_framework' ),
							'material' => __( "Materialized", 'zn_framework' ),
						),
					),

					array(
						"name"        => __( "Form Inputs Corners", 'zn_framework' ),
						"description" => __( "Select the input corners style in this form.", 'zn_framework' ),
						"id"          => "input_corners",
						"std"         => "",
						"type"        => "select",
						"options"     => array(
							''              => __( "Smooth rounded corner", 'zn_framework' ),
							'inp-c--square' => __( "Square corners", 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.' . $uid,
						),
						"dependency" => array( 'element' => 'cf_form_styles', 'value' => array('fancy') ),
					),
					array(
						"name"        => __( "Fancy Inputs Shadows", 'zn_framework' ),
						"description" => __( "Select the fancy form input shadows in this form.", 'zn_framework' ),
						"id"          => "inputs_style",
						"std"         => "",
						"type"        => "select",
						"options"     => array(
							''            => __( "Smooth Inset Shadow", 'zn_framework' ),
							'inp-s--flat' => __( "Flat, no shadow", 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.' . $uid,
						),
						"dependency" => array( 'element' => 'cf_form_styles', 'value' => array('fancy') ),
					),

					array(
						'id'          => 'title2',
						'name'        => 'Send Button Options',
						'description' => 'These are options to customize the send button style.',
						'type'        => 'zn_title',
						'class'       => 'zn_full zn-custom-title-large',
					),
					array(
						"name"        => __( "Send button style", 'zn_framework' ),
						"description" => __( "Select a style for the button", 'zn_framework' ),
						"id"          => "button_style",
						"std"         => "btn-fullcolor",
						"type"        => "smart_select",
						"options"     => zn_get_button_styles(),
						'live'        => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .zn_contact_submit',
						),
					),
					array(
						"name"        => __( "Button background Color", 'zn_framework' ),
						"description" => __( "Select button background color.", 'zn_framework' ),
						"id"          => "cf_btn_custom_bg_color",
						"std"         => "#cd2122",
						"alpha"       => true,
						"type"        => "colorpicker",
						"dependency"  => array( 'element' => 'button_style', 'value' => array('btn-fullcolor btn-custom-color') ),
					),

					array(
						"name"        => __( "Button Corners", 'zn_framework' ),
						"description" => __( "Select the button corners type for this button", 'zn_framework' ),
						"id"          => "button_corners",
						"std"         => "btn--rounded",
						"type"        => "select",
						"options"     => array(
							'btn--rounded' => __( "Smooth rounded corner", 'zn_framework' ),
							'btn--round'   => __( "Round corners", 'zn_framework' ),
							'btn--square'  => __( "Square corners", 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .zn_contact_submit',
						),
					),

					array(
						"name"        => __( "Button Width", 'zn_framework' ),
						"description" => __( "Select a size for the button", 'zn_framework' ),
						"id"          => "button_width",
						"std"         => "",
						"type"        => "select",
						"options"     => array(
							''                        => __( "Default", 'zn_framework' ),
							'btn-block btn-fullwidth' => __( "Full width (100%)", 'zn_framework' ),
							'btn-halfwidth'           => __( "Half width (50%)", 'zn_framework' ),
							'btn-third'               => __( "One-Third width (33%)", 'zn_framework' ),
							'btn-forth'               => __( "One-forth width (25%)", 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .zn_contact_submit',
						),
					),

					array(
						"name"        => __( "Size", 'zn_framework' ),
						"description" => __( "Select a size for the button", 'zn_framework' ),
						"id"          => "button_size",
						"std"         => "",
						"type"        => "select",
						"options"     => array(
							''       => __( "Default", 'zn_framework' ),
							'btn-lg' => __( "Large", 'zn_framework' ),
							'btn-md' => __( "Medium", 'zn_framework' ),
							'btn-sm' => __( "Small", 'zn_framework' ),
							'btn-xs' => __( "Extra small", 'zn_framework' ),
						),
						'live' => array(
							'type'      => 'class',
							'css_class' => '.' . $uid . ' .zn_contact_submit',
						),
					),

					array(
						"name"        => __( "Button Alignment", 'zn_framework' ),
						"description" => __( "Select the alignment", 'zn_framework' ),
						"id"          => "btn_alignment",
						"std"         => "left",
						"type"        => "select",
						"options"     => array(
							"left"   => "Left",
							"center" => "Center",
							"right"  => "Right",
						),
						'live' => array(
							'type'        => 'class',
							'css_class'   => '.' . $uid . ' .zn_submit_container',
							'val_prepend' => 'text-',
						),
					),
				),
			),
			'help' => znpb_get_helptab( array(
				'video'   => sprintf( '%s', esc_url( 'https://my.hogash.com/video_category/kallyas-wordpress-theme/#foPoTLB3Q5k') ),
				'docs'    => sprintf( '%s', esc_url( 'https://my.hogash.com/documentation/contact-form/') ),
				'copy'    => $uid,
				'general' => true,
			) ),
		);

		return $options;
	}

	function get_form_style() {
		return $this->opt( 'cf_form_styles', 'fancy');
	}

	function get_label_style() {
		$phType = $this->opt( 'cf_labels_pl', '1' );

		// force only labels if Material Style
		if ( $this->get_form_style() == 'material' ) {
			$phType = 1;
		}

		return $phType;
	}

	function element() {
		$options      = $this->data['options'];
		$description  = $this->opt( 'description' ) ? $this->opt( 'description' ) : '';
		$submit_label = ( $this->opt( 'submit_label' ) ) ? $this->opt( 'submit_label' ) : 'Send message';
		$fields       = ( $this->opt( 'fields' ) ) ? $this->opt( 'fields' ) : '';
		$captcha      = ( $this->opt( 'captcha' ) ) ? $this->opt( 'captcha' ) : '';
		$sent_message = ( $this->opt( 'sent_message' ) ) ? $this->opt( 'sent_message' ) : __( 'New Contact Form submission', 'zn_framework' );
		$response     = '';
		if ( empty( $fields ) ) {
			echo '<div class="zn-pb-notification">Please configure the element options and add your contact fields.</div>';

			return;
		}
		$elm_classes   = array();
		$elm_classes[] = $uid = $this->data['uid'];
		$elm_classes[] = zn_get_element_classes( $options );
		$color_scheme  = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) :
				$this->opt( 'element_scheme', '' );
		$elm_classes[] = 'cf--' . $color_scheme;
		$elm_classes[] = 'element-scheme--' . $color_scheme;

		$form_style = $this->get_form_style();

		$elm_classes[] = $form_style == 'fancy' ? $this->opt( 'input_corners', '') : '';
		$elm_classes[] = $form_style == 'fancy' ? $this->opt( 'inputs_style', '') : ''; ?>

		<div class="zn_contact_form_container contactForm cf-elm <?php echo implode( ' ', $elm_classes ); ?>" <?php echo zn_get_element_attributes( $options ); ?>>
			<?php if ( ! empty( $description ) ) {
			?>
				<div class="zn_description"><?php echo wpautop( $description ); ?></div>
			<?php
		} ?>
			<?php
			if ( $fields ) {
				$label_mod    = $this->get_label_style() == '2' ? 'cf--placeholders' : '';
				$redirect_url = $this->opt( 'redirect_url', false );
				//@since v4.1.5
				$debugEnabled = $this->_inDebugMode();

				echo '<form action="#" id="form_' . $uid . '" method="post" class="zn_contact_form contact_form cf-elm-form row ' . $label_mod . '" data-redirect="' . esc_attr( esc_url( $redirect_url ) ) . '">';
				if ( $debugEnabled ) {
					echo '<p class="col-sm-12"><strong>DEBUG IS ENABLED!</strong> Debug mode is only for development mode and shouldn\'t be enabled in production mode.</p>';
				}
				if ( $captcha ) {
					$fields[] = array(
						'name'       => 'zn_captcha',
						'type'       => 'captcha',
						'validation' => 'captcha',
						'width'      => 'col-sm-12',
					);
				}
				$fields[] = array(
					'name'       => 'zn_pb_form_submit_' . self::$form_id,
					'validation' => 'none',
					'value'      => 1,
					'type'       => 'hidden',
					'width'      => 'col-sm-12',
				);

				$this->form_fields = $fields;

				// PRINT OUT THE FORM FIELDS
				echo $this->create_form_elements();

				//@since v4.0.12
				if ( $this->opt( 'send_me_copy', 'no' ) == 'yes' ) {
					$cid = 'send_me_copy_' . $this->data['uid'];
					echo '<p class="col-sm-12  kl-' . $form_style . '-form zn_form_field zn_cf_checkbox">';
					echo '<input id="' . $cid . '" name="' . $cid . '" value="yes"
							class="zn_form_input form-control--' . $color_scheme . '" type="checkbox"/>';
					echo '<label for="' . $cid . '">' . __( 'Send me a copy', 'zn_framework' ) . '</label>';
					echo '<p>';
				}
				if ( $this->opt( 'mailchimp_subscribe', 'no' ) == 'yes' ) {
					$cid = 'mailchimp_subscribe_' . $this->data['uid'];
					echo '<p class="col-sm-12  kl-' . $form_style . '-form zn_form_field zn_cf_checkbox">';
					echo '<input id="' . $cid . '" name="' . $cid .
						 '" value="yes" class="zn_form_input form-control--' . $color_scheme .
						 '" type="checkbox"/>';
					echo '<label for="' . $cid . '">' . __( 'Subscribe to our newsletter', 'zn_framework' ) .
						 '</label>';
					echo '<p>';
				}

				echo '<div class="col-sm-12">';
				if ( isset( $_POST['zn_pb_form_submit_' . self::$form_id] ) ) {
					if ( ! empty( $this->error_messages ) ) {
						$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
						foreach ( $this->error_messages as $key => $value ) {
							$response .= $value;
						}
						$response .= '</div>';
					} else {
						$result = $this->submit ? $this->form_send() : '';

						// This way, $result will always be a boolean value
						$emailSent = ( $debugEnabled ? $this->_emailSent : $result );

						if ( $this->submit && $emailSent ) {
							$response = '<div class="alert alert-success alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $sent_message;
							if ( $debugEnabled ) {
								$response .= '<pre>1</pre>';
							}
							$response .= '</div>';
						} else {
							$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
							$response .= __( 'There was a problem submitting your message. Please try again.', 'zn_framework' );
							if ( $debugEnabled ) {
								// Here, the $result will contain the error message(s)
								$response .= '<pre>' . $this->form_send() . '</pre>';
							}
							$response .= '</div>';
						}
					}
				} else {
					// Only display the message when form was submitted. Prevents the element from sending the email
					// when debug mode active
					if ( $this->_formSubmitted) {
						$response = '<div class="alert alert-danger alert-dismissible zn_cf_response" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
						$response .= __( 'There was a problem submitting your message. Please try again.', 'zn_framework' );
						if ( $debugEnabled ) {
							$response .= '<pre>' . $this->form_send() . '</pre>';
						}
						$response .= '</div>';
					}
				}

				$btn_classes[] = $this->opt( 'button_style', 'btn-fullcolor' );
				$btn_classes[] = $this->opt( 'button_corners', 'btn--rounded' );
				$btn_classes[] = $this->opt( 'button_width', '' );
				$btn_classes[] = $this->opt( 'button_size', '' );

				echo '<div class="zn_contact_ajax_response titleColor" id="zn_form_id' . self::$form_id . '" >' . $response . '</div>';
				echo '<div class="zn_submit_container ' . 'text-' . $this->opt( 'btn_alignment', 'left' ) . '"><button class="zn_contact_submit btn ' . implode( ' ', $btn_classes) . '" type="submit">' . $submit_label . '</button></div>';
				echo '</div>'; // END of .col-sm-12
				echo '</form>';
			} ?>

		</div>

		<?php
		self::$form_id ++;
	}

	/**
	 * Load scripts in footer
	 */
	function scripts() {
		// Load recaptcha if needed
		if ( $this->opt( 'captcha', '0' ) == 1 ) {
			// Load recaptcha script in footer
			wp_enqueue_script( 'kl-recaptcha');
		}

		// Load timepicker and datepicker if we have a field for them
		foreach ( $this->opt( 'fields', array() ) as $key => $field ) {
			if ( isset( $field['type'] ) && $field['type'] == 'datepicker' ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'zn_timepicker', ZNHGFW()->getFwUrl( 'assets/dist/js/jquery.timepicker.min.js'), array( 'jquery' ), ZN_FW_VERSION, true );
				if ( isset( $field['datepicker_lang'] ) && $field['datepicker_lang'] != '' ) {
					wp_enqueue_script( 'jquery-ui-datepicker-i18n', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/i18n/jquery-ui-i18n.min.js', array( 'jquery-ui-datepicker' ), ZN_FW_VERSION, true );
				}
			}
		}
	}

	function css() {
		$uid        = esc_attr( $this->data['uid']);
		$css        = '.' . $uid . ' .btn-fullcolor.btn-custom-color{ background-color: ' . esc_attr( $this->opt( 'cf_btn_custom_bg_color' )) . ';}';
		$hoverColor = adjustBrightness( $this->opt( 'cf_btn_custom_bg_color'), 20);
		$css .= '.' . $uid . ' .btn-fullcolor.btn-custom-color:hover{ background-color: ' . esc_attr( $hoverColor) . ';}';
		return $css;
	}


	/*--------------------------------------------------------------------------------------------------
		Sanitize string
	--------------------------------------------------------------------------------------------------*/
	function zn_sanitize_string( $string, $prepend = false, $not_empty = false ) {

		// Remove all accents
		$string = remove_accents( $string );
		$string = preg_replace( array('~[^A-Za-z0-9]+~', '/_+/'), '_', $string);
		$string = strtolower( $string);

		if ( $not_empty ) {
			if ( str_replace( '_', '', $string) == '') {
				return;
			}
		}

		if ( $prepend ) {
			$string = $prepend . $string;
		}

		return $string;
	}


	function create_form_elements() {
		$form_style = $this->get_form_style();

		// THIS WILL BE INCREMENTED IF THE GENERATED ID IS NOT OK
		$i = 0;
		foreach ( $this->form_fields as $key => $field ) {
			if ( isset( $field['type'] ) && method_exists( $this, $field['type'] ) ) {
				$value = $validation_class = '';
				// SET THE FIELD ID FROM NAME AND FALLBACK TO THE INCREMENTED ID
				$id = $this->zn_sanitize_string( $field['name'], false, true );
				if ( $field['type'] != 'hidden' ) {
					$id = 'zn_form_field_' . $id . self::$form_id . '_' . $i;
				}
				$i ++;
				//$validation_class = $field['validation'] != 'none' ? $field['validation'] : '';
				// ADD THE VALUE IF IT'S SET
				if ( ! empty( $_POST[$id] ) ) {
					$value = $_POST[$id];
				}
				if ( empty( $value ) && ( isset( $field['value'] ) && ! empty( $field['value'] ) ) ) {
					$value = $field['value'];
				}
				// PERFORM THE VALUE VALIDATION
				if ( $field['validation'] != 'none' && isset( $_POST[$id] ) ) {
					$validation_class .= ' ' . $this->validate_field( $field, $id, $value );
				}

				// Special case for recaptcha
				if ( $field['validation'] == 'captcha' ) {
					if ( isset( $_POST['g-recaptcha-response'] ) ) {
						$validation_class .= ' ' . $this->validate_field( $field, $id, $_POST['g-recaptcha-response'] );
					} else {
						$this->submit = false;
					}
				}
				echo '<div class="' . $field['width'] . ' ' . $validation_class . ' kl-' . $form_style . '-form zn_form_field zn_cf_' .
					 $field['type'] . '">';
				/*
				 * Some servers are not allowing this type of function call:
				 * 		$this->$field['type']( $field , $id , $value );
				 */
				call_user_func( array( $this, $field['type'] ), $field, $id, $value );
				echo '</div>';
			}
		}
	}

	/* WILL OUTPUT A TEXT FIELD */
	function text( $field, $id, $value ) {
		$form_style = $this->get_form_style();

		$class[] = 'zn_form_input zn-field-' . $field['type'] . ' form-control';
		$class[] = 'form-control--' . $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$class[] = 'kl-' . $form_style . '-form-input';
		$class[] = 'zn_validate_' . $field['validation'];
		$class[] = $value != '' ? 'input-has-content' : '';

		$phType = $this->get_label_style();
		$label  = $placeholder  = '';

		if ( '2' == $phType ) {
			$placeholder = 'placeholder="' . esc_attr( $field['placeholder'] ) . '"';
		} elseif ( '3' == $phType ) {
			$label = '<label for="' . $id . '" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' .
						   esc_attr( $field['name'] ) . '</label>';
			$placeholder = 'placeholder="' . $field['placeholder'] . '"';
		} // 1
		else {
			$label = '<label for="' . $id . '" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' .
					 esc_attr( $field['name'] ) . '</label>';
		}
		echo '<input type="text" name="' . $id . '" id="' . $id . '" ' . $placeholder . ' value="' . esc_attr( $value ) . '" class="' . implode( ' ', $class) . '"/>';

		if ( ! empty( $label ) ) {
			echo $label;
		}
	}

	/* Will output plain text to be used as title or just text */
	function plain_text( $field, $id, $value ) {
		$ptext = $field['name'];
		if ( ! empty( $ptext ) ) {
			echo $ptext;
		}
	}

	/*
	 * WILL OUTPUT A DYNAMIC FIELD
	 * Displays a value that is passed through a custom link (eg: Modal Inline Dynamic link)
	 */
	function dynamic( $field, $id, $value ) {
		$this->text( $field, $id, $value );
	}

	/* WILL OUTPUT A TEXT FIELD */
	function hidden( $field, $id, $value ) {
		$v = ( isset( $value ) && ! empty( $value ) ) ? esc_attr( $value ) : '1';
		echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . $v .
			 '" class="zn_form_input zn_validate_' . $field['validation'] . '" />';
	}

	/* Will output a checkbox */
	function checkbox( $field, $id, $value ) {
		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) :
				$this->opt( 'element_scheme', '' );
		$checked = true === $value ? 'checked="checked"' : '';
		echo '<input ' . $checked . ' type="checkbox" name="' . $id .
			 '" class="zn_form_input form-control--' . $color_scheme . ' zn_validate_' .
			 $field['validation'] . '" id="' . $id . '" value="true"/>';
		echo '<label class="control-label" for="' . $id . '">' . $field['name'] . '</label>';
	}

	/* WILL OUTPUT A TEXT FIELD */
	function select( $field, $id, $value ) {
		$form_style   = $this->get_form_style();
		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );

		$separator_symbol = apply_filters( 'zn_contactform_select_separator', ',');
		$multiple         = ( isset( $field['select_multiple'] ) && $field['select_multiple'] === 'yes' );
		$multiple_markup  = $multiple ? ' multiple' : '';
		$field_name       = $multiple ? $id . '[]' : $id;

		$select_options = explode( $separator_symbol, $field['select_option'] );
		echo '<label class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . esc_attr( $field['name'] ) . '</label>';
		if ( is_array( $select_options ) ) {
			echo '<select name="' . $field_name . '" ' . $multiple_markup . ' id="' . $id . '" class="zn_form_input form-control form-control--' . $color_scheme . ' kl-' . $form_style . '-form-select zn_validate_' . $field['validation'] . '">';

			// Show the placeholder
			if ( ! $multiple && ! empty( $field['select_placeholder'] ) ) {
				printf( '<option value="" disabled selected>%s</option>', $field['select_placeholder'] );
			}

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

	/* WILL OUTPUT A RADIO FIELD */
	function radio( $field, $id, $value ) {
		$form_style = $this->get_form_style();

		$color_scheme   = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$select_options = explode( ',', $field['select_option'] );

		echo '<label class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . esc_attr( $field['name'] ) . '</label>';

		$inline = isset( $field['radio_inline'] ) && $field['radio_inline'] == 'yes' ? 'is-inline' : '';

		$required = isset( $field['is_required'] ) && $field['is_required'] == 'yes' ? 'required' : '';

		echo '<div class="kl-radio-group-wrapper ' . $inline . '">';
		if ( is_array( $select_options ) ) {
			$i = 0;
			foreach ( $select_options as $key => $value ) {
				$options = explode( ':', $value );
				if ( is_array( $options ) ) {
					$select_key     = trim( $options[0] );
					$select_value   = trim( $options[1] );
					$selected       = $select_key == $value ? 'checked="checked"' : '';
					$incremented_id = $id . $i;
					echo '<div class="kl-radio-field-group">';
					echo '<input type="radio" name="' . $id . '" id="' . $incremented_id . '" value="' . esc_attr( $select_key ) . '" ' . $required . ' ' . $selected . '>';
					echo '<label for="' . $incremented_id . '">' . $select_value . '</label>';
					echo '</div>';
					$i ++;
				}
			}
		}
		echo '</div>';
	}

	/* WILL OUTPUT A CAPTCHA FIELD */
	function captcha( $field, $id, $value ) {
		$siteKey = zget_option( 'rec_pub_key', 'general_options' );
		$pvKey   = zget_option( 'rec_priv_key', 'general_options' );
		if ( empty( $siteKey ) || empty( $pvKey ) ) {
			_e( 'Please enter the reCaptcha public and private keys inside the admin panel!', 'zn_framework' );
			return;
		}
		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		echo '<span class="kl-recaptcha" data-colorscheme="' . $color_scheme . '" data-sitekey="' . $siteKey . '" id="zn_recaptcha_' . self::$form_id . '"></span>';
	}

	/* WILL OUTPUT A TEXTAREA FIELD */
	function textarea( $field, $id, $value ) {
		$form_style = $this->get_form_style();


		$class[] = 'zn_form_input form-control';
		$class[] = 'form-control--' . $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) : $this->opt( 'element_scheme', '' );
		$class[] = 'kl-' . $form_style . '-form-textarea';
		$class[] = 'zn_validate_' . $field['validation'];
		$class[] = $value != '' ? 'input-has-content' : '';

		$phType = $this->get_label_style();
		$label  = $placeholder  = '';

		if ( '2' == $phType ) {
			$placeholder = 'placeholder="' . esc_attr( $field['placeholder'] ) . '"';
		} elseif ( '3' == $phType ) {
			$label       = '<label for="' . $id . '" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . esc_attr( $field['name'] ) . '</label>';
			$placeholder = 'placeholder="' . $field['placeholder'] . '"';
		} // 1
		else {
			$label = '<label for="' . $id . '" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . esc_attr( $field['name'] ) . '</label>';
		}
		echo '<textarea name="' . $id . '" class="' . implode( ' ', $class) . '" id="' . $id . '" ' . $placeholder . ' cols="40" rows="6">' . $value . '</textarea>';

		if ( ! empty( $label ) ) {
			echo $label;
		}
	}

	/* Will output plain text to be used as title or just text */
	function datepicker( $field, $id, $value ) {
		$form_style   = $this->get_form_style();
		$phType       = $this->get_label_style();
		$label        = '';
		$placeholder  = '';
		$field_name   = esc_attr( $field['name'] );
		$color_scheme = $this->opt( 'element_scheme', '' ) == '' ? zget_option( 'zn_main_style', 'color_options', false, 'light' ) :
				$this->opt( 'element_scheme', '' );
		if ( '2' == $phType ) {
			$placeholder = 'placeholder="' . esc_attr( $field['placeholder'] ) . '"';
		} elseif ( '3' == $phType ) {
			$label = '<label for="' . $id . '[date]" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . $field_name .
				'</label>';
			$placeholder = 'placeholder="' . $field['placeholder'] . '"';
		} // 1
		else {
			$label = '<label for="' . $id . '[date]" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' . $field_name .
				'</label>';
		}
		if ( isset( $value['date'] ) ) {
			$date_val = stripslashes( $value['date'] );
		} else {
			$date_val = '';
		}
		// Check for url text
		if ( isset( $value['time'] ) ) {
			$time_val = stripslashes( $value['time'] );
		} else {
			$time_val = '';
		}
		$datepicker_lang = isset( $field['datepicker_lang'] ) && $field['datepicker_lang'] != '' ? 'data-datepickerlang="' . $field['datepicker_lang'] . '"' : '';
		$date_format     = isset( $field['date_format'] ) && $field['date_format'] != '' ? 'data-dateformat="' . $field['date_format'] . '"' : '';
		echo '<div class="kl-' . $form_style . '-form-inpwrapper clearfix">';
		echo '<div class="kl-' . $form_style . '-form-col-wrapper kl-' . $form_style . '-form-date-wrapper">';
		echo '<input onfocus="blur();" type="text" name="' . $id . '[date]" id="' . $id . '[date]" ' . $placeholder . ' value="' .
			 $date_val . '" class="zn_form_input zn-field-' . $field['type'] . ' form-control form-control--' .
			 $color_scheme . ' kl-' . $form_style . '-form-input kl-' . $form_style . '-form-date zn_fr_date_picker zn_validate_' .
			 $field['validation'] . '" ' . $datepicker_lang . ' ' . $date_format . ' />';
		if ( ! empty( $label ) ) {
			echo $label;
		}
		echo '</div>';
		if ( isset( $field['time_picker'] ) && $field['time_picker'] == 'yes' ) {
			$timeformat = get_option( 'time_format', 'h:i A' );
			echo '<div class="kl-' . $form_style . '-form-col-wrapper kl-' . $form_style . '-form-time-wrapper">';
			echo '<input onfocus="blur();" type="text" name="' . $id . '[time]" id="' . $id . '[time]" value="' . $time_val .
				 '" class="zn_form_input zn-field-' . $field['type'] . ' form-control form-control--' . $color_scheme .
				 ' kl-' . $form_style . '-form-input kl-' . $form_style . '-form-time zn_fr_time_picker zn_validate_' . $field['validation'] .
				 '" data-timeformat="' . $timeformat . '"/>';
			if ( isset( $field['tpicker_label'] ) && ! empty( $field['tpicker_label'] ) ) {
				echo '<label for="' . $id . '[time]" class="control-label kl-font-alt kl-' . $form_style . '-form-label">' .
					 $field['tpicker_label'] . '</label>';
			}
			echo '</div>';
		}
		echo '</div>';
	}

	function validate_field( $field, $id, $value ) {
		if ( ! isset( $_POST['zn_pb_form_submit_' . self::$form_id] ) ) {
			// do nothing if the current form wasn't submitted
			return '';
		}

		// Validate field
		if ( ! isset( $field['validation'] )) {
			return '';
		}

		$validationRule = trim( $field['validation']);

		if ( 'not_empty' == $validationRule) {
			if ( ! empty( $value ) ) {
				return "zn_field_valid";
			}
		}

		if ( 'is_numeric' == $validationRule ) {
			if ( is_numeric( $value ) ) {
				return "zn_field_valid";
			}
		}

		if ( 'is_letters_ws' == $validationRule) {
			if ( ! preg_match( '/[^A-Za-z\s]/i', $value )) {
				return 'zn_field_valid';
			} else {
				$this->error_messages[] = sprintf( __( 'An error occurred. The field "%s" accepts only letters and whitespaces', 'zn_framework' ), $field['name'] );
			}
		}

		if ( 'is_email' == $validationRule ) {
			if ( is_email( $value ) ) {
				return "zn_field_valid";
			}
		}

		if ( 'captcha' == $validationRule) {
			$result = znhgReCaptchaValidate();
			if ( ! empty( $result) && is_array( $result ) ) {

				// Add the errror messages to the errors array
				$this->error_messages = array_merge( $this->error_messages, $result );

				// There was a problem. Don't submit the form'
				$this->submit = false;
				return 'zn_field_not_valid';
			}
			// All good
			else {
				return "zn_field_valid";
			}
		}

		$this->submit = false;
		return 'zn_field_not_valid';
	}

	private $_testing = false;
	function form_send() {
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
		$replyTo     = '';
		$attachments = '';
		$i           = 0;
		//@since v4.0.12
		$sc            = 'send_me_copy_' . $this->data['uid'];
		$sendCopy      = ( isset( $_REQUEST[$sc] ) && $_REQUEST[$sc] == 'true' );
		$dynamic_email = '';

		//#! Allow exporting fields to CF templates
		$kallyasContactFormFieldsArray = array();

		foreach ( $this->form_fields as $field ) {
			// SET THE FIELD ID FROM NAME AND FALLBACK TO THE INCREMENTED ID
			$id = $this->zn_sanitize_string( $field['name'], false, true );
			if ( $field['type'] != 'hidden' ) {
				$id = 'zn_form_field_' . $id . self::$form_id . '_' . $i;
			}
			$i ++;
			if ( isset( $_POST[$id] ) ) {
				$ignored_field_types = array(
					'hidden',
					'captcha',
				);
				if ( ! in_array( $field['type'], $ignored_field_types ) ) {
					$val = $_POST[$id];
					if ( is_array( $val ) ) {
						$val = htmlentities( implode( ' / ', $_POST[$id] ) );
					} else {
						$val = nl2br( htmlentities( stripslashes( $val ) ) );
					}
					$fieldName                                   = $field['name'];
					$kallyasContactFormFieldsArray["$fieldName"] = $val;
				}
				// Check if form has email field
				if ( isset( $field['validation'] ) && $field['validation'] == 'is_email' ) {
					$dynamic_email = nl2br( $_POST[$id] );
				}

				if ( isset( $field['is_dynamic_subject']) && ( 'yes' == $field['is_dynamic_subject']) ) {
					$subject = wp_strip_all_tags( $val );
				}
			}
		}

		// check if we need to subscribe the user to mailchimp
		$mailchimp_subscribe_id = 'mailchimp_subscribe_' . $this->data['uid'];
		$mailchimp_subscribe    = ( isset( $_REQUEST[$mailchimp_subscribe_id] ) && $_REQUEST[$mailchimp_subscribe_id] == 'true' );
		if ( $mailchimp_subscribe && ! empty( $dynamic_email ) ) {
			$mailchimp_api = zget_option( 'mailchimp_api', 'general_options' );
			$double_opt_in = zget_option( 'mailchimp_double_opt_in', 'general_options', false, 'no' );
			if ( ! empty( $mailchimp_api ) ) {
				Hg_Mailchimp::loadHgMcApiClass();

				$mcapi = new HG_MCAPI( $mailchimp_api, array(
					'opt_in' => $double_opt_in === 'yes' ? true : false,
				) );
				$merge_vars = array(
					'EMAIL' => $dynamic_email,
				);
				$list_id = $this->opt( 'mailchimp_list_id' );
				// Subscribe the user to mailchimp
				if ( $mcapi->subscribe( $list_id, $dynamic_email, $merge_vars ) ) {
					$text_msg = '';
					if ( $double_opt_in === 'yes' ) {
						$text_msg = __( 'Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'zn_framework' );
					} else {
						$text_msg = __( 'Success!&nbsp; You have successfully signed up.', 'zn_framework' );
					}


					$msg = '<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
						$text_msg .
						'</div>';
				} else {
					$msg = '<div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' .
						__( 'Error:', 'zn_framework' ) . '</strong>&nbsp; ' . $mcapi->errorMessage . '</div>';
				}
				echo $msg;
			}
		}
		// SENDER
		$from = 'no-reply@your-domain.com';
		// Get hostname
		// Some servers don't accept other senders, except own domain email addresses
		$default_from = parse_url( home_url() );
		if ( ! empty( $default_from['host'] ) ) {
			$from = "no-reply@" . str_replace( 'www.', '', $default_from['host'] );
		}
		$opt_from = $this->opt( 'cf_from', 'noreply' );
		if ( 'custom' == $opt_from ) {
			$custom_sender = trim( $this->opt( 'cf_custom_sender' ) );
			$from          = is_email( $custom_sender ) ? $custom_sender : $from;
		}

		// GENERATE THE FINAL HEADER AND SEND THE FORM

		// Force Dynamic sender
		if ( 'yes' == $this->opt( 'cf_force_dynamic', '' ) && 'dynamic' == $opt_from ) {
			$dynamic_from = is_email( $dynamic_email ) ? $dynamic_email : $from;
			$headers[]    = 'From: "' . $dynamic_from . '" <' . $dynamic_from . '>';
		} else {
			$headers[] = 'From: ' . $from . ' <' . $from . '>';
		}
		// Dynamic Sender as Reply-To
		if ( 'dynamic' == $opt_from ) {
			$replyTo   = is_email( $dynamic_email ) ? $dynamic_email : $from;
			$headers[] = 'Reply-To: "' . $replyTo . '" <' . $replyTo . '>';
		}

		//@since v4.0.12 - allow users to send the message to themselves as well
		if ( $sendCopy && is_email( $dynamic_email ) ) {
			$headers[] = 'Bcc: ' . $dynamic_email . ' <' . $dynamic_email . '>';
		}

		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'MIME-Version: 1.0';

		//#! Allow using the contact form templates
		$templateFilePath = apply_filters( 'znhgfw_contact_form_template_path', dirname( __FILE__) . '/cf-template.php' );
		$templateFilePath = realpath( wp_normalize_path( $templateFilePath ) );
		if ( is_file( $templateFilePath ) ) {
			ob_start();
			include( $templateFilePath );
			$message = ob_get_contents();
			ob_end_clean();
		}

		$result = $this->_emailSent = wp_mail( $to, $subject, $message, $headers );

		/*
		 * Provides more information when debug mode is enabled
		 * @since v4.1.5 - only displaying the debug info to website administrators
		 */
		if ( ! $result && $this->_inDebugMode() ) {
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
				'HEADERS: ' . var_export( $headers, 1 ),
			) );
		}

		return $result;
	}

	/**
	 * Utility method to check whether or not the Debug Mode is enabled
	 *
	 * @since v4.1.5
	 *
	 * @return bool
	 */
	private function _inDebugMode() {
		return ( $this->opt( 'cf_debug', '' ) == 1);
	}
}
