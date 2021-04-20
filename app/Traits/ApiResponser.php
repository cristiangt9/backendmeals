<?php

namespace app\Traits;

use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
	protected function defaultJsonResponse($success, $title, $message = "", $messages = [], $data = null, $code = 200)
	{

		return response()->json(
			[
				"success" => $success,
				"title" => $title,
				"message" => $message,
				"messages" => $messages,
				"data" => $data,
				"code" => $code,
			],
			$code
		);
	}

	protected function defaultJsonResponseWithoutData($success, $title, $message = "", $messages = [], $code = 200)
	{

		return response()->json(
			[
				"success" => $success,
				"title" => $title,
				"message" => $message,
				"messages" => $messages,
				"data" => null,
				"code" => $code
			],
			$code
		);
	}
	/*@param mixed $request
	*@param mixed $rules
	*@param array $messages
	*@param array $customAttributes
	*@return array["validated"=>$validated,"errors"=>$errors]
	*/
	protected function validateRequestJson($request, $rules, array $messages = [], array $customAttributes = [])
	{
		$validated = false;
		$errors = [];

		$validator = Validator::make($request, $rules, $messages, $customAttributes);

		if ($validator->fails())
			foreach ($validator->errors()->toArray() as $campo => $e)
				$errors = array_merge($errors, $e);

		else
			$validated = true;

		return (object)["validated" => $validated, "errors" => $errors];
	}
}
