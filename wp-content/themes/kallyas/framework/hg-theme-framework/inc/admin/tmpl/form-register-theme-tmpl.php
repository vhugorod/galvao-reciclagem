<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

//#! Default GET request
$dashIsConnected  = ZN_HogashDashboard::isConnected();
$dashIsPostAction = ( isset( $GLOBALS['dashRegisterPostAction'] ) && ! empty( $GLOBALS['dashRegisterPostAction'] ) );
if ( $dashIsPostAction ) {
	if ( isset( $GLOBALS['dashRegisterPostAction']['success'] ) ) {
		$dashIsConnected = (bool) $GLOBALS['dashRegisterPostAction']['success'];
	}
}
$dash_api_key = ZN_HogashDashboard::getApiKey();
?>
<div class="zn-registerContainer">

	<div class="znfb-row">

		<div class="znfb-col-12">
			<h3 class="zn-lead-title">
				<strong><?php esc_html_e( 'Register', 'zn_framework' ); ?> <?php echo ZNHGTFW()->getThemeName() ?></strong></h3>
		</div>

		<div class="znfb-col-7">

			<?php if ( ! $dashIsConnected ) {
	?>
				<div class="zn-lead-text">
					<div class="zn-adminNotice zn-adminNotice-info">
						<p>
							<strong><?php esc_html_e( 'To enjoy the full experience we strongly recommend to register.', 'zn_framework' ); ?> <?php echo ZNHGTFW()->getThemeName() ?></strong>
						</p>

						<p><?php echo sprintf( __( 'By connecting your theme with <a href="%s" target="%s">Hogash Dashboard</a>, you will get theme updates, sample data demo packs and notifications about cool new features.', 'zn_framework' ), '//my.hogash.com/', esc_attr( '_blank' ) ); ?></p>
					</div>
					<h3><strong><?php esc_html_e( 'Please follow these steps:', 'zn_framework' ); ?></strong>
					</h3>
					<ul class="zn-dashRegister-steps">
						<?php
							$allowed_html = array(
								'strong' => array(),
							); ?>
						<li><?php echo sprintf( '1) <a href="%s" target="%s"> ' . esc_html__( 'Register to Hogash Customer Dashboard', 'zn_framework' ) . '</a>' . esc_html__( 'with your Envato Account', 'zn_framework' ), '//my.hogash.com/', esc_attr( '_blank' ) ); ?></li>
						<li><?php echo sprintf( __( '2) Access "<a href="%s" target="">My Products</a>" section of the dashboard and make sure you have at least one purchase of the theme.', 'zn_framework' ), '//my.hogash.com/register-products/', '_blank' ); ?></li>
						<li><?php esc_html_e( '3) Click on the Generate Key button and than copy the Key.', 'zn_framework' ); ?></li>
						<li><?php echo wp_kses( __( '4) Insert/paste the generated API Key you just copied, into the right side "HOGASH API KEY" form. Click the <strong>Connect</strong> button.', 'zn_framework' ), $allowed_html ); ?></li>
					</ul>
				</div>
				<?php
} else {
								?>
				<div class="zn-lead-text">
					<div class="zn-adminNotice zn-adminNotice-info">
						<p><strong><?php echo esc_html( sprintf( __( 'You have successfully activated your copy of %s theme. ', 'zn_framework' ), ZNHGTFW()->getThemeName() ) ); ?></strong></p>

						<p><?php esc_html_e( 'If you plan on migrating / changing the domain of this website, please unlink this domain first.', 'zn_framework' ); ?></p>
					</div>
				</div>
			<?php
							} ?>

		</div>

		<div class="znfb-col-5">
			<?php
			if ( $dashIsPostAction && ! empty( $GLOBALS['dashRegisterPostAction']['data'] ) ) {
				$cssClass = ( $GLOBALS['dashRegisterPostAction']['success'] ? 'success' : 'error' ); ?>
				<div class="zn-adminNotice zn-adminNotice-<?php echo esc_attr( $cssClass); ?>">
					<p><?php echo '' . $GLOBALS['dashRegisterPostAction']['data']; ?></p></div>
				<?php
			}
			?>
			<form action="" class="zn-about-register-form zn-dashRegister-form" method="post">

				<div class="zn-dashRegister-status">
					<?php esc_html_e( 'Status:', 'zn_framework' ); ?>
					<?php
					if ( ! $dashIsConnected ) {
						echo '<strong class="zn-dashRegister-statusName">' . esc_html__( 'NOT CONNECTED', 'zn_framework' ) . '</strong>';
					} else {
						echo '<strong class="zn-dashRegister-statusName is-connected">' . esc_html__( 'CONNECTED', 'zn_framework' ) . '</strong>';
					}
					?>
				</div>

				<!--// Displays the ajax result on single installations -->
				<div id="zn-register-theme-alert"></div>


				<div class="zn-about-form-field zn-dashRegister-formMain">
					<label for="hg_api_key"><?php esc_html_e( 'Hogash API key', 'zn_framework' ); ?></label>

					<input type="text" id="hg_api_key" name="dash_api_key" class="zn-about-register-form-api"
						   value="<?php echo esc_attr( $dash_api_key); ?>"
						   placeholder="<?php esc_attr_e( 'XXXXX-XXXXX-XXXXX-XXXXX-XXXXX', 'zn_framework' ); ?>">
				</div>

				<?php wp_nonce_field( 'zn_theme_registration', 'zn_nonce' ); ?>
				<input type="submit"
					   class="zn-about-register-form-submit zn-dashRegister-formSubmit zn-about-action zn-action-green zn-action-md"
					   value="<?php esc_attr_e( 'Connect', 'zn_framework' ); ?>">
				<?php
				//#! Display the unlink button if the theme is connected and the api key is not managed
				if ( $dashIsConnected ) {
					?>
					<a href="#" id="unlink_theme_button" class=""
					   data-confirm="<?php esc_attr_e( 'Are you sure you want to unlink this domain?', 'zn_framework' ); ?>"
						data-button-cancel="<?php esc_attr_e( 'Cancel', 'zn_framework' ); ?>"
						data-button-ok="<?php esc_attr_e( 'Unlink', 'zn_framework' ); ?>"
						><?php esc_html_e( 'Unlink domain', 'zn_framework' ); ?></a>
					<?php
				}
				?>
			</form>

		</div>

		<div class="znfb-col-12">
			<hr class="zn-dashRegister-sep">
		</div>

		<div class="znfb-col-12">
			<div class="zn-dashRegister-infoList">

				<?php
					$registerUrl          = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/';
					$whyNotActiveUrl      = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/#why_not_active';
					$curlErrorUrl         = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/#error-curl-error-28-connect-timed-out';
					$registrationBenefits = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/#registration_benefits';
					$whatIsNeededUrl      = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/#what_is_needed';
					$howToVerifyApiKeyUrl = 'https://my.hogash.com/documentation/how-to-register-kallyas-theme/#how_to_verify_api_key';
				?>


				<h4 class="zn-dashRegister-tutorial"><?php echo sprintf( __( 'Having problems? <a href="%s" target="_blank">Read the tutorial</a>', 'zn_framework' ), $registerUrl ); ?></h4>

				<h3><?php echo esc_html( __( 'Troubleshooting', 'zn_framework' ) ); ?></h3>
				<ul>
					<li><?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'Why is my API key inactive?', 'zn_framework' ) . '</a>', $whyNotActiveUrl ); ?></li>
					<li><?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'ERROR: cURL error 28: connect() timed out!', 'zn_framework' ) . '</a>', $curlErrorUrl ); ?></li>
				</ul>

				<h3><?php echo esc_html( __( 'Frequently Asked Questions', 'zn_framework' ) ); ?></h3>
				<ul>
					<li><?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'What are the benefits of registration?', 'zn_framework' ) . '</a>', $registrationBenefits ); ?></li>
					<li><?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'Why do I need to register my theme?', 'zn_framework' ) . '</a>', $whatIsNeededUrl ); ?></li>
					<li><?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'How can I verify my API Key?', 'zn_framework' ) . '</a>', $howToVerifyApiKeyUrl ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
