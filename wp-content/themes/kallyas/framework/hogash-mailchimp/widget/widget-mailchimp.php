<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Navigation Menu widget class
 *
 * @since 1.0.0
 */
class hg_Mailchimp_Widget extends WP_Widget
{
	/**
	 * Holds the nonce to validate when submitting the form
	 * @var null|string
	 */
	private $_nonce = null;

	function __construct()
    {
        $widget_ops = array( 'description' => __( 'Use this widget to add a MailChimp Newsletter to your site.', 'hogash-mailchimp' ) );
        parent::__construct( 'zn_mailchimp', __( '[ Hogash] MailChimp Newsletter', 'hogash-mailchimp' ), $widget_ops );
		$this->_nonce = wp_create_nonce( 'zn_hg_mailchimp' );
    }

    function widget( $args, $instance )
    {
        // Bail if a Hogash theme is not installed
        if ( ! function_exists( 'zget_option' ) ) {
            _e( 'This widget is only compatible with Hogash Themes', 'hogash-mailchimp' );
            return;
        }

        $instance[ 'title' ] = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );
        $mcApiKey            = zget_option( 'mailchimp_api', 'general_options' );
        $double_opt_in = zget_option( 'mailchimp_double_opt_in', 'general_options', false, 'no' );
        $msg = '';

        if ( empty( $mcApiKey ) ) {
            echo '<div class="newsletter-signup kl-newsletter">';
                echo '<p>';
                echo sprintf(
                    '%s <a href="%s" target="_blank">%s</a> %s'
                    , __( 'Please enter your <strong>MailChimp API KEY</strong> in the', 'hogash-mailchimp' )
                    , admin_url( 'admin.php?page=zn_tp_general_options#mailchimp_options' )
                    , __( 'theme options panel', 'hogash-mailchimp' )
                    , __( 'prior to using this widget.', 'hogash-mailchimp' )
                );
                echo '</p>';
            echo '</div>';
            return;
        }

        $widget_theme = ( isset( $instance[ 'widget_theme' ] ) ? esc_attr( $instance[ 'widget_theme' ] ) : 'dark' );

        echo $args[ 'before_widget' ];

        echo '<div class="dn-widgetNewsletter dn-widgetNewsletter--' . $widget_theme . '">';

        if ( !empty( $instance[ 'title' ] ) ) {
            echo $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
        }

        // GET INTRO TEXT
        if ( !empty( $instance[ 'zn_mailchimp_intro' ] ) ) {
            echo '<div class="dn-widgetNewsletter-intro">' . $instance[ 'zn_mailchimp_intro' ] . '</div>';
        }

        // Results wrapper
        echo '<div class=" js-mcForm-result dn-widgetNewsletter-result"></div>';

        $button_text = !empty( $instance[ 'button_text' ] ) ? $instance[ 'button_text' ] : __( "SUBSCRIBE", 'hogash-mailchimp' );

        if ( isset( $instance[ 'zn_mailchimp_list' ] ) && !empty( $instance[ 'zn_mailchimp_list' ] ) ) {
            echo '<form method="post" class="js-mcForm dn-widgetNewsletter-form" data-url="' . trailingslashit( home_url() ) . '" name="newsletter_form">';
            echo '	<input type="email" name="mc_email" class="form-control dn-widgetNewsletter-email js-mcForm-email" value="" placeholder="' . __( "your.address@email.com", 'hogash-mailchimp' ) . '" />';
            echo '	<input type="hidden" name="mailchimp_list" class="nl-lid" value="' . $instance[ 'zn_mailchimp_list' ] . '" />';
            echo '  <input type="hidden" name="nonce" value="'.esc_attr($this->_nonce).'" class="zn_hg_mailchimp"/>';
            echo '  <input type="hidden" name="action" value="hg_mailchimp_register" />';

            echo Hg_Mailchimp_GDPR::getConsentMarkup();

            echo '	<input type="submit" name="submit" class="dn-widgetNewsletter-submit btn btn-default ' . ( $widget_theme == 'dark' ? 'btn-default--whover' : '' ) . '" value="' . $button_text . '" />';
            echo '</form>';
        }
        else {
            echo '<div class="js-mcForm-result dn-widgetNewsletter-result"><strong>' . __( 'Error:', 'hogash-mailchimp' ) . '</strong> ' . __( 'Please select a list.', 'hogash-mailchimp' ) . '</div>';
        }

        // Outer text
        if ( !empty( $instance[ 'zn_mailchimp_outro' ] ) ) {
            echo '<div class="dn-widgetNewsletter-outro">' . $instance[ 'zn_mailchimp_outro' ] . '</div>';
        }

        echo '	</div><!-- /.dn-widgetNewsletter -->';

