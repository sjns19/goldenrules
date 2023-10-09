<?php

class Response {
	public const ERROR = 0;
	public const SUCCESS = 1;

	public static function Send(string $message, int $type): string {
		switch ($type) {
			case self::ERROR:
				$status = 'error';
				break;
			case self::SUCCESS:
				$status = 'success';
				break;
		}
		
		return json_encode([
			'status' => $status,
			'message' => $message
		]);
	}
}