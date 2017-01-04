<?php
namespace isItaTemplate;

class JsonResponse {

	/**
	 * Send a normalized error.
	 */
	public static function error( $message ) {
		return [
			'status'  => 'error',
			'message' => $message,
		];
	}

	/**
	 * Send a normalized success message.
	 */
	public static function success( $message, $data ) {
		return [
			'status'  => 'success',
			'message' => $message,
			'data'    => $data,
		];
	}
}
