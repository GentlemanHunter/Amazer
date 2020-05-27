<?php


namespace App\Exception;


class TaskStatus
{
    /** 执行任务状态 1000-1999 */
    const UNEXECUTED = 1000,
          EXECUTED = 1001,
          EXECUTEDFAIL = 1002,
          EXECUTEDSUCCESS = 1003;



    public static $errorMessages = [
        self::UNEXECUTED => 'Task not yet performed!(:>',
        self::EXECUTED => 'Task executed!(:<',
        self::EXECUTEDFAIL => 'Task execution failed!(:<',
        self::EXECUTEDSUCCESS => 'Task executed successfully!(:'
    ];
}
