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

use App\Model\Entity\Test;
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
        $process->name('swoft-monitor');

        while (true) {
            $connections = context()->getServer()->getSwooleServer()->connections;
            CLog::info('monitor = ' . json_encode($connections));

            // Database
            $user = $this->getDate();
            CLog::info('user=' . json_encode($user));

            foreach ($user as $key => $value) {
                Redis::zAdd(env('MASTER_REDIS', 'default_'), [json_encode([
                    'task_time' => $value['runTime'],
                    'task_name' => $value['name'],
                    'task_params' => $value
                ], JSON_UNESCAPED_UNICODE) => $value['runTime']]);
            }

            Coroutine::sleep(3);
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
        $start = $time - 300;
        $userCount = Test::whereBetween('run_time', [$start, $time])->count();

        if ($userCount > 10) {
            $userCount = ceil($userCount / 10);
            for ($i = 0; $i <= $userCount; $i++) {
                $skip = $i * 10;
                $tmpArray = Test::whereBetween('run_time', [$start, $time])->skip($skip)->limit(10)->get()->toArray() ?? [];
                $data = array_merge($data, $tmpArray);
            }
        } else {
            $data = array_merge($data, (array)Test::whereBetween('run_time', [$start, $time])->get()->toArray() ?? []);
        }

        return $data;
    }
}
