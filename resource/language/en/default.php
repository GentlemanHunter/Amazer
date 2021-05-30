<?php

use App\ExceptionCode\ApiCode;

return [
    ApiCode::SUCCESS => 'Success',
    ApiCode::UNKNOWN => 'unknown mistake',
    ApiCode::IS_STRING => 'Must be a string',
    ApiCode::IS_PARAMS => 'Parameter cannot be empty',
    ApiCode::IS_INT => 'Must be an integer',
    // gateway error
    ApiCode::AUTH_ERROR => 'Authorization has been denied for this request !',
    ApiCode::NO_PERMISSION_PROCESS => 'No permission to process !',
    ApiCode::NO_DATA_AVAILABLE => 'No data available !',
    // user error
    ApiCode::USER_NOT_INITIALIZED => 'User not initialized',
    ApiCode::USER_NOT_FOUND => 'User not found!',
    ApiCode::USER_ID_INVALID => 'The user id is invalid !',
    ApiCode::USER_PASSWORD_ERROR => 'User password input error !',
    ApiCode::USER_ACCOUNT_LENGTH_DOES_NOT_MATCH => 'User account length does not match !',
    ApiCode::USER_PASSWORD_LENGTH_DOES_NOT_MATCH => 'User password length does not match !',
    ApiCode::USER_NICKNAME_CANNOT_BE_EMPTY => 'User nickname cannot be empty',
    ApiCode::USER_NICKNAME_EXCEEDS_MAXIMUM_LENGTH => 'The maximum length of user nickname is 30',
    ApiCode::USER_PASSWORD_RULE_ERROR => 'Must be uppercase and lowercase letters, numbers, dash -, underscore _',
    ApiCode::PASSWORD_CANNOT_BE_EMPTY => 'Password cannot be empty',
    ApiCode::USER_CREATE_APPLICATION_FAIL => 'Failed to create user application !',
    ApiCode::USER_APPLICATION_SET_READ_FAIL => 'application set to read failed',
    ApiCode::USER_INFO_MODIFY_FAIL => 'Failed to modify user information !',
    ApiCode::USER_APPLICATION_NOT_FOUND => 'Application information does not exist !',
    ApiCode::USER_APPLICATION_PROCESSED => 'Application information has been processed !',
    ApiCode::USER_APPLICATION_TYPE_WRONG => 'Wrong application type !',
    // jwt auth error
    ApiCode::JWT_PRIVATE_KEY_EMPTY => 'The private key is invalid !',
    ApiCode::JWT_PUBLIC_KEY_EMPTY => 'The public key is invalid !',
    ApiCode::JWT_ALG_EMPTY => 'The alg is invalid !',
    ApiCode::CONFIG_NOT_FOUND => 'Configuration not found !',
    ApiCode::FILE_DOES_NOT_EXIST => 'File does not exist !',
    // verify code error
    ApiCode::VERIFY_CODE_ERROR => 'Verification code error !',
    ApiCode::VERIFY_CODE_IS_INVALID => 'Verification code is invalid !',
    ApiCode::VERiFY_CODE_USED => 'Verification code used !'
];
