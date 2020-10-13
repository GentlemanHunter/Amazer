<?php


namespace App\Model\Dao;

use App\Model\Entity\TaskWorkLog;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

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
     * @param array $data
     * @return string
     */
    public function createLogData(array $data)
    {
        return $this->taskWorkLogEntity::insertGetId($data);
    }
}
