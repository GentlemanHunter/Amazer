<?php


namespace App\Task\Crontab;

use Swoft\Crontab\Annotaion\Mapping\Cron;
use Swoft\Crontab\Annotaion\Mapping\Scheduled;

/**
 * Class TestTask
 * @package App\Task\Crontab
 * @Scheduled()
 */
class TestTask
{
    /**
     * @Cron("* * * * * *")
     */
    public function secondTask(): void
    {
        printf("second: %s",date('Y-m-d H:i:s',time()));
    }
}
