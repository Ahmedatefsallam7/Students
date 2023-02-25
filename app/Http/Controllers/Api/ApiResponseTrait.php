<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
    function apiResponse($data, $msg)
    {
        if (isset($data)) {

            $result = [
                'data' => $data,
                'msg' => $msg,
            ];

            // return response($result);
            return response()->json($result);
        }
    }
}
