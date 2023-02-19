<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
    function apiResponse($data, $status, $msg)
    {
        if (isset($data)) {

            $res = [
                'data' => $data,
                'status' => $status,
                'msg' => $msg,
            ];

            return response($res);
        }
    }
}
