<?php

namespace App\Helper;


use GuzzleHttp\Psr7\Request;
use App\Exception\TaskStatus;
use GuzzleHttp\Psr7\Response;
use App\Model\Logic\RedisLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GuzzleRetry
 * @Bean()
 */
class GuzzleRetry
{
    /**
     * @Inject()
     * @var RedisLogic
     */
    private $redisLogic;

    protected static $taskid = '';
    protected static $startTime = '';
    protected static $overtime = 0;
    protected static $bodys = '';

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
            // Limit the number of retries to 5
            if ($retries >= self::$retry) {
                $this->log($response, "超过最大重试次数", TaskStatus::EXECUTEDFAIL);
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                $this->log($response, "连接异常", TaskStatus::EXECUTEDFAIL);
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    $this->log($response, "客户端错误", TaskStatus::EXECUTEDFAIL);
                    return true;
                }
            }
            $this->log($response, "执行成功", TaskStatus::EXECUTEDSUCCESS);
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
    public function setRetry(int $retry)
    {
        self::$retry = $retry;
        return $this;
    }
    public function setTaskId($task)
    {
        self::$taskid = $task;
        return $this;
    }
    public function setStartTime($startTime)
    {
        self::$startTime = $startTime;
        return $this;
    }
    public function setOvertime($overtime)
    {
        self::$overtime = $overtime;
        return $this;
    }
    public function setBodys($bodys)
    {
        self::$bodys = $bodys;
        return $this;
    }

    public function getRetry(): ?int
    {
        return self::$retry;
    }

    /**
     * 记录日志
     * @param $response
     * @param $message
     * @param $status
     * @throws \App\Exception\ApiException
     */
    private function log($response, $message, $status): void
    {
        $result = "{$message}" . json_encode($response->getBody());
        $osTime = time();
        $endTime = $osTime - self::$startTime;
        $this->redisLogic->addTaskWorkLog(
            self::$taskid,
            self::$retry,
            self::$overtime,
            self::$bodys,
            self::$startTime,
            $osTime,
            $endTime,
            $result,
            $status
        );
    }
}