        echo $args[ 'after_widget' ];

    }

    function update( $new_instance, $old_instance )
    {
        $instance[ 'title' ]              = strip_tags( stripslashes( $new_instance[ 'title' ] ) );
        $instance[ 'button_text' ]        = strip_tags( stripslashes( $new_instance[ 'button_text' ] ) );
        $instance[ 'zn_mailchimp_intro' ] = stripslashes( $new_instance[ 'zn_mailchimp_intro' ] );
        $instance[ 'zn_mailchimp_outro' ] = stripslashes( $new_instance[ 'zn_mailchimp_outro' ] );
        $instance[ 'widget_theme' ]       = $new_instance[ 'widget_theme' ];
        $instance[ 'zn_mailchimp_list' ]  = $new_instance[ 'zn_mailchimp_list' ];
        return $instance;
    }

    function form( $instance )
    {
        $title              = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
        $button_text        = isset( $instance[ 'button_text' ] ) ? $instance[ 'button_text' ] : '';
        $zn_mailchimp_intro = isset( $instance[ 'zn_mailchimp_intro' ] ) ? $instance[ 'zn_mailchimp_intro' ] : '';
        $zn_mailchimp_outro = isset( $instance[ 'zn_mailchimp_outro' ] ) ? $instance[ 'zn_mailchimp_outro' ] : '';
        $zn_mailchimp_list  = isset( $instance[ 'zn_mailchimp_list' ] ) ? $instance[ 'zn_mailchimp_list' ] : '';

        if ( !function_exists( 'curl_init' ) ) {
            echo __( 'Curl is not enabled on your hosting environment. Please contact your hosting company and ask them to enable CURL for your account.', 'hogash-mailchimp' );
            return;
        }

        $mcApiKey = zget_option( 'mailchimp_api', 'general_options' );

        if ( empty ( $mcApiKey ) ) {
            echo sprintf(
                '<p>%s <a href="%s" target="_blank">%s</a> %s</p>'
                , __( 'Please enter your <strong>MailChimp API KEY</strong> in the', 'hogash-mailchimp' )
                , admin_url( 'admin.php?page=zn_tp_general_options#mailchimp_options' )
                , __( 'theme options panel', 'hogash-mailchimp' )
                , __( 'prior of using this widget.', 'hogash-mailchimp' )
            );
            return;
        }

        $mail_lists = array();
        if ( !empty ( $mcApiKey ) ) {
            Hg_Mailchimp::loadHgMcApiClass();

            $mcapi = new HG_MCAPI( $mcApiKey );
            $lists = $mcapi->getLists();

            $mail_lists = array( '' => __( 'Select List ID', 'hogash-mailchimp' ) );
            if ( !empty( $lists[ 'lists' ] ) ) {
                foreach ( $lists[ 'lists' ] as $key => $value ) {
                    $mail_lists[ $value[ 'id' ] ] = $value[ 'name' ];
                }
            }
        }

        $widgetTheme = isset( $instance[ 'widget_theme' ] ) ? esc_attr( $instance[ 'widget_theme' ] ) : 'dark';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'hogash-mailchimp' ) ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_list' ) ); ?>"><?php _e( 'Select List:', 'hogash-mailchimp' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_list' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_list' ) ); ?>">
                <?php
                if ( !empty( $mail_lists ) ) {
                    foreach ( $mail_lists as $key => $value ) {
                        $selected = ( isset( $zn_mailchimp_list ) && $zn_mailchimp_list == $key ) ? ' selected="selected" ' : '';
                        ?>
                        <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </p>

        <p>
        <div><label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_intro' ) ); ?>"><?php echo __( 'Intro Text :', 'hogash-mailchimp' ); ?></label></div>
        <div><textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_intro' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_intro' ) ); ?>" cols="35" rows="5"><?php echo $zn_mailchimp_intro; ?></textarea></div>
        </p>
        <p>
        <div><label for="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_outro' ) ); ?>"><?php echo __( 'After Form Text :', 'hogash-mailchimp' ); ?></label></div>
        <div><textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'zn_mailchimp_outro' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'zn_mailchimp_outro' ) ); ?>" cols="35" rows="5"><?php echo $zn_mailchimp_outro; ?></textarea></div>
        </p>
        <p>
            <label for="<?php echo esc_attr(  $this->get_field_id( 'button_text' ) ); ?>"><?php _e( 'Button text:', 'hogash-mailchimp' ) ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" value="<?php echo esc_attr( $button_text ); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'widget_theme' ) ); ?>"><?php _e( 'Widget Theme:', 'hogash-mailchimp' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'widget_theme' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_theme' ) ); ?>">
                <option value="light" <?php echo selected( 'light', $widgetTheme, false ); ?>><?php _e( 'Light', 'hogash-mailchimp' ); ?></option>
                <option value="dark" <?php echo selected( 'dark', $widgetTheme, false ); ?>><?php _e( 'Dark', 'hogash-mailchimp' ); ?></option>
            </select>
        </p>
        <?php
    }
}

/**
 * Register the widget
 */
function register_widget_hg_Mailchimp_Widget()
{
	if( ! class_exists('ZN_Mailchimp_Widget')) {
		register_widget( "hg_Mailchimp_Widget" );
	}
}
/*
 * Hook into action
 */
add_action( 'widgets_init', 'register_widget_hg_Mailchimp_Widget' );
