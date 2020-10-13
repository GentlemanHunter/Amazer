<?php

return [
    'taskToId' => [// 任务id 绑定 定时器id
        'size' => 1024 * 5,
        'columns' => [
            'timerId' => [
                'type' => \Swoole\Table::TYPE_INT,
                'size' => 4
            ]
        ]
    ],
];
