<?php


namespace App\Exception;


class TaskStatus
{
    /** 执行任务状态 1000-1999 */
    const UNEXECUTED = 1000,
          EXECUTEDCANCEL = 1001,
          EXECUTEDFAIL = 1002,
          EXECUTEDSUCCESS = 1003;



    public static $errorMessages = [
        self::UNEXECUTED => '待执行!(:>',
        self::EXECUTEDCANCEL => '执行取消!(:<',
        self::EXECUTEDFAIL => '执行失败(:<',
        self::EXECUTEDSUCCESS => '执行成功!(:'
    ];
}
