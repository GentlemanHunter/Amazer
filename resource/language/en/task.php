<?php

use App\ExceptionCode\TaskStatus;

return [
    TaskStatus::UNEXECUTED => 'preparing !(:>',
    TaskStatus::EXECUTEDCANCEL => 'Cancelled !(:<',
    TaskStatus::EXECUTEDFAIL => 'failure(:<',
    TaskStatus::EXECUTEDSUCCESS => 'success!(:',
    TaskStatus::EXECUTEVERSION => "Expired-:)."
];
