<?php if( ! defined( 'ABSPATH' ) ) {
	return;
}
/*
 * The default template for the Contact Form element.
 *
 * All form fields are set in this array: $kallyasContactFormFieldsArray;
 *
 * How to use your own templates:
 * 	- Create a file in your child theme, call it my-cf-template.php, for example
 * 	- Add the following code in your child theme's functions.php file
 *
	add_filter( 'znhgfw_contact_form_template_path', 'kc_cf_add_include_template_path', 100 );
	function kc_cf_add_include_template_path( $defaultTemplatePath ){
		return ( get_stylesheet_directory() . '/my-cf-template.php' );
	}
 *
 * 	- All Contact Form fields will be found in this array: $kallyasContactFormFieldsArray, as fieldName => field value
 *	- You can access other variables such as $to, $from, etc directly.
 *
 * See: pagebuilder/elements/contact_form/contact_form.php
 * See: function form_send()
 */
if( ! empty( $kallyasContactFormFieldsArray ) ){
	foreach( $kallyasContactFormFieldsArray as $fieldName => $fieldValue ){
		echo $fieldName . ': '.$fieldValue.'<br/>';
	}
}
