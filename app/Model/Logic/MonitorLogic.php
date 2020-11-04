<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Model\Logic;

use App\Model\Entity\TaskWork;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Swoft\Redis\Redis;
use Swoole\Coroutine;

/**
 * Class MonitorProcessLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class MonitorLogic
{
    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function monitor(Process $process): void
    {
        $process->name('ktp-task-monitor');

        while (true) {
//            $connections = context()->getServer()->getSwooleServer()->connections;
//            CLog::info('monitor = ' . json_encode($connections));

            // Database
            $data = $this->getDate();
            CLog::info('data_count=' . count($data));

            foreach ($data as $key => $value) {
                Redis::zAdd(env('MASTER_REDIS', 'default_'), [$value['taskId'] => $value['execution']]);
            }

            Coroutine::sleep(1);
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

        if ($taskCount > env('TASK_COUNT', 1000)) {
            $userCount = ceil($taskCount / env('TASK_COUNT', 1000));
            for ($i = 0; $i <= $userCount; $i++) {
                $skip = $i * 10;
                $tmpArray = TaskWork::whereBetween('execution', [$time, $start])->skip($skip)->limit(env('TASK_COUNT', 1000))->get()->toArray() ?? [];
                $data = array_merge($data, $tmpArray);
            }
        } else {
            $data = array_merge($data, (array)TaskWork::whereBetween('execution', [$time, $start])->get()->toArray() ?? []);
        }

        return $data;
    }
}
