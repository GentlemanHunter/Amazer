<?php

use App\ExceptionCode\TaskStatus;

return [
    TaskStatus::UNEXECUTED => '待执行!(:>',
    TaskStatus::EXECUTEDCANCEL => '执行取消!(:<',
    TaskStatus::EXECUTEDFAIL => '执行失败(:<',
    TaskStatus::EXECUTEDSUCCESS => '执行成功!(:',
    TaskStatus::EXECUTEVERSION => "编辑的版本-:)."
];
