<?php


namespace App\Crontab;

use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;
use Swoft\Redis\Redis;

/**
 * Class CronTask
 * @package App\Crontab
 * @Scheduled(name="cronTask")
 */
class CronTask
{
    /**
     * @Cron("* * * * * *")
     */
    public function secondTask()
    {
        $second = time();
        $data = Redis::zRangeByScore(env('MASTER_REDIS', 'default_'),(string)$second,(string)$second);
    }
}
