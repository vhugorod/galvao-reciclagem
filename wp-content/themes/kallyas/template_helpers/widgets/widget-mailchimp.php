<?php if(! defined('ABSPATH')){ return; }

/**
 * Class ZN_Mailchimp_Widget
 *
 * MailChimp Newsletter
 *
 * @since 3.0.0
 */
class ZN_Mailchimp_Widget extends WP_Widget
{
	/**
	 * Holds the nonce to validate when submitting the form
	 * @var null|string
	 */
	private $_nonce = null;

	function __construct()
	{
		$widget_ops = array ( 'description' => __( 'Use this widget to add a MailChimp newsletter to your site.', 'zn_framework' ) );
		parent::__construct( 'zn_mailchimp', __( '[ Kallyas ] Mailchimp Newsletter', 'zn_framework' ), $widget_ops );
		$this->_nonce = wp_create_nonce( 'zn_hg_mailchimp' );
	}

	function widget( $args, $instance )
	{
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$mcApiKey = zget_option( 'mailchimp_api', 'general_options' );
		$double_opt_in = zget_option( 'mailchimp_double_opt_in', 'general_options', false, 'no' );
		$msg = '';

		if ( empty( $mcApiKey ) ) {
			echo '<div class="newsletter-signup kl-newsletter">';
			echo sprintf(
				'%s <a href="%s" target="_blank">%s</a> %s'
				, __( 'Please enter your <strong>MailChimp API KEY</strong> in the', 'zn_framework' )
				, admin_url( 'admin.php?page=zn_tp_general_options#mailchimp_options' )
				, __( 'theme options panel', 'zn_framework' )
				, __( 'prior to using this widget.', 'zn_framework' )
			);
			echo '	</div><!-- end newsletter-signup -->';
			return;
		}

		echo $args['before_widget'];

		echo '<div class="newsletter-signup kl-newsletter-wrapper">';

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		// GET INTRO TEXT
		if ( ! empty( $instance['zn_mailchimp_intro'] ) ) {
			echo '<p>' . $instance['zn_mailchimp_intro'] . '</p>';
		}

		$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( "JOIN US", 'zn_framework' );

		if(isset($instance['zn_mailchimp_list']) && !empty($instance['zn_mailchimp_list']) ){

			// Results wrapper
			echo '<div class=" js-mcForm-result dn-widgetNewsletter-result"></div>';

			echo '<form method="post" class="js-mcForm newsletter_subscribe newsletter-signup kl-newsletter clearfix" data-url="' . trailingslashit( home_url() ) . '" name="newsletter_form">';
			echo '	<input type="text" name="mc_email" class="nl-email kl-newsletter-field form-control js-mcForm-email" value="" placeholder="' . __( "your.address@email.com", 'zn_framework' ) . '" />';
			echo '	<input type="hidden" name="mailchimp_list" class="nl-lid" value="' . $instance['zn_mailchimp_list'] . '" />';
            echo '  <input type="hidden" name="nonce" value="'.esc_attr($this->_nonce).'" class="zn_hg_mailchimp"/>';
            echo '  <input type="hidden" name="action" value="hg_mailchimp_register" />';
			echo '	<input type="submit" name="submit" class="kl-newsletter-submit kl-font-alt btn btn-fullcolor" value="' . $button_text . '" />';

			echo Hg_Mailchimp_GDPR::getConsentMarkup();

			echo '</form>';
		}
		else {
			echo '<span class="zn_mailchimp_result kl-newsletter-result"><span><b>' . __( 'Please select a list.', 'zn_framework' ) . '</span></span>';
		}

		// GET INTRO TEXT
		if ( ! empty( $instance['zn_mailchimp_outro'] ) ) {
			echo '<p>' . $instance['zn_mailchimp_outro'] . '</p>';
		}

		echo '	</div><!-- end newsletter-signup -->';
		echo $args['after_widget'];

	}

	function update( $new_instance, $old_instance )
	{
		$instance['title']              = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['button_text']        = strip_tags( stripslashes( $new_instance['button_text'] ) );
		$instance['zn_mailchimp_intro'] = stripslashes( $new_instance['zn_mailchimp_intro'] );
		$instance['zn_mailchimp_outro'] = stripslashes( $new_instance['zn_mailchimp_outro'] );
		$instance['zn_mailchimp_list']  = $new_instance['zn_mailchimp_list'];
		return $instance;
	}

	function form( $instance )
	{
		$title              = isset( $instance['title'] ) ? $instance['title'] : '';
		$button_text        = isset( $instance['button_text'] ) ? $instance['button_text'] : '';
		$zn_mailchimp_intro = isset( $instance['zn_mailchimp_intro'] ) ? $instance['zn_mailchimp_intro'] : '';
		$zn_mailchimp_outro = isset( $instance['zn_mailchimp_outro'] ) ? $instance['zn_mailchimp_outro'] : '';
		$zn_mailchimp_list  = isset( $instance['zn_mailchimp_list'] ) ? $instance['zn_mailchimp_list'] : '';
		$mcApiKey = zget_option( 'mailchimp_api', 'general_options' );
		$mail_lists = array();

		if ( ! function_exists( 'curl_init' ) ) {
			echo __( 'Curl is not enabled on your hosting environment. Please contact your hosting company and ask them to enable CURL for your account.', 'zn_framework' );
			return;
		}

		if ( empty ( $mcApiKey ) ) {
			echo __( 'Please enter your MailChimp API KEY in the theme options pannel prior of using this widget.', 'zn_framework' );
			return;
		}

		if ( ! empty ( $mcApiKey ) ) {
			Hg_Mailchimp::loadHgMcApiClass();

			$mcapi = new HG_MCAPI( $mcApiKey );
			$lists = $mcapi->getLists();

			$mail_lists = array ( '' => __('Select List ID','zn_framework'));
		    if ( ! empty( $lists['lists'] ) ) {
		        foreach ( $lists['lists'] as $key => $value ) {
		            $mail_lists[ $value['id'] ] = $value['name'];
		        }
		    }
		}
		?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'zn_framework' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_list' ) ); ?>"><?php _e( 'Select List:', 'zn_framework' ); ?></label>
		<select id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_list' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_list' ) ); ?>">

		<?php
		foreach ( $mail_lists as $key => $value ) {
			$selected = ( isset( $zn_mailchimp_list ) && $zn_mailchimp_list == $key ) ? ' selected="selected" ' : '';
			?>
			<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
		<?php
		}
		?>
		</select>
	</p>

	<p>
		<div><label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_intro' ) ); ?>"><?php echo __( 'Intro Text :', 'zn_framework' ); ?></label></div>
		<div><textarea id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_intro' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_intro' ) ); ?>" cols="35" rows="5"><?php echo $zn_mailchimp_intro; ?></textarea></div>
	</p>
	<p>
		<div><label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_outro' ) ); ?>"><?php echo __( 'After Form Text :', 'zn_framework' ); ?></label></div>
		<div><textarea id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_outro' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_outro' ) ); ?>" cols="35" rows="5"><?php echo $zn_mailchimp_outro; ?></textarea></div>
	</p>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Button text:', 'zn_framework' ) ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" value="<?php echo esc_attr( $button_text ); ?>" />
	</p>
	<?php
	}
}
function register_widget_ZN_Mailchimp_Widget(){
	register_widget( "ZN_Mailchimp_Widget" );
}
add_action( 'widgets_init', 'register_widget_ZN_Mailchimp_Widget' );
