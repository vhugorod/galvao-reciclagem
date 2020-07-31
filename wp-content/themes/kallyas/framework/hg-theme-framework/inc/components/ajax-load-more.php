<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class ZnHgThemeComponent_AjaxLoadMore {

	/**
	 * Nonce action id
	 */
	const NONCE_ACTION = 'znhg-load-more-nonce';

	/**
	 * Default post type
	 */
	const DEFAULT_POST_TYPE = 'post';

	/**
	 * Button instance
	 *
	 * HOlds a refference to the number of generated buttons
	 *
	 * @var integer
	 */
	public static $button_instance = 0;

	/**
	 * Main class constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_znhg_load_more', array( __CLASS__, 'ajax_handler' ) );
		add_action( 'wp_ajax_nopriv_znhg_load_more', array( __CLASS__, 'ajax_handler' ) );
	}


	/**
	 * Get Template for Post Type
	 *
	 * Will return the render function callback and arguments for a specific post type
	 *
	 * @param string $post_type The post type for which you want the render function arguments
	 *
	 * @return array Render function configuration
	 */
	public static function get_template_for_post_type( $post_type = 'post' ) {
		$defaults = array(
			'post'    => array(
				'callback' => 'get_template_part',
				'args'     => array( 'components/blog/content-loop' ),
			),
			'product' => array(
				'callback' => 'wc_get_template_part',
				'args'     => array( 'content', 'product' ),
			),
		);

		$post_type_config = apply_filters( 'znhg_theme_load_more_post_type_templates', $defaults );

		if ( ! empty( $post_type_config[$post_type] ) ) {
			return $post_type_config[$post_type];
		}

		return false;
	}


	/**
	 * Ajax Handler
	 *
	 * Will perform the WP query and will return the next page posts
	 *
	 * @return string The HTML markup for next posts
	 */
	public static function ajax_handler() {

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), self::NONCE_ACTION ) ) {
			wp_send_json_error( esc_html__( 'invalid nonce', 'zn_framework' ) );
		}

		$query = json_decode( stripslashes( $_POST['query'] ), true );
		$args  = array(
			'post_type'   => isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : self::DEFAULT_POST_TYPE,
			'paged'       => sanitize_text_field( $_POST['page'] ),
			'post_status' => 'publish',
		);

		$args              = wp_parse_args( $query, $args );
		$template_callback = self::get_template_for_post_type( sanitize_text_field( $_POST['post_type'] ) );

		if ( empty( $template_callback ) ) {
			wp_send_json_error(array(
				'message' => esc_html__( 'No template found for current post type', 'zn_framework' ),
			));
		}

		query_posts( $args );

		if ( have_posts() ) {
			// run the loop
			while ( have_posts() ) {
				the_post();
				call_user_func_array( $template_callback['callback'], $template_callback['args'] );
			}
		}

		die;
	}


	/**
	 * Get Button Markup
	 *
	 * Will return the load more button HTML markup based on provided arguments
	 *
	 * @param array $config The button/post type config
	 *
	 * @return string The HTML markup for the load more button
	 */
	public static function get_button_markup( $config = array() ) {
		global $wp_query;

		if ( 1 == $wp_query->max_num_pages || ! have_posts() ) {
			return;
		}

		$defaults = array(
			'post_type'       => self::DEFAULT_POST_TYPE,
			'nonce'           => wp_create_nonce( self::NONCE_ACTION ),
			'content_wrapper' => false,
		);

		$config = wp_parse_args( $config, $defaults );

		$button_id    = 'znhg-load-more-' . self::$button_instance;
		$current_page = get_query_var( 'paged' );
		$current_page = $current_page ? $current_page : 1;
		ob_start(); ?>
		<div class="more-wrapper txt-center">
		<script>
			jQuery(document).on( 'themeJsLoaded', function( event, themeJsFw ){
				themeJsFw.loadMoreBehaviour({
					button_selector: '#<?php echo esc_js( $button_id ); ?>',
					wp_query_vars: <?php echo wp_json_encode( $wp_query->query_vars ); ?>,
					current_page: <?php echo (int) $current_page; ?>,
					max_page: <?php echo esc_js( $wp_query->max_num_pages ); ?>,
					post_type: '<?php echo esc_js( $config['post_type'] ); ?>',
					nonce: '<?php echo esc_js( $config['nonce'] ); ?>',
					content_wrapper: '<?php echo esc_js( $config['content_wrapper'] ); ?>'
				});
			});
		</script>
			<div id="<?php echo esc_attr( $button_id ); ?>" class="js-minstor_loadmore more-trigger flex">
				<span class="cross flex-center">&nbsp;</span>
			</div>
		</div>
		<?php

		$markup = ob_get_clean();
		self::$button_instance++;

		return apply_filters( 'znhg_theme_load_more_markup', $markup );
	}
}

new ZnHgThemeComponent_AjaxLoadMore();
