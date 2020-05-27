<?php


namespace App\Task\Task;

use App\Helper\GuzzleRetry;
use App\Helper\MemoryTable;
use App\Model\Logic\TaskWorkLogic;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
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
    private $taskWorkLogic;

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
     */
    public function consumptionTimer(int $runTime, string $taskId, int $retry, array $data): void
    {
        $url = $data['url'] ?? '';
        $method = $data['method'] ?? 'GET';
        unset($data['url']);
        unset($data['method']);

        /** TODO: 待完善 */
        $timerId = Timer::after($runTime, function ($url, $method, $data, $retry) {
            /** @var GuzzleRetry $handRetry */
            $handRetry = bean('App\Helper\GuzzleRetry');
            $handRetry->setRetry($retry);

            // TODO 可以迭代
            /*if ($method === 'POST'){

            }*/

            $handlerState = HandlerStack::create(new CurlHandler());
            $handlerState->push(Middleware::retry($handRetry->retryDecider(), $handRetry->retryDelay()));
            $client = new Client(['handler' => $handlerState]);
            $reponse = $client->request($method, $url, $data);

            CLog::info('reponse:' . json_encode($reponse));
            vdump($reponse);

        }, $url, $method, $data, $retry);

        vdump($timerId);

        /** @var MemoryTable $memoryTable */
//        $memoryTable = bean('App\Helper\MemoryTable');
//        $memoryTable->store(MemoryTable::TASK_TO_ID, (string)$taskId, ['timerId' => $timerId]);
    }

    /**
     * @TaskMapping(name="taskConsumption")
     */
    public function taskConsumption($data, $runTime): void
    {
        foreach ($data as $item) {
            $task = $this->taskWorkLogic->findByTaskId($item);
            \Swoft\Task\Task::async('work', 'consumption',
                [$runTime, $task->getTaskId(), $task->getRetry(), $task->getBodys()]
            );
            Redis::zRem(env('MASTER_REDIS', 'default_'),$task->getTaskId());
        }
    }
}
