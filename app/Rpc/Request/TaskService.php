<?php


namespace App\Rpc\Request;

use App\Task\Task\WorkTask;
use Swoft\Redis\Redis;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use Swoft\Task\Task;

/**
 * Class: TaskService
 * @Service(version="1.0")
 */
class TaskService implements \App\Rpc\Lib\TaskInterface
{
    public function server(array $taskList): void
    {
        foreach ($taskList as $item) {
            $score = Redis::zScore('zset_data', $item);
            $msec = $score - time();
            $value = redisHashArray(Redis::hGet('hash_data', $item));
            \Swoft\Task\Task::async('work', 'consumption',
                [$msec, $item, $value['retry'], $value['bodys']]
            );
            Redis::zRem('zset_data', $item);
        }
    }

    public function delTask(string $taskId): bool
    {
        return true;
    }

    /**
     * Notes:
     * @param string $taskId
     * @param int $execution
     * @return bool
     * @throws \Swoft\Task\Exception\TaskException
     * @author: MagicConch17
     */
    public function inserTask(string $taskId, int $execution): bool
    {
        /** @method WorkTask insertQueueData() */
        Task::async('work', 'insertQueue', [$taskId, $execution]);
        return true;
    }
}
