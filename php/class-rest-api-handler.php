<?php
namespace Rarst\wps;

use Whoops\Exception\Formatter;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Util\Misc;

/**
 * WordPress-specific version of Json handler for REST API.
 */
class Rest_Api_Handler extends JsonResponseHandler {

	/**
	 * @return bool
	 */
	private function isRestRequest() {

		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	/**
	 * @return int
	 */
	public function handle() {

		if ( ! $this->isRestRequest() ) {
			return Handler::DONE;
		}

		$data     = Formatter::formatExceptionAsDataArray( $this->getInspector(), $this->addTraceToOutput() );
		$response = array(
			'code'    => $data['type'],
			'message' => $data['message'],
			'data'    => $data,
		);

		if ( Misc::canSendHeaders() ) {
			status_header( 500 );
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		}

		$json_options = version_compare( PHP_VERSION, '5.4.0', '>=' ) ? JSON_PRETTY_PRINT : 0;

		echo wp_json_encode( $response, $json_options );

		return Handler::QUIT;
	}
}