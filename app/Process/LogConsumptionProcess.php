<?php


namespace App\Process;

use App\Model\Dao\RedisListDao;
use App\Model\Logic\TaskWorkLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoole\Coroutine;

/**
 * Class LogConsumptionProcess
 * @Bean()
 * @package App\Process
 */
class LogConsumptionProcess extends UserProcess
{
    /**
     * @Inject()
     * @var RedisListDao
     */
    private $redisListDao;

    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    private $taskWorkLogic;

    public function run(Process $process): void
    {
        while (true) {
            $data = $this->redisListDao->getPopListDataAux();
            if ($data) {
                $log = json_decode($data, true);
                $this->taskWorkLogic->createTaskLogData($log);
                CLog::info(json_encode($log));
                $this->taskWorkLogic->updateByTaskId($log['task_id'],$log['status']);
            }
            Coroutine::sleep(1);
        }
    }
}
