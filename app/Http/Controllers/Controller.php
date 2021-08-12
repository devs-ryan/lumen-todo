<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    private $http_status_codes = [404, 403, 401];

    /**
     * Output an API response in JSON.
     *
     * @param array $output
     * @param int $status_code
     * @return Response
     */
    protected function apiResponse($output, $status_code = 200)
    {
        return response()->json($output, $status_code);
    }

    /**
     * Output an API error response in JSON.
     *
     * @param mixed $err
     * @param int $status_code
     * @return Response
     */
    protected function apiError($err, $status_code = 400)
    {
        if ($err instanceof Exception) {
            $code = $err->getCode();
            $message = $err->getMessage();
            $status_code = in_array($code, $this->http_status_codes) ? $code : $status_code;
        }
        else
            $message = $err;

        return response()->json(['error' => $message], $status_code);
    }
}
