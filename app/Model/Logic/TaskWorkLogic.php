<?php


namespace App\Model\Logic;

use App\Exception\TaskStatus;
use App\Model\Dao\TaskWorkDao;
use App\Model\Dao\TaskWorkLogDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class TaskWorkLogic
 * @Bean()
 * @package App\Model\Logic
 */
class TaskWorkLogic
{
    /**
     * @Inject()
     * @var TaskWorkDao
     */
    private $taskWorkDao;

    /**
     * @Inject()
     * @var TaskWorkLogDao
     */
    private $taskWorkLogDao;

    /**
     * 创建一个 task
     * @param $names
     * @param $describe
     * @param $execution
     * @param $retry
     * @param $overtime
     * @param $bodys
     * @param $uid
     * @param int $status
     * @return string
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
            'taskId' => $taskId,
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

        $this->taskWorkDao->createTask($data);

        return $taskId;
    }

    /**
     * 修改物理状态
     * @param $taskId
     * @param int $status
     * @return int
     * @throws \Swoft\Db\Exception\DbException
     */
    public function updateByTaskId($taskId, $status = TaskStatus::UNEXECUTED)
    {
        return $this->taskWorkDao->updateBytaskId($taskId, ['status' => $status]);
    }

    /**
     * 获取一个task
     * @param $taskId
     * @param int $status
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
     */
    public function findByTaskId($taskId,$status = TaskStatus::EXECUTED)
    {
        return $this->taskWorkDao->findByTaskId($taskId,$status);
    }

    /**
     * 写入日志
     * @param array $data
     * @return string
     */
    public function createTaskLogData(array $data)
    {
        return $this->taskWorkLogDao->createLogData($data);
    }
}
