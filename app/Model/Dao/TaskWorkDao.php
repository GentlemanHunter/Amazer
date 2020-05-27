<?php


namespace App\Model\Dao;

use App\Exception\TaskStatus;
use App\Model\Entity\TaskWork;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class TaskWorkDao
 * @Bean()
 * @package App\Model\Dao
 */
class TaskWorkDao
{
    /**
     * @Inject()
     * @var TaskWork
     */
    private $taskWorkEntity;

    /**
     * 根据 task 任务id 返回 task 信息
     * @param string $taskId
     * @param int $status
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
     */
    public function findByTaskId(string $taskId,int $status = TaskStatus::UNEXECUTED){
        $where = [
            'taskId' => $taskId,
            'status' => $status
        ];
        return $this->taskWorkEntity::where($where)->first();
    }

    /**
     * 新增数据
     * @param array $data
     * @return string
     */
    public function createTask(array $data)
    {
        return $this->taskWorkEntity::insertGetId($data);
    }
}
