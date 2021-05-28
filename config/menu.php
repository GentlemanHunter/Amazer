<?php

return [
    [
        'title' => '设备管理',
        'child' => [
            [
                'title' => '设备列表',
                'id' => 'list',
                'url' => '/views/machine',
                'width' => '1670px',
                'height' => '750px',
            ]
        ]
    ],
    [
        'title' => '任务管理',
        'child' => [
            [
                'title' => '任务列表',
                'id' => 'taskList',
                'url' => '/views/task',
                'width' => '1670px',
                'height' => '750px',
            ],
            [
                'title' => '新增任务',
                'id' => 'createTask',
                'url' => '/views/task/add',
                'width' => '1670px',
                'height' => '750px'
            ],
            /*[
                'title' => '编辑任务',
                'id' => 'editTask',
                'url' => '/views/task/edit',
                'width' => '1670px',
                'height' => '750px'
            ]*/
        ]
    ],
    /*[
        'title' => '其它',
        'child' => [
            [
                'title' => '关于',
                'id' => 'about',
                'url' => '/static/about',
                'width' => '1000px',
                'height' => '520px',
            ]
        ]
    ]*/
];


//return '<pre class="layui-code" lay-height="100px">' + JSON.stringify(d.bodys, null, ' ') + '</pre>';
