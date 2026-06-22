<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data, int $code = 200): JsonResponse
    {
        return response()->json([
            'code'  => $code,
            'data' => $data,
        ], $code);
    }

    protected function message(string $messageKey, int $code = 200): JsonResponse
    {
        $message = config("responses.messages.{$messageKey}", $messageKey);

        return response()->json([
            'code'  => $code,
            'msg' => $message,
        ], $code);
    }

    protected function error(string $messageKey, int $code = 500): JsonResponse
    {
        $message = config("responses.messages.{$messageKey}", $messageKey);

        return response()->json([
            'code'  => $code,
            'msg' => $message,
        ], $code);
    }

    protected function custom(array $data, int $code = 200): JsonResponse
    {
        return response()->json([
            'code'  => $code,
            'data' => $data,
        ], $code);
    }
}
