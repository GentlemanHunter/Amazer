<?php


namespace App\Task\Crontab;

use Swoft\Redis\Redis;
use App\Rpc\Lib\TaskInterface;
use App\Model\Logic\TaskWorkLogic;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class CronTask
 * @package App\Crontab
 * @Scheduled(name="cronTask")
 */
class CronTask
{
    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    public $taskWork;

    /**
     * @Reference(pool="task.pool",version="1.0")
     * @var TaskInterface
     */
    public $taskService;

    /**
     * 秒级定时器
     * @Cron("* * * * * *")
     */
    public function secondTaskConsumption()
    {
        $start = time();
        $end = time() + 5;
        $data = Redis::zRangeByScore('zset_data', (string)$start, (string)$end);
        if (!empty($data)) {
            $this->taskService->server($data);
        }
    }

    /**
     * 秒级定时器
     * @Cron("0 * * * * *")
     */
    public function minuteTaskProduction()
    {
        $data = $this->taskWork->getTaskWorkByExecution();
        if (!empty($data)) {
            foreach ($data as $item) {
                $this->taskService->inserTask($item['taskId'], $item['execution']);
            }
        }
    }
}
