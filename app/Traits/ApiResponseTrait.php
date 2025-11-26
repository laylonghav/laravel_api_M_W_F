<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function sessuccApiRespone($data, $message = "Successfully.", $code = 200)
    {
        return response()->json([
            "message" => $message,
            "data" => $data,
        ], $code);
    }

    protected function errorApiResponse($message, $code = 500)
    {
        return response()->json([
            "message" => $message
        ], $code);
    }
}
