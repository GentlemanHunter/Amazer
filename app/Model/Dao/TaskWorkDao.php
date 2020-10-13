<?php


namespace App\Model\Dao;

use App\ExceptionCode\TaskStatus;
use App\Model\Entity\TaskWork;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\CLog;

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
    public function findByTaskId(string $taskId, int $status = 0)
    {
        $where = [
            'task_id' => $taskId
        ];

        if ($status !== 0) {
            $where['status'] = $status;
        }

        return $this->taskWorkEntity::where($where)->first();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function createTask(array $data)
    {
        return $this->taskWorkEntity::insert($data);
    }

    /**
     * @param string $taskId
     * @param array $data
     * @return bool|int
     * @throws \Swoft\Db\Exception\DbException
     */
    public function updateBytaskId(string $taskId, array $data)
    {
        return $this->taskWorkEntity::where('task_id', '=', $taskId)->update($data);
    }

    /**
     * task 分页 获取
     * @param array $where
     * @param int $page
     * @param int $pageSize
     * @param array|string[] $field
     * @return \Swoft\Db\Eloquent\Collection
     * @throws \Swoft\Db\Exception\DbException
     */
    public function getPaging(array $where, int $page, int $pageSize, array $field = ['*'])
    {
        return $this->taskWorkEntity::where($where)
            ->forPage($page, $pageSize)
            ->orderByDesc('created_at')
            ->orderBy('execution', 'desc')
            ->get($field);
    }

    /**
     * 根据条件返回 总条数
     * @param array $where
     * @param string $field
     * @return int
     */
    public function getCount(array $where, $field = '*')
    {
        return $this->taskWorkEntity::where($where)->count($field);
    }
}
