<?php

namespace App\Traits;

trait JsonData
{
    /**
     * Display exception errors of request.
    */
    public function jsonMsgResult($errors, $success, $statusCode)
    {
        $result = [
            'errors' => $errors,
            'success' => $success,
            'statusCode' => $statusCode,
        ];

        return response()->json($result, $result['statusCode']);
    }

    /**
     * Return data json format
     */
    public function jsonDataResult($data, $statusCode)
    {
        $result = [
            'data' => $data,
            'statusCode' => $statusCode,
        ];
        return response()->json($result, $result['statusCode']);

    }
}
