<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Exceptions
{
    /**
     * Api response hata mesajlarını standartlaştırmak için kullanılan sınıf
     */
    public static function handle($request, \Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            return response()->json(['errors' => $exception->validator->errors(), 'status_code' => 400, 'status' => false, 'message' => $exception->getMessage()], 400);
        }
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['errors' => 'Item Not found', 'status_code' => 404, 'status' => false], 404);
        }
        if ($exception instanceof AuthenticationException) {
            return response()->json(['errors' => 'Unauthenticated', 'status_code' => 401, 'status' => false], 401);
        }
        if ($exception instanceof AuthorizationException) {
            return response()->json(['errors' => 'Unauthorized', 'status_code' => 403, 'status' => false], 403);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['errors' => 'Not found', 'status_code' => 404, 'status' => false], 404);
        }
        if ($exception instanceof InternalErrorException) {

            return response()->json(['errors' => 'Internal Server Error', 'status_code' => 500, 'status' => false], 500);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['errors' => 'Method Not Allowed', 'status_code' => 405, 'status' => false], 405);
        }
        if ($exception instanceof \Laravel\Horizon\Exceptions\ForbiddenException) {
            return response()->json(['errors' => 'Unauthenticated', 'status_code' => 403, 'status' => false], 403);
        }

        return response()->json([
            'message' => $exception->getMessage(),
            'status_code' => 500,
            'status' => false,
            'errors' => $exception->getTrace(),
        ], 500);

    }
}
