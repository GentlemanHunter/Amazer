<?php


namespace App\Model\Logic;

use App\ExceptionCode\TaskStatus;
use App\Model\Dao\TaskWorkDao;
use App\Model\Dao\TaskWorkLogDao;
use App\Model\Entity\TaskWork;
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
     * @throws DbException
     */
    public function updateByTaskId($taskId, $status = TaskStatus::UNEXECUTED)
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
    public function createTaskLogData(array $data)
    {
        CLog::info(json_encode($data));
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
        return $this->taskWorkLogDao->createLogData($data);
    }

    /**
     * 根据 用户 获取 task 分页 列表
     * @param $uid
     * @param $page
     * @param $pageSize
     * @return array
     * @throws DbException
     */
    public function getTaskWorkPagingByUid($uid, $page, $pageSize)
    {
        $count = $this->taskWorkDao->getCount(['uid' => (string)$uid]) ?? 0;
        $data = $this->taskWorkDao->getPaging(['uid' => (string)$uid], $page, $pageSize) ?? [];

        if ($data) {
            $data = $data->toArray();
        }
        $username = getUserInfo($uid)->getUsername() ?? '此用户异常';
        foreach ($data as &$item) {
            $item['username'] = $username;
            $item['status'] = TaskStatus::$errorMessages[$item['status']];
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
     * 返回 一分钟内的 任务列表
     * @return array
     * @throws DbException
     */
    public function getTaskWorkByExecution()
    {
        $currentTime = time();
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
}
