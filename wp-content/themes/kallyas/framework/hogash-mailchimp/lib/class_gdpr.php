<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

class Hg_Mailchimp_GDPR {

    const __GDPR_INPUT_BASE_ID = 'hg-gdpr-checkbox_';

    static $inputIndex = 0;

    /**
     * Generate an unique ID for a GDPR checkbox field
     *
     * @param string $key The string that will be attached to the unique id base
     * @return string A unique string built from an unique base id and provided $key param
     */
    public static function generateId( $key ){
        return self::__GDPR_INPUT_BASE_ID . $key;
	}

    /**
     * Generate an unique ID for a GDPR checkbox field
     *
     * @param string $key The string that will be attached to the unique id base
     * @return string A unique string built from an unique base id and provided $key param
     */
    public static function generateUniqueId( $key ){
        self::$inputIndex += 1;
        return self::__GDPR_INPUT_BASE_ID . $key . self::$inputIndex;
	}

	/**
	 * Returns the HTML markup for consent checkboxes
	 */
	public static function getConsentMarkup(){
		// Add consent boxes
		$consent_boxes = zget_option( 'after_newsletter_boxes', 'general_options', false, array() );

		// Don't proceed if the gdpr text is empty
		if( ! is_array( $consent_boxes ) || empty( $consent_boxes ) ){
			return false;
		}

		foreach( $consent_boxes as $key => $textConfig ){

			if( empty( $textConfig['text'] ) ){
				continue;
			}

			$inputName = Hg_Mailchimp_GDPR::generateId($key);
			$inputId = Hg_Mailchimp_GDPR::generateUniqueId($key);

			?>
			<label class="znhg-gdpr-label" for="<?php echo esc_attr( $inputId ); ?>">
				<input type="checkbox" name="<?php echo esc_attr( $inputName ); ?>" id="<?php echo esc_attr( $inputId ); ?>" value="1"/>
				<?php echo $textConfig['text'] ?>
			</label>
		<?php
		}
	}

}