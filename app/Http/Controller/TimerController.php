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

use Exception;
use Swoft\Timer;
use Swoft\Task\Task;
use Swoft\Redis\Redis;
use Swoft\Log\Helper\Log;
use App\Model\Entity\User;
use Swoft\Log\Helper\CLog;
use App\Helper\MemoryTable;
use Swoft\Http\Message\Request;
use App\Model\Dao\RedisHashDao;
use App\Exception\ApiException;
use App\Model\Logic\RedisLogic;
use Swoft\Http\Message\Response;
use App\ExceptionCode\TaskStatus;
use App\Model\Logic\TaskWorkLogic;
use Swoft\Stdlib\Helper\JsonHelper;
use App\Http\Middleware\AuthMiddleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class TimerController
 *
 * @since 2.0
 *
 * @Controller()
 */
class TimerController
{
    /**
     * @Inject()
     * @var RedisLogic
     */
    private $redisLogic;

    /**
     * 新增 任务
     * @RequestMapping(route="/task",method={RequestMethod::POST})
     * @Validate(validator="TaskWorkValidator",fields={"names","describe","execution","retry","bodys"})
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function createTask(Request $request)
    {
        try {
            $names = $request->parsedBody('names');
            $describe = $request->parsedBody('describe');
            $execution = isTimestamp($request->parsedBody('execution'));
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
     * 删除任务
     * @RequestMapping(route="/task",method={RequestMethod::DELETE})
     * @Validate(validator="TaskWorkValidator",fields={"taskId"})
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function cancelTaskWork(Request $request)
    {
        try {
            $taskId = $request->parsedBody('taskId');

            /** @var RedisHashDao $redisHashDao */
            $redisHashDao = bean('App\Model\Dao\RedisHashDao');
            $value = $redisHashDao->findByKeyAux($taskId);

            if (!$value) {
                /** @var TaskWorkLogic $taskWorkLogic */
                $taskWorkLogic = bean('App\Model\Logic\TaskWorkLogic');
                $value = $taskWorkLogic->findByTaskIdInfo($taskId);

                if (!$value) throw new ApiException("任务不存在", -1);

                if ($value->getStatus(true) > TaskStatus::UNEXECUTED)
                    throw new ApiException(TaskStatus::$errorMessages[$value->getStatus(true)], -1);

                if (($value->getExecution(true) - time()) <= 2)
                    throw new ApiException("任务执行时间小于 2 秒 禁止操作!!", -1);

            } else {
                $value = redisHashArray($value);
                if (($value['execution'] - time()) <= 2)
                    throw new ApiException("任务执行时间 小于 2秒 禁止操作!!", -1);
            }

            Task::co('work', 'delQueue', [$taskId]);

            return apiSuccess(['taskId' => $taskId]);
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }

    /**
     * 修改 任务
     * @RequestMapping(route="/task",method={RequestMethod::PUT})
     * @Validate(validator="TaskWorkValidator",fields={"taskId","names","describe","execution","retry","bodys"})
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function editTask(Request $request)
    {
        try {
            $taskId = $request->parsedBody('taskId');
            $names = $request->parsedBody('names');
            $describe = $request->parsedBody('describe');
            $execution = isTimestamp($request->parsedBody('execution'));
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

            Task::co('work','editQueue',[
                $taskId
                ,$names
                ,$describe
                ,$execution
                ,$retry
                ,$bodys
                ,1
            ]);

            return apiSuccess(['taskId' => $taskId]);
        } catch (\Throwable $throwable){
            return apiError($throwable->getCode(),$throwable->getMessage());
        }
    }

    /**
     * Notes: 测试返回结果的影响
     * @RequestMapping(route="/setTime")
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     * @author: MagicConch17
     */
    public function setTime(Request $request)
    {
        $time = time();
        $request = json_encode($request->getBody());

        return apiSuccess([
            'request' => $request,
            'time' => $time
        ]);
    }
}
