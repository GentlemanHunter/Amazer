<?php


namespace App\Model\Dao;

use App\Model\Entity\TaskWorkLog;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Eloquent\Collection;
use Swoft\Db\Exception\DbException;

/**
 * Class TaskWorkLogDao
 *
 * @Bean()
 */
class TaskWorkLogDao
{
    /**
     * @Inject()
     * @var TaskWorkLog
     */
    private $taskWorkLogEntity;

    /**
     * Notes: 创建一条日志数据
     * @param array $data
     * @return string
     */
    public function createLogData(array $data)
    {
        return $this->taskWorkLogEntity::insertGetId($data);
    }

    /**
     * Notes: 根据条件返回分页数据
     * @param array $where
     * @param int $page
     * @param int $pageSize
     * @param array|string[] $field
     * @return Collection
     * @throws DbException
     * @author: MagicConch17
     */
    public function getPaging(array $where, int $page, int $pageSize, array $field = ['*'])
    {
        return $this->taskWorkLogEntity::where($where)
            ->forPage($page, $pageSize)
            ->orderByDesc('created_at')
            ->orderBy('id', 'desc')
            ->get($field);
    }

    /**
     * Notes: 根据条件返回总数
     * @param array $where
     * @param string $field
     * @return int
     * @author: MagicConch17
     */
    public function getCount(array $where, $field = '*')
    {
        return $this->taskWorkLogEntity::where($where)->count($field);
    }
}
