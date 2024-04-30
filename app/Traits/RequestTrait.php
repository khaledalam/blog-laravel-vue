<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait RequestTrait {
    public function getJsonResponse($data, string $message, $status = 1, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
