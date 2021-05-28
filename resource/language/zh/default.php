<?php

use App\ExceptionCode\ApiCode;

$config = [
    ApiCode::SUCCESS => '成功',
    ApiCode::AUTH_ERROR => '此请求的授权已被拒绝！',
    ApiCode::NO_PERMISSION_PROCESS => '无权处理！',
    ApiCode::NO_DATA_AVAILABLE => '无可用数据 ！',
    ApiCode::USER_NOT_FOUND => '用户不存在!',
    ApiCode::USER_ID_INVALID => '无效用户!',
    ApiCode::USER_PASSWORD_ERROR => '用户密码错误!',
    ApiCode::USER_PASSWORD_LENGTH_DOES_NOT_MATCH => '用户密码长度不匹配!',
    ApiCode::USER_ACCOUNT_LENGTH_DOES_NOT_MATCH => '用户账号长度不匹配!',
    ApiCode::USER_NICKNAME_CANNOT_BE_EMPTY => '用户昵称不能为空!',
    ApiCode::USER_NICKNAME_EXCEEDS_MAXIMUM_LENGTH => '用户昵称最长 30个字符!'
];

foreach ($config as $key => $value) {
    $result[getCodeMessage($key, ApiCode::$errorMessages)] = $value;
}

return $result;
