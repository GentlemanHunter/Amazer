<?php


namespace App\Process;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoft\Timer;

/**
 * Class DispatchProcess
 * @package App\Process
 * @Bean()
 */
class DispatchProcess extends UserProcess
{
    public function run(Process $process): void
    {
        // TODO: 消费者

    }
}
