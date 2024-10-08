<?php
require_once __DIR__ . '/error.php';

/**
 * API Response
 */
class Meow_WPLR_Sync_Response {

	/**
	 * HTTP Status Messages
	 */
	protected static $STATUS_MSGS = array (
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing', // WebDAV; RFC 2518
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information', // since HTTP/1.1
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status', // WebDAV; RFC 4918
		208 => 'Already Reported', // WebDAV; RFC 5842
		226 => 'IM Used', // RFC 3229
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other', // since HTTP/1.1
		304 => 'Not Modified',
		305 => 'Use Proxy', // since HTTP/1.1
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect', // since HTTP/1.1
		308 => 'Permanent Redirect', // approved as experimental RFC
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot', // RFC 2324
		419 => 'Authentication Timeout', // not in RFC 2616
		420 => 'Enhance Your Calm', // Twitter
		420 => 'Method Failure', // Spring Framework
		422 => 'Unprocessable Entity', // WebDAV; RFC 4918
		423 => 'Locked', // WebDAV; RFC 4918
		424 => 'Failed Dependency', // WebDAV; RFC 4918
		424 => 'Method Failure', // WebDAV)
		425 => 'Unordered Collection', // Internet draft
		426 => 'Upgrade Required', // RFC 2817
		428 => 'Precondition Required', // RFC 6585
		429 => 'Too Many Requests', // RFC 6585
		431 => 'Request Header Fields Too Large', // RFC 6585
		444 => 'No Response', // Nginx
		449 => 'Retry With', // Microsoft
		450 => 'Blocked by Windows Parental Controls', // Microsoft
		451 => 'Redirect', // Microsoft
		451 => 'Unavailable For Legal Reasons', // Internet draft
		494 => 'Request Header Too Large', // Nginx
		495 => 'Cert Error', // Nginx
		496 => 'No Cert', // Nginx
		497 => 'HTTP to HTTPS', // Nginx
		499 => 'Client Closed Request', // Nginx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates', // RFC 2295
		507 => 'Insufficient Storage', // WebDAV; RFC 4918
		508 => 'Loop Detected', // WebDAV; RFC 5842
		509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
		510 => 'Not Extended', // RFC 2774
		511 => 'Network Authentication Required', // RFC 6585
		598 => 'Network read timeout error', // Unknown
		599 => 'Network connect timeout error' // Unknown
	);

	protected
		$success = true,
		$status = 200,
		$header = '',
		$data = null,
		$error = null,
		$nonce = null;

	/**
	 * @return boolean
	 */
	public function isSuccessful() {
		return $this->success;
	}

	/**
	 * @return number
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getStatusMessage() {
		return isset(static::$STATUS_MSGS[$this->getStatus()]) ? static::$STATUS_MSGS[$this->getStatus()] : '';
	}

	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @return Meow_WPLR_Sync_Error
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @param boolean $X
	 * @return Meow_WPLR_Sync_Response This
	 */
	public function setSuccessful( $X ) {
		$this->success = $X;
		return $this;
	}

	/**
	 * @param int $X
	 * @return Meow_WPLR_Sync_Response This
	 */
	public function setStatus( $X ) {
		$this->status = $X;
		return $this;
	}

	/**
	 * @param mixed $X
	 * @return Meow_WPLR_Sync_Response This
	 */
	public function setData( $X ) {
		$this->data = $X;
		return $this;
	}

	/**
	 * @param Meow_WPLR_Sync_Error $X
	 * @return Meow_WPLR_Sync_Response This
	 */
	public function setError( $X ) {
		$this->error = $X;
		return $this;
	}

	public function setNonce( $X ) {
		$this->nonce = $X;
		return $this;
	}

	/**
	 * Sends a JSON response
	 */
	public function send() {
		wp_send_json( $this->toArray(), $this->getStatus() );
	}

	/**
	 * Transforms to an associative array representation
	 * @return array
	 */
	public function toArray() {
		$r = array (
			'success' => $this->isSuccessful(),
			'code'    => $this->getStatus(),
			'status'  => $this->getStatusMessage(),
			'nonce'   => $this->nonce
		);
		if ( $x = $this->getError() ) $r['error'] = $x;
		if ( $x = $this->getData() ) $r['data'] = $x;
		return $r;
	}
}