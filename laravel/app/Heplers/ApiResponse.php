<?php

declare(strict_types=1);

namespace App\Heplers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use Throwable;

class ApiResponse
{
    public static function success($result, ?string $message = null): JsonResponse
    {
        if ($result instanceof JsonResource) {
            $result = $result->resolve();
        }

        $response = [
            'success' => true,
            'data' => $result,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }

    public static function error(Exception | Throwable $exception, $errorCode = null): JsonResponse
    {
        $response = [
            'success' => false,
            'errors' => [
                'general' => $exception->getMessage(),
            ]
        ];

        // Определяем правильный HTTP статус код
        $httpCode = self::getHttpStatusCode($exception);

        return response()->json($response, $errorCode ?? $httpCode);
    }

    private static function getHttpStatusCode(Throwable $exception): int
    {
        // Для QueryException используем 500
        if ($exception instanceof QueryException) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // Для других исключений проверяем код
        $code = $exception->getCode();

        // Если код в допустимом диапазоне HTTP статус кодов
        if ($code >= 100 && $code <= 599) {
            return $code;
        }

        // По умолчанию 500
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
