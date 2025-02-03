<?php

namespace App\Support\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    /**
     * Api responseleri standartlaştırmak için
     * kullanılan sınıf
     * 
     */
    public static function setData($data, ?string $message = null, int $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'data' => $data,
            'message' => $message ?? trans('api.success_message'),
        ])->setStatusCode($statusCode);
    }

    public static function item(JsonResource $item, $message = null, $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'data' => $item,
            'message' => $message ?? trans('api.success_message'),
        ])->setStatusCode($statusCode);
    }

    public static function createdMessage(): string
    {
        return trans('api.created_message');
    }

    public static function createdStatus(): int
    {
        return 201;
    }

    public static function collection(ResourceCollection $collection, $message = null, $statusCode = 200)
    {
        $return = [
            'status' => true,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.success_message'),
            'data' => $collection->response()->getData()->data,
        ];

        if (!empty($collection->response()->getData()->meta)) {
            $meta = $collection->response()->getData()->meta;
            $return['meta'] = $meta;
            unset($meta->links);
            unset($meta->path);
        }
        if (!empty($collection->response()->getData()->links)) {
            $links = $collection->response()->getData()->links;
        }

        return $return;
    }

    public static function setError($message = null, $statusCode = 400)
    {
        return response()->json([
            'status' => false,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.error_message'),
        ])->setStatusCode($statusCode);
    }

    public static function created($message = null, $statusCode = 201)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.created_message'),
        ])->setStatusCode($statusCode);
    }

    public static function success($message = null, $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.success_message'),
        ])->setStatusCode($statusCode);
    }

    public static function updated($message = null, $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.updated_message'),
        ])->setStatusCode($statusCode);
    }

    public static function deleted($message = null, $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'status_code' => $statusCode,
            'message' => $message ?? trans('api.deleted_message'),
        ])->setStatusCode($statusCode);
    }

    public static function error($message = null, $statusCode = 400)
    {
        $message = $message ?? trans('api.error_message');

        return response()->json([
            'status' => true,
            'status_code' => 400,
            'message' => $message ?? trans('api.deleted_message'),
        ])->setStatusCode($statusCode);
    }

    public static function backMessage($message = null, $status = true)
    {
        if (!$message) {
            return response()->json([
                'status' => $status,
                'status_code' => 400,
            ])->setStatusCode(400);
        }

        return response()->json([
            'status' => $status,
            'status_code' => 400,
            'message' => $message,
        ])->setStatusCode(400);
    }
}
