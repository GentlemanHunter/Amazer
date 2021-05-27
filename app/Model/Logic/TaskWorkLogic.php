<?php


namespace App\Model\Logic;

use App\ExceptionCode\TaskStatus;
use App\Model\Dao\TaskWorkDao;
use App\Model\Dao\TaskWorkLogDao;
use App\Model\Entity\TaskWork;
use App\Model\Entity\TaskWorkLog;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Eloquent\Builder;
use Swoft\Db\Eloquent\Model;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;

/**
 * Class TaskWorkLogic
 * @Bean()
 * @package App\Model\Logic
 */
class TaskWorkLogic
{
    /**
     * 任务主表
     * @Inject()
     * @var TaskWorkDao
     */
    public $taskWorkDao;

    /**
     * 任务日志表
     * @Inject()
     * @var TaskWorkLogDao
     */
    public $taskWorkLogDao;

    /**
     * 创建一个 task
     * @param $names
     * @param $describe
     * @param $execution
     * @param $retry
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
    ): string
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
     * @throws DbException
     */
    public function updateByTaskId($taskId, $status = TaskStatus::UNEXECUTED): int
    {
        return $this->taskWorkDao->updateBytaskId($taskId, ['status' => $status]);
    }

    /**
     * 获取一个task
     * @param $taskId
     * @param int $status
     * @return object|Builder|Model|null
     * @throws DbException
     */
    public function findByTaskId($taskId, $status = TaskStatus::EXECUTEDSUCCESS)
    {
        return $this->taskWorkDao->findByTaskId($taskId, $status);
    }

    /**
     * 写入日志
     * @param array $data
     * @return string
     */
    public function createTaskLogData(array $data): string
    {
        return $this->taskWorkLogDao->createLogData($data);
    }

    /**
     * 创建一个 构建 日志
     * @param $taskId
     * @param $length
     * @param $overtime
     * @param $bodys
     * @param $execution
     * @param int $complete
     * @param int $implement
     * @param string $result
     * @param int $status
     * @return string
     */
    public function createTaskLogAux(
        $taskId,
        $length,
        $overtime,
        $bodys,
        $execution,
        $complete = 0,
        $implement = 0,
        $result = '',
        $status = TaskStatus::UNEXECUTED
    ): string
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
        return $this->taskWorkLogDao->createLogData($data);
    }

    /**
     * 根据 用户 获取 task 分页 列表
     * @param $uid
     * @param $page
     * @param $pageSize
     * @param null|string $taskId
     * @return array
     * @throws DbException
     */
    public function getTaskWorkPagingByUid($uid, $page, $pageSize, $taskId = null): array
    {
        $where = ['uid' => (string)$uid];

        if (!is_null($taskId) && strlen($taskId) > 0) {
            $where[] = ['task_id', $taskId];
        }

        $count = $this->taskWorkDao->getCount($where) ?? 0;
        $data = $this->taskWorkDao->getPaging($where, $page, $pageSize) ?? [];

        if ($data) {
            $data = $data->toArray();
        }
        $username = getUserInfo($uid)->getUsername() ?? '此用户异常';
        foreach ($data as &$item) {
            $item['username'] = $username;
            $item['status'] = TaskStatus::message($item['status']);
        }

        return ['data' => $data, 'total' => $count];
    }

    /**
     * 返回 task 主体
     * @param $taskId
     * @return object|Builder|Model|null
     * @throws DbException
     */
    public function findByTaskIdInfo($taskId)
    {
        return $this->taskWorkDao->findByTaskId($taskId);
    }

    /**
     * 返回 [10 - 60]s 内的任务列表
     * @return array
     * @throws DbException
     */
    public function getTaskWorkByExecution(): array
    {
        $currentTime = time() + 10;
        $futureTime = $currentTime + 60;// 6 分钟预热 大于 预热执行任务 时间
        $data = $this->taskWorkDao->getPaging([
            ['execution', '>=', $currentTime],
            ['execution', '<=', $futureTime],
            ['status', '=', TaskStatus::UNEXECUTED]
        ], 1, 999, ['task_id', 'execution']) ?: [];

        if ($data) {
            $data = $data->toArray();
        }

        return $data;
    }

    /**
     * Notes: 根据任务Id 返回日志
     * @param $taskId
     * @param $page
     * @param $pageSize
     * @param null $status
     * @return array
     * @throws DbException
     * @author: MagicConch17
     */
    public function getTaskWorkLogPagingByTaskId($taskId, $page, $pageSize, $status = null): array
    {
        $where = [
            'task_id' => $taskId
        ];

        if (!is_null($status)) {
            $where['status'] = $status;
        }

        $count = $this->taskWorkLogDao->getCount($where, 'id') ?? 0;
        $list = $this->taskWorkLogDao->getPaging($where, $page, $pageSize) ?? [];

        foreach ($list as &$item) {
            $item['statusMessage'] = TaskStatus::message($item['status']);
        }

        return ['total' => $count, 'data' => $list];
    }
}
