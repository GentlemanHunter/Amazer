<?php

use App\ExceptionCode\ApiCode;

return [
    ApiCode::SUCCESS => '成功',
    ApiCode::UNKNOWN => '未知错误',
    ApiCode::IS_STRING => '类型错误,必须是字符串',
    ApiCode::IS_PARAMS => '参数不能为空',
    ApiCode::IS_INT => '类型错误,必须是整数',
    // gateway error
    ApiCode::AUTH_ERROR => '此请求的授权已被拒绝！',
    ApiCode::NO_PERMISSION_PROCESS => '无权处理！',
    ApiCode::NO_DATA_AVAILABLE => '无可用数据 ！',
    // user error
    ApiCode::USER_NOT_INITIALIZED => '用户还未初始化!',
    ApiCode::USER_NOT_FOUND => '用户不存在!',
    ApiCode::USER_ID_INVALID => '无效用户!',
    ApiCode::USER_PASSWORD_ERROR => '用户密码错误!',
    ApiCode::USER_PASSWORD_LENGTH_DOES_NOT_MATCH => '用户密码长度不匹配!',
    ApiCode::USER_ACCOUNT_LENGTH_DOES_NOT_MATCH => '用户账号长度不匹配!',
    ApiCode::USER_NICKNAME_CANNOT_BE_EMPTY => '用户昵称不能为空!',
    ApiCode::USER_NICKNAME_EXCEEDS_MAXIMUM_LENGTH => '用户昵称最长 30个字符!',
    ApiCode::USER_PASSWORD_RULE_ERROR => '必须是大小写字母、数字、短横 -、下划线 _',
    ApiCode::PASSWORD_CANNOT_BE_EMPTY => '密码不能为空',
    ApiCode::USER_CREATE_APPLICATION_FAIL => '无法创建用户应用程序！',
    ApiCode::USER_APPLICATION_SET_READ_FAIL => '应用程序设置为读取失败',
    ApiCode::USER_INFO_MODIFY_FAIL => '修改用户信息失败！',
    ApiCode::USER_APPLICATION_NOT_FOUND => '申请信息不存在！',
    ApiCode::USER_APPLICATION_PROCESSED => '申请资料已处理完毕！',
    ApiCode::USER_APPLICATION_TYPE_WRONG => '错误的应用程序类型！',
    // jwt auth error
    ApiCode::JWT_PRIVATE_KEY_EMPTY => '私钥无效！',
    ApiCode::JWT_PUBLIC_KEY_EMPTY => '公钥无效！',
    ApiCode::JWT_ALG_EMPTY => '该算法无效！',
    ApiCode::CONFIG_NOT_FOUND => '未找到配置！',
    ApiCode::FILE_DOES_NOT_EXIST => '文件不存在 ！',
    // verify code error
    ApiCode::VERIFY_CODE_ERROR => '验证码错误！',
    ApiCode::VERIFY_CODE_IS_INVALID => '验证码无效！',
    ApiCode::VERiFY_CODE_USED => '使用验证码！'
];
