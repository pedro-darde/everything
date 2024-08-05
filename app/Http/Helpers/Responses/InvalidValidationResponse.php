<?php

namespace App\Http\Helpers\Responses;

use Illuminate\Validation\ValidationException;

class InvalidValidationResponse
{
    static function send(ValidationException $ex): \Illuminate\Http\JsonResponse
    {
        $errorReturn = [];

        foreach ($ex->errors() as $field => $errors) {
            $errorReturn[$field] = join(' ', $errors);
        }

        return response()->json([
            'message' => 'Validation error',
            'errors' => $errorReturn
        ], 422);
    }
}
