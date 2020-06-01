<?php declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use App\Exception\ApiException;
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

if (!function_exists('checkAuth')) {
    /**
     * @return bool|int
     * @throws \App\Exception\ApiException
     * @throws \Swoft\Db\Exception\DbException
     */
    function checkAuth()
    {
        $request = context()->getRequest();
        $token = $request->getCookieParams()['TOKEN_WHARF'] ?? '';
        if (!$token || !is_string($token) || !$userId = JwtHelper::decrypt($token)) {
            vdump($token);
            return false;
        }
        $userInfo = bean('App\Model\Dao\UserDao')->findUserInfoById($userId);
        if (!$userInfo) {
            vdump($userInfo);
            return false;
        }
        $request->user = $userId;
        $request->userInfo = $userInfo;

        return $userId;
    }
}

if (!function_exists('getGuid')) {
    /**
     * @param string $namespace
     * @return string
     */
    function getGuid($namespace = '')
    {
        static $guid = '';
        $server = context()->getResponse();
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $server->getHeaderLine('request_time');
        $data .= $server->getHeaderLine('HTTP_USER_AGENT');
        $data .= $server->getHeaderLine('LOCAL_ADDR');
        $data .= $server->getHeaderLine('LOCAL_PORT');
        $data .= $server->getHeaderLine('REMOTE_ADDR');
        $data .= $server->getHeaderLine('REMOTE_PORT');
        $data .= $server->getHeaderLine('REMOTE_PORT');
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash, 0, 8) .
            '-' .
            substr($hash, 8, 4) .
            '-' .
            substr($hash, 12, 4) .
            '-' .
            substr($hash, 16, 4) .
            '-' .
            substr($hash, 20, 12);
        return $guid;
    }
}

if (!function_exists('isJSON')) {
    /**
     * 判断是否json
     * @param $string
     * @return bool
     */
    function isJSON($string)
    {
        return is_string($string) &&
        is_array(json_decode($string, true)) &&
        (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('keyExists')) {
    /**
     * @param $array
     * @param $key
     * @throws ApiException
     */
    function keyExists($array, $key)
    {
        if (!is_array($array)) {
            if (!isJSON($array)) {
                throw new ApiException("array 不是 json", -1);
            }
            $array = json_decode($array, true);
        }
        if (!array_key_exists($key, $array))
            throw new ApiException("{" . $key . "} 不存在", -1);
    }
}

if (!function_exists('UID')) {
    /**
     * 获取用户 uid
     * @param \Swoft\Http\Message\Request|null $request
     * @return mixed
     */
    function UID(\Swoft\Http\Message\Request $request = null)
    {
        if ($request === null) {
            $request = context()->getRequest();
        }
        return $request->user;
    }
}

if (!function_exists('redisHashArray')){
    /**
     * 反序列化 redis 数据
     * @param $value
     * @return mixed
     */
    function redisHashArray($value)
    {
        $lists = array();
        array_push($lists,unserialize($value));
        return $lists[0];
    }
}

if (!function_exists('getUserInfo')){
    /**
     * 获取用户的 姓名
     * @param $uid
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Collection|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
     */
    function getUserInfo($uid){
        /** @var \App\Model\Dao\UserDao $userDao */
        $userDao = bean('App\Model\Dao\UserDao');
        return $userDao->findUserInfoById($uid);
    }
}
