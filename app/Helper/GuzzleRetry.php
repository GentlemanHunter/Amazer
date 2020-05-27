<?php

namespace App\Helper;


use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;

/**
 * Class GuzzleRetry
 * @Bean()
 */
class GuzzleRetry
{
    protected static $retry = 1;

    /**
     * 重试定时器任务
     * @return \Closure
     */
    public function retryDecider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            CLog::info("Curl:" . json_encode($request));
            vdump($request,$response);
            // Limit the number of retries to 5
            if ($retries >= self::$retry) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * delay 1s 2s 3s 4s 5s
     * @return \Closure
     */
    public function retryDelay()
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }

    /**
     * 管理 重试次数
     * @author yxk yangxiukang@ketangpai.com
     */
    public function setRetry(int $retry): void
    {
        self::$retry = $retry;
    }

    public function getRetry(): ?int
    {
        return self::$retry;
    }
}
