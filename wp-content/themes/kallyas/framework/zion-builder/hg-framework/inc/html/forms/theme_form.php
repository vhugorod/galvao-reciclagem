<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}


/**
 * Html Theme Form
 *
 * This class helps you render a theme form based on config
 */
class ZnHgFw_Html_ThemeForm extends ZnHgFw_BaseFormType {

	/**
	 * Holds a refference to the version displayed on the form sidebar
	 */
	protected $version;

	/**
	 * Holds a refference to all options pages
	 */
	protected $pages;

	/**
	 * Holds a refference to the current page slug
	 */
	protected $slug;

	/**
	 * Holds a refference to the logo URL. The logo will appear on the left sidebar
	 */
	protected $logo;


	/**
	 * Internal render function
	 *
	 * @return string The HTML makup for the options form
	 */
	protected function _render() {
		$form  = $this->before_form();
		$form .= $this->render_tab_content();
		$form .= $this->after_form();

		return $form;
	}


	/**
	 * Render tab content
	 *
	 * @return string The HTML markup for the tab content ( options )
	 */
	protected function render_tab_content() {
		$output = '';
		$i      = 0;

		$output .= '<div class="tab-content">';

		foreach ( $this->options as $slug => $options ) {
			if ( 0 === $i ) {
				$output .= '<div class="tab-pane active" id="' . $slug . '">';
			} else {
				$output .= '<div class="tab-pane" id="' . $slug . '">';
			}

			foreach ( $options as $key => $option ) {
				// Set the defalult value
				$saved_value   = isset( $option['std'] ) ? $option['std'] : '';
				$option['std'] = $this->get_saved_value_for_option( $option['id'], $this->slug, $saved_value );

				// RENDER SINGLE OPTION
				$output .= ZNHGFW()->getComponent( 'html' )->zn_render_single_option( $option );
			}

			$output .= '</div>'; // Close tab-pane
			$i++;
		}

		$output .= '</div>'; // END tab-content

		return $output;
	}


	/**
	 * Get saved value for options
	 *
	 * Will return the saved value for a specific options if it was saved or the default value if provided
	 *
	 * @param string $option The options id for which you want to get the saved value
	 * @param string $category The option slug
	 * @param mixed $default_value The default value to be returned in case the option is not saved
	 *
	 * @return mixed The saved value for the requested option id
	 */
	protected function get_saved_value_for_option( $option, $category, $default_value = false ) {
		$saved_options = get_option( ZNHGTFW()->getThemeDbId() );

		if ( isset( $saved_options[ $category ][ $option ] ) ) {
			return $saved_options[ $category ][ $option ];
		} else {
			return $default_value;
		}
	}

	/**
	 * Before form
	 *
	 * @return string The HTML markup that will be placed before the form
	 */
	protected function before_form() {
		$output  = '<div id="zn_theme_admin" class="zn_theme_admin">';
		$output .= '<form id="zn_options_form" class="zn_container" action="#" method="post" >';
		$output .= '<div class="zn_inner zn_row">';
		$output .= '<div class="zn_span2 zn_sidebar">';
		$output .= '<div class="zn_logo">';

		if ( $this->logo ) {
			$output .= '<img src="' . $this->logo . '"/>';
		}

		$output .= '<span>' . __( 'Version: ', 'zn_framework' ) . '<strong>' . $this->version . '</strong></span>';
		$output .= '</div>';

		$output .= $this->get_sidebar_menu();

		// END zn_options_container
		$output .= '</div>';
		$output .= '<div class="zn_span10 zn_page_content">';

		/* START THE HEADER */
		$output .= '<div class="zn_action zn_header clearfix">';
		$output .= '<a class="zn_admin_button zn_save" href="#">Save options</a>';
		$output .= '</div>'; // END zn_header

		return $output;
	}


	/**
	 * After form
	 *
	 * @return string The HTML markup to be placed after the form content
	 */
	protected function after_form() {
		$output = '';

		/* START THE HEADER */
		$output .= '<div class="zn_action zn_header clearfix">';
		$output .= '<a class="zn_admin_button zn_save" href="#">Save options</a>';
		$output .= '</div>'; // END zn_header

		$output .= '</div>';
		$output .= '</div>';
		//$output .= '</div>';		// END zn_inner
		$output .= '<div class="zn_hidden">';

		$output .= '<input type="hidden" name="zn_option_field" value="' . $this->slug . '">';
		$output .= '<input type="hidden" name="action" value="zn_ajax_callback">';
		$output .= '<input type="hidden" name="zn_action" value="zn_save_options">';

		$output .= '</div>';
		// END zn_options_form
		$output .= '</form>';
		$output .= '</div>'; // END zn_theme_admin

		return $output;
	}


	/**
	 * Get sidebar menu
	 *
	 * Returns the HTML markup for the form sidebar menu
	 *
	 * @return string HTML markup for the form sidebar menu
	 */
	protected function get_sidebar_menu() {
		$output = '<ul class="wp-ui-primary nav-stacked">';

		foreach ( $this->pages[ $this->slug ]['submenus'] as $key => $page ) {
			if ( 0 === $key ) {
				$output .= '<li class="wp-ui-highlight" id="' . $page['slug'] . '_menu_item"><a href="#' . $page['slug'] . '" data-toggle="tab">' . $page['title'] . '</a></li>';
			} else {
				$output .= '<li id="' . $page['slug'] . '_menu_item"><a href="#' . $page['slug'] . '" data-toggle="tab">' . $page['title'] . '</a></li>';
			}
		}

		$output .= '</ul>';

		return $output;
	}


	/**
	 * Will return the HTML markup for the form
	 *
	 * @return string The HTML markup for the form
	 */
	public function render() {
		// Will output the option content
		return $this->_render();
	}
}
