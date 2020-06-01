<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Helper\MemoryTable;
use App\Model\Dao\RedisHashDao;
use Exception;
use Swoft\Task\Task;
use Swoft\Timer;
use Swoft\Redis\Redis;
use Swoft\Log\Helper\Log;
use App\Model\Entity\User;
use Swoft\Http\Message\Request;
use App\Exception\ApiException;
use App\Model\Logic\RedisLogic;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Validator\Annotation\Mapping\Validate;
use App\Http\Middleware\AuthMiddleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * Class TimerController
 *
 * @since 2.0
 *
 * @Controller(prefix="timer")
 */
class TimerController
{
    /**
     * @Inject()
     * @var RedisLogic
     */
    private $redisLogic;

    /**
     * @RequestMapping(route="/add/task",method={RequestMethod::POST})
     * @Validate(validator="TaskWorkValidator",fields={"names","describe","execution","retry","bodys"})
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function createTask(Request $request)
    {
        try {
            $names = $request->parsedBody('names');
            $describe = $request->parsedBody('describe');
            $execution = strtotime($request->parsedBody('execution'));
            $retry = $request->parsedBody('retry');
            $bodys = $request->parsedBody('bodys');
            if (!isJSON($bodys)) {
                throw new ApiException("body 不是 JSON", -1);
            }
            $bodys = json_decode($bodys, true);
            keyExists($bodys, 'url');
            keyExists($bodys, 'method');
            if (time() >= $execution || ($execution - time()) < 5) {
                throw new ApiException("不允许 设定 超过时间", -1);
            }
            $id = $this->redisLogic->createTaskWork(
                $names,
                $describe,
                $execution,
                $retry,
                $bodys,
                1
            );
            return apiSuccess(['taskId' => $id]);
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }

    /**
     * @RequestMapping(route="/del/task",method={RequestMethod::POST,RequestMethod::GET})
     * @Validate(validator="TaskWorkValidator",fields={"taskId"})
     * @param Request $request
     * @return \Swoft\Http\Message\Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function delTaskWork(Request $request)
    {
        try {
            $taskId = $request->parsedBody('taskId');

            /** @var RedisHashDao $redisHashDao */
            $redisHashDao = bean('App\Model\Dao\RedisHashDao');
            $value = $redisHashDao->findByKeyAux($taskId);

            if (!$value) {
                throw new ApiException("任务不存在！或者已经超过执行时间！", -1);
            }

            $value = redisHashArray($value);

            if (($value['execution'] - time()) <= 2) {
                throw new ApiException("任务执行时间 小于 2秒 禁止操作!!", -1);
            }

            Task::co('work', 'delQueue', [$taskId]);

            return apiSuccess(['taskId' => $taskId]);
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }
}
