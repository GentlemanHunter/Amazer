<?php


namespace App\Task\Task;

use App\Common\Wechat;
use App\Exception\ApiException;
use App\ExceptionCode\TaskStatus;
use App\Helper\GuzzleRetry;
use App\Helper\MemoryTable;
use App\Model\Logic\RedisLogic;
use App\Model\Logic\TaskWorkLogic;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
use Swoft\Task\Exception\TaskException;
use Swoft\Timer;

/**
 * Class WorkTask
 *
 * @Task(name="work")
 */
class WorkTask
{
    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    public $taskWorkLogic;

    /**
     * @Inject()
     * @var RedisLogic
     */
    public $redisLogic;

    /**
     * @TaskMapping(name="consumption")
     * $data = [
     *      url => 'https://localhost/test/redis,
     *      connect_timeout => '表示等待服务器响应超时的最大值, // float 0
     *      verify => '请求时验证SSL证书行为', // boole
     *      cookies => 'cookie 数据’, // string
     *      body => 'body 选项用来控制一个请求(比如：PUT, POST, PATCH)的主体部分。',
     *      headers => '要添加到请求的报文头的关联数组，每个键名是header的名称，每个键值是一个字符串或包含代表头字段字符串的数组。', // array
     *      form_params => '用来发送一个 application/x-www-form-urlencoded POST请求.',// array
     *      timeout => '请求超时的秒数。使用 0 无限期的等待(默认行为)',// float
     *      version => '请求要使用到的协议版本',// string, float
     * ]
     * @param int $runTime
     * @param $taskId
     * @param $retry
     * @param array $data
     */
    public function consumptionTimer(int $runTime, $taskId, $retry, array $data): void
    {
        $url = $data['url'] ?? '';
        $method = $data['method'] ?? 'GET';
        unset($data['url']);
        unset($data['method']);

        /** TODO: 需要加入 通知元素 */
        $timerId = Timer::after($runTime * 1000, function ($url, $method, $data, $retry, $taskId) {
            try {
                /** @var GuzzleRetry $handRetry */
                $handRetry = bean('App\Helper\GuzzleRetry');
                $handRetry->setRetry($retry)->setBodys($data)->setTaskId($taskId)->setStartTime(time());

                if (isset($data['timeout'])) {
                    $handRetry->setOvertime($data['timeout']);
                }

                $handlerState = HandlerStack::create(new CurlHandler());
                $handlerState->push(Middleware::retry($handRetry->retryDecider(), $handRetry->retryDelay()));
                $client = new Client(['handler' => $handlerState, 'delay' => 1]);
                $reponse = $client->request($method, $url, $data);

                CLog::info("response:" . serialize($reponse->getBody()->getContents()));
            } catch (\Throwable $exception) {
                Redis::hSet('timer:error', $taskId, json_encode([
                    'url' => $url,
                    'method' => $method,
                    'data' => $data,
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'line' => $exception->getTraceAsString()
                ]));
                $wechat = bean('App\Common\Wechat');
                $wechat->sendMarkdownMessage(
                    sprintf(
                        Wechat::$message[Wechat::ERRORLOG],
                        $taskId,
                        date('Y/m/d H:i:s', time()),
                        $url,
                        json_encode($data)
                    )
                );
            } finally {
                Redis::hDel('hash_data', (string)$taskId);
                /** @var MemoryTable $memoryTable */
                $memoryTable = bean('App\Helper\MemoryTable');
                $memoryTable->forget(MemoryTable::TASK_TO_ID, (string)$taskId);
            }
        }, $url, $method, $data, $retry, $taskId);

        /** @var MemoryTable $memoryTable */
        $memoryTable = bean('App\Helper\MemoryTable');
        $memoryTable->store(MemoryTable::TASK_TO_ID, (string)$taskId, ['timerId' => $timerId]);
    }

    /**
     * 插入 task
     * @TaskMapping(name="insertQueue")
     * @param $taskId
     * @param $runTime
     */
    public function insertQueueData($taskId, $runTime): void
    {
        Redis::zAdd('zset_data', [$taskId => $runTime]);
    }

    /**
     * 取消任务
     * @TaskMapping(name="delQueue")
     * @param $taskId
     * @throws ApiException
     * @throws DbException
     */
    public function delQueueData($taskId): void
    {
        /** @var MemoryTable $memoryTable */
        $memoryTable = bean('App\Helper\MemoryTable');
        $timerId = $memoryTable->get(MemoryTable::TASK_TO_ID, (string)$taskId, 'timerId');
        if ($timerId) {
            // 取消定时器
            $memoryTable->forget(MemoryTable::TASK_TO_ID, (string)$taskId);
            Timer::clear((int)$timerId);
        }
        $this->redisLogic->delTaskData($taskId);
    }

    /**
     * 编辑任务
     * @TaskMapping(name="editQueue")
     * @param $taskId
     * @param $names
     * @param $describe
     * @param $execution
     * @param $retry
     * @param $bodys
     * @param $uid
     * @throws ApiException
     * @throws DbException
     * @throws TaskException
     */
    public function editQueueData(
        $taskId
        , $names
        , $describe
        , $execution
        , $retry
        , $bodys
        , $uid
    ): void
    {
        /** @var MemoryTable $memoryTable */
        $memoryTable = bean('App\Helper\MemoryTable');
        $timerId = $memoryTable->get(MemoryTable::TASK_TO_ID, (string)$taskId, 'timerId');

        if ($timerId) {
            // 取消定时器
            $memoryTable->forget(MemoryTable::TASK_TO_ID, (string)$taskId);
            Timer::clear((int)$timerId);
            if ((time() - $execution) < env('TIMEOUT', 60))
                $this->insertQueueData($taskId, $execution);// 时间小于 当前 时间 20秒 进行插队
        }

        try {
            /** @var RedisLogic $redisLogic */
            $redisLogic = bean('App\Model\Logic\RedisLogic');
            $redisLogic->clearRedisData($taskId);
        } catch (ApiException $apiException) {
            //
        }

        $data = [
            'names' => $names,
            'describe' => $describe,
            'execution' => $execution,
            'retry' => $retry,
            'bodys' => $bodys,
            'uid' => $uid,
            'status' => TaskStatus::UNEXECUTED
        ];

        $this->redisLogic->editTask($taskId, $execution, $data, false);
    }
}
