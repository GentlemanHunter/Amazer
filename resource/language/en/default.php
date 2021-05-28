<?php

use App\ExceptionCode\ApiCode;

return [
    ApiCode::SUCCESS => 'Success',
    ApiCode::AUTH_ERROR => 'Authorization has been denied for this request !',
    ApiCode::NO_PERMISSION_PROCESS => 'No permission to process !',
    ApiCode::NO_DATA_AVAILABLE => 'No data available !',
    ApiCode::USER_NOT_FOUND => 'User not found!',
    ApiCode::USER_ID_INVALID => 'The user id is invalid !',
    ApiCode::USER_PASSWORD_ERROR => 'User password input error !',
];
