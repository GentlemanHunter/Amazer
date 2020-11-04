<?php


namespace App\Task\Crontab;

use App\ExceptionCode\TaskStatus;
use App\Helper\GuzzleRetry;
use App\Model\Entity\TaskWork;
use App\Model\Logic\TaskWorkLogic;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Redis\Redis;
use Swoft\Task\Task;
use Swoole\Coroutine;

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
    private $taskWork;

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
            foreach ($data as $item) {
                $score = Redis::zScore('zset_data', $item);
                $msec = $score - time();
                $value = redisHashArray(Redis::hGet('hash_data', $item));
                CLog::info("msec:" . $msec);
                \Swoft\Task\Task::async('work', 'consumption',
                    [$msec, $item, $value['retry'], $value['bodys']]
                );
//                CLog::info("scoure:" . $score . "  value:" . json_encode($value));
                Redis::zRem('zset_data', $item);
            }
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
            foreach ($data as $item){
                Task::async('work','insertQueue',[$item['taskId'],$item['execution']]);
            }
        }
    }
}
