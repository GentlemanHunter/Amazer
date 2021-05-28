<?php

use App\ExceptionCode\ApiCode;

return [
    ApiCode::SUCCESS => '成功',
    ApiCode::AUTH_ERROR => '此请求的授权已被拒绝！',
    ApiCode::NO_PERMISSION_PROCESS => '无权处理！',
    ApiCode::NO_DATA_AVAILABLE => '无可用数据 ！',
    ApiCode::USER_NOT_FOUND => '用户不存在!',
    ApiCode::USER_ID_INVALID => '无效用户!',
    ApiCode::USER_PASSWORD_ERROR => '用户密码错误!',
];
