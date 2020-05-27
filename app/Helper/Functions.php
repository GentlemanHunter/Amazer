<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use App\ExceptionCode\ApiCode;
use App\Helper\JwtHelper;

function user_func(): string
{
    return 'hello';
}

if (!function_exists('apiError')) {


    /**
     * @param $code
     * @param string $msg
     * @return \Swoft\Http\Message\Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    function apiError($code = -1, $msg = 'Error')
    {
        $code = ($code == 0) ? -1 : $code;
        $msg = ApiCode::$errorMessages[$code] ?? $msg;
        $result = [
            'code' => $code,
            'msg' => $msg,
        ];
        return context()->getResponse()->withStatus(200)->withData($result);
    }
}

if (!function_exists('apiSuccess')) {

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     * @return \Swoft\Http\Message\Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    function apiSuccess($data = [], $code = 0, $msg = 'Success')
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return context()->getResponse()->withStatus(200)->withData($result);
    }
}

if (!function_exists('throwApiException')) {

    /**
     * @param $code
     * @param string $msg
     * @param string $file
     * @param string $trace
     * @return \Swoft\Http\Message\Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    function throwApiException($code, $msg = 'Error', $file = '', $trace = '')
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
        ];
        if (APP_DEBUG) {
            $result = array_merge($result, [
                'file' => $file,
                'trace' => $trace
            ]);
        }
        return context()->getResponse()->withStatus(200)->withData($result);
    }
}
