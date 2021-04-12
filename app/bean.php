<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\Db\Pool;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;
use Swoft\Server\SwooleEvent;
use Swoft\Http\Server\HttpServer;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;

return [
    'noticeHandler'      => [
        'logFile' => '@runtime/logs/notice-%d{Y-m-d-H}.log',
    ],
    'applicationHandler' => [
        'logFile' => '@runtime/logs/error-%d{Y-m-d}.log',
    ],
    'logger'             => [
        'flushRequest' => true,
        'enable'       => true,
        'json'         => false,
    ],
    'httpServer'         => [
        'class'    => HttpServer::class,
        'port'     => env('HTTP_PORT',18306),
        'listener' => [
        ],
        'process'  => [
            'logconsump' => bean(\App\Process\LogConsumptionProcess::class),
            'crontab' => bean(\Swoft\Crontab\Process\CrontabProcess::class)
        ],
        'on'       => [
//            SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true,
            'worker_num'            => 6,
            // static handle
             'enable_static_handler'    => true,
             'document_root'            => dirname(__DIR__) . '/public',
        ]
    ],
    'httpDispatcher'     => [
        // Add global http middleware
        'middlewares'      => [
            \App\Http\Middleware\FavIconMiddleware::class,
            \Swoft\Http\Session\SessionMiddleware::class,
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
        ],
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class
        ]
    ],
    'sessionManager' => [
        'class' => \Swoft\Http\Session\SessionManager::class,
        'name' => 'Wharf_SESSION_ID'
    ],
    'db'                 => [
        'class'    => Database::class,
        'dsn'      => env('DB_DSN'),
        'username' => env('DB_USERNAME','root'),
        'password' => env('DB_PASSWORD','123456'),
        'prefix'   => env('DB_PREFIX'),
        'charset'  => 'utf8mb4',
    ],
    'db.pool'            => [
        'class'    => Pool::class,
        'database' => bean('db')
    ],
    'db2'                => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test2;host=127.0.0.1',
        'username' => 'root',
        'password' => 'swoft123456',
    ],
    'db2.pool'           => [
        'class'    => Pool::class,
        'database' => bean('db2'),
    ],
    'migrationManager'   => [
        'migrationPath' => '@database/Migration',
    ],
    'redis'              => [
        'class'    => RedisDb::class,
        'host'     => env('REDIS_HOST'),
        'port'     => env('REDIS_PROT',6379),
        'database' => env('REDIS_DB',0),
        'password' => env('REDIS_PASS'),
        'option'   => [
            'prefix' => env('REDIS_PREFIX','swoft:')
        ]
    ]
];
