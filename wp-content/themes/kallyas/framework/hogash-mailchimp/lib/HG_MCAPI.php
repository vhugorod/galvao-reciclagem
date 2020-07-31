<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class HG_MCAPI
 *
 * Helper class to interact with mailChimp API v3.0
 *
 * TODO: Validate API response, ensure it's valid
 * TODO: build error search from URL retrieved in the API response. see : $errorSearchUrl, $errorCode, $errorMessage
 * TODO: Build a better error response message
 */
class HG_MCAPI {
	//<editor-fold desc=":: CLASS VARS ::">
	public $version = '1.0';

	private $_apiKey    = '';
	private $_config    = array();
	private $_serverUrl = '';

	public $errorMessage   = '';
	public $errorCode      = '';
	public $errorSearchUrl = '';

	public $responseCode = 0;

	//</editor-fold desc=":: CLASS VARS ::">

	/**
	 * HG_MCAPI constructor.
	 *
	 * @param string $userName
	 * @param string $apiKey
	 * @param mixed $config
	 */
	public function __construct( $apiKey, $config = array() ) {
		$this->config = wp_parse_args( $config, array(
			'opt_in' => true,
		));

		$dc = 'us1';
		if ( strstr( $apiKey, '-' ) ) {
			$parts = explode( '-', $apiKey, 2 );
			if ( ! isset( $parts[ 1 ] ) || empty( $parts[ 1 ] ) ) {
				$dc = 'us1';
			} else {
				$dc = $parts[ 1 ];
			}
		}
		$this->_apiKey    = $apiKey;
		$this->_serverUrl = 'https://' . esc_attr( $dc ) . '.api.mailchimp.com/3.0/';
	}

	/**
	 * Retrieve all of the lists defined for your user account
	 * @param int    $start
	 * @param int    $limit
	 * @param string $sort_field
	 * @param string $sort_dir
	 * @return array|mixed|object
	 */
	public function getLists( $start = 0, $limit = 25, $sort_field = 'date_created', $sort_dir = 'DESC' ) {
		$params = array(
			'offset'     => $limit,
			'count'      => $start,
			'sort_field' => $sort_field,
			'sort_dir'   => $sort_dir,
		);
		return $this->__getLists( $params );
	}

	private function __getLists( $params = array() ) {
		$url = $this->_serverUrl . 'lists?count=999&offset=0';

		$json = json_encode( $params );

		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $this->_apiKey );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'PHP-HG-MCAPI/1.0' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );

		//#! TODO: VALIDATE RESPONSE
		$response = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		$this->__buildErrorMessage( $response, $httpCode );
		return ( 200 == $this->responseCode ? json_decode( $response, true ) : array( 'lists' => array(), 'total_items' => 0 ) );
	}

	/**
	 * Subscribe the provided email to a list.
	 *
	 * @param string $listID the list id to connect to.
	 * @param string $email_address the email address to subscribe
	 * @param array  $merge_vars optional merges for the email (FNAME, LNAME, etc.) (see examples below for handling "blank" arrays). Note that a merge field can only hold up to 255 bytes. Also, there are a few "special" keys:
	 * string EMAIL set this to change the email address. This is only respected on calls using update_existing or when passed to listUpdateMember()
	 * string NEW-EMAIL set this to change the email address. This is only respected on calls using update_existing or when passed to listUpdateMember(). Required to change via listBatchSubscribe() - EMAIL takes precedence on other calls, though either will work.
	 * array GROUPINGS Set Interest Groups by Grouping. Each element in this array should be an array containing the "groups" parameter which contains a comma delimited list of Interest Groups to add. Commas in Interest Group names should be escaped with a backslash. ie, "," =&gt; "\," and either an "id" or "name" parameter to specify the Grouping - get from listInterestGroupings()
	 * string OPTIN_IP Set the Opt-in IP field. <em>Abusing this may cause your account to be suspended.</em> We do validate this and it must not be a private IP address.
	 * string OPTIN_TIME Set the Opt-in Time field. <em>Abusing this may cause your account to be suspended.</em> We do validate this and it must be a valid date. Use YYYY-MM-DD HH:ii:ss to be safe. Generally, though, anything strtotime() understands we'll understand - <a href="http://us2.php.net/strtotime" target="_blank">http://us2.php.net/strtotime</a>
	 * array MC_LOCATION Set the members geographic location. By default if this merge field exists, we'll update using the optin_ip if it exists. If the array contains LATITUDE and LONGITUDE keys, they will be used. NOTE - this will slow down each subscribe call a bit, especially for lat/lng pairs in sparsely populated areas. Currently our automated background processes can and will overwrite this based on opens and clicks.
	 *
	 * @return boolean true on success, false on failure.
	 */
	public function subscribe( $listID, $email_address, $merge_vars = null ) {
		$params                    = array();
		$params[ 'email_address' ] = $email_address;
		$params[ 'email_type' ]    = 'html';
		$params[ 'status' ]        = $this->config['opt_in'] ? 'pending' : 'subscribed';
		$params[ 'merge_fields' ]  = $merge_vars;
		return $this->__subscribe( $listID, $params );
	}

	/**
	 * Subscribe a user to the specified list
	 * @param string $listID
	 * @param array  $data
	 * @return bool
	 */
	private function __subscribe( $listID, $data = array() ) {
		$memberId = md5( strtolower( $data[ 'email_address' ] ) );
		$url      = $this->_serverUrl . 'lists/' . $listID . '/members/' . $memberId;

		$json = json_encode( array(
			'email_address' => $data[ 'email_address' ],
			'status'        => $data[ 'status' ], // "subscribed","unsubscribed","cleaned","pending"
			'merge_fields'  => $data[ 'merge_fields' ],
		) );

		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $this->_apiKey );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'PHP-HG-MCAPI/1.0' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );

		//#! TODO: VALIDATE RESPONSE
		$response = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		$this->__buildErrorMessage( $response, $httpCode );
		return ( 200 == $this->responseCode );
	}

	/**
	 * Retrieve the error message if any
	 * @return string
	 */
	public function getErrorMessage() {
		$m = '';
		if ( 200 <> $this->responseCode ) {
			$m .= $this->errorMessage;
			$m .= '<a href="' . esc_url($this->errorSearchUrl) . '" target="_blank">' . __('More details', 'hogash-mailchimp') . '</a>';
		}
		return $m;
	}

	/**
	 * populate the internal vars with error details if any
	 * @param string $response
	 * @param int    $httpCode
	 */
	private function __buildErrorMessage( $response = '', $httpCode = 200 ) {
		if ( empty( $response ) ) {
			$this->errorMessage = __( 'Invalid response from MailChimp server.', 'hogash-mailchimp' );
			$this->responseCode = ( 200 == $httpCode ? 404 : $httpCode );
			return;
		} elseif ( 200 <> $httpCode ) {
			if ( is_string($response)) {
				$response = json_decode( $response, true );
			}

			if ( isset( $response[ 'type' ] ) ) {
				$this->errorSearchUrl = $response[ 'type' ];
			}

			if ( isset( $response[ 'title' ] ) ) {
				$this->errorMessage = $response[ 'title' ];
			}

			if ( isset( $response[ 'detail' ] ) ) {
				if ( ! empty( $this->errorMessage ) ) {
					$this->errorMessage .= ': ' . $response[ 'detail' ];
				} else {
					$this->errorMessage = $response[ 'detail' ];
				}
			}
		}
		$this->responseCode = $httpCode;
	}
}


