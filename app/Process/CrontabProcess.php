<?php


namespace App\Process;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;

/**
 * Class CrontabProcess
 * @package App\Process
 * @Bean()
 */
class CrontabProcess extends UserProcess
{
    public function run(Process $process): void
    {
        // TODO: Implement run() method.
    }
}
