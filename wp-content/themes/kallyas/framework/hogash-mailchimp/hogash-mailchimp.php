<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'Hg_Mailchimp' ) ) {
	/*
		Plugin Name:       Hogash Mailchimp
		Description:       A plugin that will add Mailchimp functionality to all Hogash themes.
		Version:           1.0.4
		Author:            Hogash
		Author URI:        https://hogash.com/
		License:           GPLv2 or later
	*/

	/**
	 * Class Hg_Mailchimp
	 *
	 * Standard singleton
	 */
	class Hg_Mailchimp {

		/**
		 * Holds the plugin current version
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * Holds the HTTP path to the plugin's directory
		 * @var string
		 */
		public $url = '';

		/**
		 * Holds the SYSTEM path to the plugin's directory
		 * @var string
		 */
		public $path = '';

		/**
		 * Holds a refference to the plugin path
		 */
		public static $_plugin_path = '';

		/**
		 * Holds a refference to the plugin url
		 */
		public static $_plugin_url = '';

		/**
		 * Holds the reference to the instance of this class
		 * @var Hg_Mailchimp
		 */
		private static $instance = null;

		/**
		 * Returns the instance of the classs
		 * @return Hg_Mailchimp
		 */
		public static function get_instance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Hg_Mailchimp constructor.
		 */
		private function __construct() {
			// register text domain
			load_plugin_textdomain( 'hogash-mailchimp', false, basename( dirname( __FILE__ ) ) . '/languages' );

			$this->set_defaults();

			//#! Load the HG_MCAPI class
			require self::$_plugin_path . 'lib/class_gdpr.php';
			self::loadHgMcApiClass();

			// Add theme options
			add_filter( 'zn_theme_pages', array( $this, 'add_theme_options_page' ), 11 );
			add_filter( 'zn_theme_options', array( $this, 'add_theme_options' ), 11 );

			// refresh mailchimp lists on options save
			add_action( 'zn_save_theme_options', array( $this, 'refresh_mailchimp_lists' ) );

			// Load the mailchimp Widget
			require $this->path . 'widget/widget-mailchimp.php';

			// Add ajax functionality
			add_action( 'wp_ajax_nopriv_hg_mailchimp_register', array( $this, 'ajax_functionality' ) );
			add_action( 'wp_ajax_hg_mailchimp_register', array( $this, 'ajax_functionality' ) );

			// Register PB element
			add_action( 'znb:elements:register_elements', array( $this, 'register_pb_element' ) );

			// Load scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		}


		/**
		 * Load HG API class
		 *
		 * Will load the Mailchimp API class
		 *
		 */
		public static function loadHgMcApiClass() {
			if ( ! class_exists( 'HG_MCAPI' ) ) {
				//#! Load the HG_MCAPI class
				require self::$_plugin_path . 'lib/HG_MCAPI.php';
			}
		}

		/**
		 * Setup class vars
		 */
		private function set_defaults() {
			// Set plugin paths
			$plugin_parent_directory = basename( realpath( dirname( __FILE__ ) . '/../' ) );
			if ( 'framework' === $plugin_parent_directory ) {
				$this->url  = ZNHGTFW()->getThemeUrl( 'framework/hogash-mailchimp/' );
				$this->path = ZNHGTFW()->getThemePath( 'framework/hogash-mailchimp/' );
			} else {
				$this->url  = plugin_dir_url( __FILE__ );
				$this->path = plugin_dir_path( __FILE__ );
			}
			self::$_plugin_path = $this->path;
			self::$_plugin_url  = $this->url;
		}

		/**
		 * Ajax functionality
		 *
		 * Will handle all ajax calls for mailchimp elements
		 */
		public function ajax_functionality() {
			if ( isset( $_POST['mc_email'] ) ) {
				//#! Validate nonce
				if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'zn_hg_mailchimp' ) ) {
					wp_send_json_error( array(
						'message' => __( 'Invalid nonce.', 'zn_framework' ),
					) );
				} else {
					$consent_boxes = zget_option( 'after_newsletter_boxes', 'general_options', false, array() );
					$errors        = array();
					// Don't proceed if the gdpr text is empty
					if ( is_array( $consent_boxes ) && ! empty( $consent_boxes ) ) {
						foreach ( $consent_boxes as $key => $config ) {
							$input_id = Hg_Mailchimp_GDPR::generateId( $key );
							if ( ! isset( $_POST[ $input_id ] ) ) {
								$errors[] = $config['validation_text'];
							}
						}
					}

					// Make sure that all checkboxes are checked
					if ( is_array( $errors ) && ! empty( $errors ) ) {
						wp_send_json_error(array(
							'message' => implode( $errors, '<br />' ),
						));
					}

					$email         = sanitize_email( $_POST['mc_email'] );
					$mc_api_key    = zget_option( 'mailchimp_api', 'general_options' );
					$double_opt_in = zget_option( 'mailchimp_double_opt_in', 'general_options', false, 'no' );

					if ( ! empty( $mc_api_key ) ) {
						self::loadHgMcApiClass();
						$mcapi = new HG_MCAPI( $mc_api_key, array(
							'opt_in' => 'yes' === $double_opt_in ? true : false,
						));

						$merge_vars = array(
							'EMAIL' => $email,
						);

						$list_id = $_POST['mailchimp_list'];

						$subscribe_result = $mcapi->subscribe( $list_id, $email, $merge_vars );
						if ( $subscribe_result ) {
							if ( 'yes' === $double_opt_in ) {
								wp_send_json_success(array(
									'message' => __( 'Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'hogash-mailchimp' ),
								));
							} else {
								wp_send_json_success(array(
									'message' => __( 'Success!&nbsp; You have successfully signed up.', 'hogash-mailchimp' ),
								));
							}
						} else {
							wp_send_json_error(array(
								'message' => $mcapi->errorMessage,
							));
						}
					}
				}
			}

			wp_send_json_error(array(
				'message' => __( 'There was an error processing your request', 'hogash-mailchimp' ),
			));
		}

		/**
		 * Register the zion page builder Newsletter element
		 * @param ZionElementsManager $elements_manager
		 */
		public function register_pb_element( $elements_manager ) {
			//#! Zion Page Builder not installed
			if ( ! class_exists( 'ZionElement' ) ) {
				return;
			}
			require $this->path . 'pb_element/newsletter/newsletter.php';

			$elements_manager->registerElement( new ZNB_Newsletter( array(
				'id'          => 'HgMcNewsletter',
				'name'        => __( 'MailChimp Newsletter', 'hogash-mailchimp' ),
				'description' => __( 'This element will create a MailChimp based newsletter form.', 'hogash-mailchimp' ),
				'level'       => 3,
				'category'    => 'Content',
				'legacy'      => false,
				'keywords'    => array( 'mailing list', 'mailchimp' ),
			) ) );
		}


		/**
		 * Refresh mailchimp lists
		 *
		 * Will delete the saved mailchimp lists from DB
		 */
		public function refresh_mailchimp_lists() {
			delete_option( 'zn_mailchimp_lists' );
		}


		/**
		 * Add theme options page
		 *
		 * @uses zn_theme_pages filter
		 * @param array $admin_pages Array containing all admin pages
		 */
		public function add_theme_options_page( $admin_pages ) {
			$admin_pages['general_options']['submenus'][] = array(
				'slug'  => 'mailchimp_options',
				'title' => __( 'Mailchimp options', 'hogash-mailchimp' ),
			);
			return $admin_pages;
		}


		/**
		 * Add theme options
		 *
		 * @uses zn_theme_options filter
		 * @param array $admin_options Array containig all admin options
		 */
		public function add_theme_options( $admin_options ) {
			include $this->path . '/includes/options.php';
			return $admin_options;
		}


		/**
		 * Add scripts
		 *
		 * Will enqueue plugin specific scripts
		 */
		public function add_scripts() {
			$mc_api_key    = zget_option( 'mailchimp_api', 'general_options' );

			// Don't proceed if the mailchimp API key is missing
			if ( empty( $mc_api_key ) ) {
				return;
			}

			wp_enqueue_style( 'hg-mailchimp-styles', $this->url . 'assets/css/hg-mailchimp.css', array(), $this->version );
			wp_enqueue_script( 'hg-mailchimp-js', $this->url . 'assets/js/hg-mailchimp.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'hg-mailchimp-js', 'hgMailchimpConfig', array(
				'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
				'l10n'    => array(
					'error' => __( 'Error:', 'zn_framework' ),
				),
			) );
		}
	}

	Hg_Mailchimp::get_instance();
}
