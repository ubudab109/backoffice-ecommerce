<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
     /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $messages = null)
    {
        $response = [
            'status' => 'success',
            'data' => $result,
        ];

        if ($messages != null) {
            $response = [
                'status' => 'success',
                'messages' => $messages,
                'data' => $result,
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($error = 'failed', $errorMessages = [], $code = 500)
    {
        $response = [
            'status' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['messages'] = (is_null($errorMessages))?"Internal Server Error":$errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendNotFound($dataName = null, $code = 404)
    {
        $response = [
            'status' => 'failed',
        ];
        if ($dataName != null) {
            $response['messages'] = $dataName.' tidak ditemukan.';
        } else {
            $response['messages'] = 'Data tidak ditemukan';
        }

        return response()->json($response, $code);
    }

    /**
     * Send bad request if input wrong
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendBadRequest($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = (is_null($errorMessages))?"Internal Server Error":$errorMessages;
        }

        return response()->json($response, $code);
    }
}
