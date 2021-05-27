<?php

return [
    'taskToId' => [
        'size' => 1024 * 5,
        'columns' => [
            'timerId' => [
                'type' => \Swoole\Table::TYPE_INT,
                'size' => 4
            ]
        ]
    ],
    'taskAdmin' => [
        'size' => 1024 * 5,
        'columns' => [
            'list' => [
                'type' => \Swoole\Table::TYPE_STRING,
                'size' => 4
            ]
        ]
    ],
];
