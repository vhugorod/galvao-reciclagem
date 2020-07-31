<?php if(! defined('ABSPATH')){ return; }

/**
 * Class ZnTypekitClient
 *
 * Facade class to integrate Typekit in the theme
 * @wp-kitten
 */
class ZnTypekitClient
{
	/**
	 * The token to use when contacting Typekit
	 * @var string
	 * @see ZnTypekitClient::__construct()
	 * @see https://typekit.com/account/tokens
	 */
	private $_token = '';

	/**
	 * ZnTypekitClient constructor.
	 *
	 * @param string|null $token The token you've got from Typekit
	 * @see https://typekit.com/account/tokens
	 */
	public function __construct( $token = null ){
		if( ! empty($token)) {
			$this->setToken( $token );
		}
	}

	public function setToken( $token ){
		$this->_token = wp_strip_all_tags( $token );
	}

	public function getAllKits() {
		if( empty($this->_token)){
			return null;
		}
		return $this->get();
	}

	public function getKitInfo( $kitID ) {
		if( empty($this->_token)){
			return null;
		}
		return $this->get( $kitID );
	}

	/**
	 * Execute a get request. This method can be used to retrieve the list of all kits or the information of the specified $kitID
	 * @param null|string $kitID The ID of the kit to retrieve the information for
	 * @return array|\WP_Error
	 */
	public function get( $kitID = null ) {
		if( empty($this->_token)){
			return new WP_Error( __( 'No token provided.', 'zn_framework') );
		}
		$args = array(
			'headers' => array(
				"X-Typekit-Token" => $this->_token,
				'Accept' => 'application/json',
				'Content-Type' => 'application/x-www-form-urlencoded'
			),
			'sslverify' => false,
		);

		$url = 'https://typekit.com/api/v1/json/kits';
		if( ! empty($kitID)){
			$url .= '/'.$kitID;
			$args['headers']['kit_id'] = $kitID;
		}

		$request = wp_remote_get( $url, $args );
		if( is_wp_error($request)){
			return $request;
		}
		$body = wp_remote_retrieve_body( $request );
		if( empty($body)){
			return new WP_Error( __( 'No data retrieved.', 'zn_framework') );
		}
		$body = json_decode( $body, true );
		if( isset($body['errors'])){
			$errorMessage = sprintf( __( 'No data retrieved: %s', 'zn_framework'), implode(', ', $body['errors']) );
			return new WP_Error( $errorMessage );
		}
		return $body;
	}

}
return new ZnTypekitClient();
