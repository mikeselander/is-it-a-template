<?php
namespace isItaTemplate;

class JsonResponse {

	public static function error( $message ) {
		return [
			'status'  => 'error',
			'message' => $message,
		];
	}

	public static function success( $message, $data ) {
		return [
			'status'  => 'success',
			'message' => $message,
			'data'    => $data,
		];
	}
}
