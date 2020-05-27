<?php

/**
 * @author yxk yangxiukang@ketangpai.com
 */

return [
    'private_key' => 'Wharfs20200505',
    'public_key' => 'Wharfs20200505',
    'iss' => env('APP_HOST','http://localhost:18306'),
    'aud' => env('APP_HOST','http://localhost:18306'),
    'alg' => 'HS256' // 为RS256 需要修改私钥和公钥
];
