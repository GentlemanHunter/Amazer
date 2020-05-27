<?php


namespace App\Task\Crontab;

use App\Exception\TaskStatus;
use App\Model\Entity\TaskWork;
use App\Model\Logic\TaskWorkLogic;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use Swoft\Task\Task;

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
    private $taskWorkLogic;

    /**
     * 秒级定时器
     * @Cron("* * * * * *")
     */
    public function secondTask()
    {
        $start = time();
        $data = Redis::zRangeByScore(env('MASTER_REDIS', 'default_'), (string)$start, (string)$start);
        Task::async('work', 'taskConsumption', [$data, $start]);
    }

    /**
     * 秒级生产者
     * @Cron("* * * * * *")
     */
    public function taskProducer()
    {
        // Database
        $data = $this->getDate();

        CLog::info("pro_" . count($data));
        foreach ($data as $key => $value) {
            $isStatus = Redis::zAdd(env('MASTER_REDIS', 'default_'), [$value['taskId'] => $value['execution']]);
            if ($isStatus) {
                $this->taskWorkLogic->updateByTaskId($value['taskId'], TaskStatus::EXECUTED);
            }
        }
    }

    /**
     * 获取全部数据
     * @return array
     * @throws DbException
     */
    private function getDate()
    {
        $data = [];
        $time = time();
        $start = $time + env('HOT_LOAD_TIME', 60);
        $taskCount = TaskWork::whereBetween('execution', [$time, $start])->count();

        $count = env('TASK_COUNT', 1000);

        if ($taskCount > $count) {
            $userCount = ceil($taskCount / $count);
            for ($i = 0; $i <= $userCount; $i++) {
                $skip = $i * 10;
                $tmpArray = TaskWork::whereBetween('execution', [$time, $start])
                        ->where('status', TaskStatus::UNEXECUTED)
                        ->offset($skip)->limit($count)->get()->toArray() ?? [];
                $data = array_merge($data, $tmpArray);
            }
        } else {
            $data = array_merge($data, (array)TaskWork::whereBetween('execution', [$time, $start])
                    ->where('status', TaskStatus::UNEXECUTED)->get()->toArray() ?? []);
        }

        return $data;
    }
}
