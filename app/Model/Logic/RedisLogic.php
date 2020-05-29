<?php


namespace App\Model\Logic;

use App\Exception\ApiException;
use App\Exception\TaskStatus;
use App\Model\Dao\RedisHashDao;
use App\Model\Dao\RedisListDao;
use App\Model\Dao\RedisSsetDao;
use App\Model\Dao\TaskWorkDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Task\Task;

/**
 * Class RedisLogic
 * @Bean()
 * @package App\Model\Logic
 */
class RedisLogic
{
    /**
     * @Inject()
     * @var RedisHashDao
     */
    private $redisHashDao;

    /**
     * @Inject()
     * @var TaskWorkDao
     */
    private $taskWorkDao;

    /**
     * @Inject()
     * @var RedisListDao
     */
    private $redisListDao;

    /**
     * @Inject()
     * @var RedisSsetDao
     */
    private $redisSsetDao;

    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    private $taskWorkLogic;

    /**
     * 创建一个 redis hash
     * @param $names
     * @param $describe
     * @param $execution
     * @param $retry
     * @param $bodys
     * @param $uid
     * @param int $status
     * @return bool|int
     */
    public function createTaskWork(
        $names,
        $describe,
        $execution,
        $retry,
        $bodys,
        $uid,
        $status = TaskStatus::UNEXECUTED
    )
    {
        $taskId = getGuid();
        $data = [
            'names' => $names,
            'describe' => $describe,
            'execution' => $execution,
            'retry' => $retry,
            'bodys' => $bodys,
            'uid' => $uid,
            'status' => $status,
            'created_at' => time(),
            'updated_at' => time()
        ];

        Task::async('work', 'insertQueue', [$taskId, $execution]);

        if ($result = $this->redisHashDao->addHashDataAux($taskId, $data)) {
            $data['task_id'] = $taskId;
            $this->taskWorkDao->createTask($data);
            return $result;
        }

        throw new ApiException('redis 存储异常', -1);
    }

    /**
     * 写入 redis log
     * @param $taskId
     * @param $length
     * @param $overtime
     * @param $bodys
     * @param $excution
     * @param $complete
     * @param $implement
     * @param $result
     * @param $status
     * @return bool|int|string
     * @throws ApiException
     */
    public function addTaskWorkLog(
        $taskId,
        $length,
        $overtime,
        $bodys,
        $execution,
        $complete,
        $implement,
        $result,
        $status
    )
    {
        $data = [
            'task_id' => $taskId,
            'length' => $length,
            'overtime' => $overtime,
            'bodys' => $bodys,
            'execution' => $execution,
            'complete' => $complete,
            'implement' => $implement,
            'result' => $result,
            'status' => $status
        ];

        if ($redisId = $this->redisListDao->addListDataAux($data)) {
            return $redisId;
        }

        throw new ApiException("redis 存储异常", -1);
    }

    /**
     * 取消 一个 任务
     * @param $taskId
     * @return string
     * @throws ApiException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function delTaskData($taskId)
    {
        $this->redisHashDao->delByKeyAux($taskId);
        $this->redisSsetDao->delByValueAux($taskId);
        $this->taskWorkDao->updateBytaskId($taskId, ['status' => TaskStatus::EXECUTEDCANCEL]);
        return $this->addTaskLogAux(
            $taskId,
            1,
            -1,
            -1,
            '用户取消任务',
            TaskStatus::EXECUTEDCANCEL
        );
    }

    /**
     * 新增 同步 日志
     * @param $taskId
     * @param $length
     * @param $complete
     * @param $implement
     * @param $result
     * @param $status
     * @return string
     * @throws ApiException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function addTaskLogAux($taskId, $length, $complete, $implement, $result, $status)
    {
        $data = $this->taskWorkLogic->findByTaskId($taskId);
        if (!$data || is_null($data)) {
            throw new ApiException("任务不存在???", -1);
        }

        $bodys = $data->getBodys();
        $timesout = $bodys['timeout'] ?? -1;

        return $this->taskWorkLogic->createTaskLogAux(
            $data->getTaskId(),
            $length,
            $timesout,
            $data->getBodys(),
            $data->getExecution(),
            $complete,
            $implement,
            $result,
            $status
        );
    }
}
