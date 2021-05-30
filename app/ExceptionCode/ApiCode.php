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

    // 系统级别错误
    const IS_STRING = 2000,
        IS_PARAMS = 2001,
        IS_INT = 2002;

    //用户错误码 3000～3999
    const
        USER_NOT_INITIALIZED = 3000,
        USER_NOT_FOUND = 3001,
        USER_ID_INVALID = 3002,
        USER_PASSWORD_ERROR = 3003,
        USER_CREATE_APPLICATION_FAIL = 3004,
        USER_APPLICATION_SET_READ_FAIL = 3005,
        USER_INFO_MODIFY_FAIL = 3006,
        USER_APPLICATION_NOT_FOUND = 3007,
        USER_APPLICATION_PROCESSED = 3008,
        USER_APPLICATION_TYPE_WRONG = 3009,
        USER_ACCOUNT_ALREADY_USER = 3010,
        USER_PASSWORD_LENGTH_DOES_NOT_MATCH = 3011,
        USER_ACCOUNT_LENGTH_DOES_NOT_MATCH = 3012,
        USER_NICKNAME_CANNOT_BE_EMPTY = 3013,
        USER_NICKNAME_EXCEEDS_MAXIMUM_LENGTH = 3014,
        USER_PASSWORD_RULE_ERROR = 3015,
        PASSWORD_CANNOT_BE_EMPTY = 3016;


    // ext 9000~9999
    const JWT_PRIVATE_KEY_EMPTY = 9001,
        JWT_PUBLIC_KEY_EMPTY = 9002,
        JWT_ALG_EMPTY = 9003,
        CONFIG_NOT_FOUND = 9004,
        FILE_DOES_NOT_EXIST = 9005,
        VERIFY_CODE_ERROR = 9006,
        VERIFY_CODE_IS_INVALID = 9007,
        VERiFY_CODE_USED = 9008;

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
            $local = context()->get('language', 'en');
        }
        return \Swoft::t($code, $params, $local);
    }
}
