<?php
/**
 * 定义api报错
 * @author yxk yangxiukang@ketangpai.com
 */

namespace App\ExceptionCode;


class ApiCode
{
    // 未知错误
    const UNKNOWN = -1;
    // 成功返回状态
    const SUCCESS = 200;

    //基本错误码 0～1000
    const AUTH_ERROR = 401;
    const NO_PERMISSION_PROCESS = 402;
    const NO_DATA_AVAILABLE = 403;

    //用户错误码 3000～3999
    const USER_NOT_FOUND = 3001,
        USER_ID_INVALID = 3002,
        USER_PASSWORD_ERROR = 3003,
        USER_CREATE_APPLICATION_FAIL = 3004,
        USER_APPLICATION_SET_READ_FAIL = 3005,
        USER_INFO_MODIFY_FAIL = 3006,
        USER_APPLICATION_NOT_FOUND = 3007,
        USER_APPLICATION_PROCESSED = 3008,
        USER_APPLICATION_TYPE_WRONG = 3009,
        USER_ACCOUNT_ALREADY_USER = 3010;


    // ext 9000~9999
    const JWT_PRIVATE_KEY_EMPTY = 9001,
        JWT_PUBLIC_KEY_EMPTY = 9002,
        JWT_ALG_EMPTY = 9003,
        CONFIG_NOT_FOUND = 9004,
        FILE_DOES_NOT_EXIST = 9005,
        VERIFY_CODE_ERROR = 9006,
        VERIFY_CODE_IS_INVALID = 9007,
        VERiFY_CODE_USED = 9008;


    public static $errorMessages = [

        self::USER_CREATE_APPLICATION_FAIL => 'Failed to create user application !',
        self::USER_APPLICATION_SET_READ_FAIL => 'application set to read failed',
        self::USER_INFO_MODIFY_FAIL => 'Failed to modify user information !',
        self::USER_APPLICATION_NOT_FOUND => 'Application information does not exist !',
        self::USER_APPLICATION_PROCESSED => 'Application information has been processed !',
        self::USER_APPLICATION_TYPE_WRONG => 'Wrong application type !',


        self::JWT_PRIVATE_KEY_EMPTY => 'The private key is invalid !',
        self::JWT_PUBLIC_KEY_EMPTY => 'The public key is invalid !',
        self::JWT_ALG_EMPTY => 'The alg is invalid !',
        self::CONFIG_NOT_FOUND => 'Configuration not found !',
        self::FILE_DOES_NOT_EXIST => 'File does not exist !',
        self::VERIFY_CODE_ERROR => 'Verification code error !',
        self::VERIFY_CODE_IS_INVALID => 'Verification code is invalid !',
        self::VERiFY_CODE_USED => 'Verification code used !'
    ];

    /**
     * 返回状态码对应的字符串
     * @param $code
     * @param array $params
     * @param null $local
     * @return string
     */
    public static function result($code, array $params = [], $local = null): string
    {
        if (is_null($local)) {
            $local = context()->get('language');
        }
        return \Swoft::t($code, $params, $local);
    }
}
