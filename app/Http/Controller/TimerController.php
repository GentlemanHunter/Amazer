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

use App\Exception\ApiException;
use App\Model\Entity\User;
use App\Model\Logic\TaskWorkLogic;
use Exception;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Swoft\Redis\Redis;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Timer;
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
     * @RequestMapping()
     *
     * @return array
     * @throws Exception
     */
    public function after(): array
    {
        Timer::after(3 * 1000, function (int $timerId) {
            $user = new User();
            $user->setAge(random_int(1, 100));
            $user->setUserDesc('desc');

            $user->save();
            $id = $user->getId();

            Redis::set("$id", $user->toArray());
            Log::info('用户ID=' . $id . ' timerId=' . $timerId);
            sgo(function () use ($id) {
                $user = User::find($id)->toArray();
                Log::info(JsonHelper::encode($user));
                Redis::del("$id");
            });
        });

        return ['after'];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Exception
     */
    public function tick(): array
    {
        Timer::tick(3 * 1000, function (int $timerId) {
            $user = new User();
            $user->setAge(random_int(1, 100));
            $user->setUserDesc('desc');

            $user->save();
            $id = $user->getId();

            Redis::set("$id", $user->toArray());
            Log::info('用户ID=' . $id . ' timerId=' . $timerId);
            sgo(function () use ($id) {
                $user = User::find($id)->toArray();
                Log::info(JsonHelper::encode($user));
                Redis::del("$id");
            });
        });

        return ['tick'];
    }

    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    private $taskWorkLogic;

    /**
     * @RequestMapping(route="/add/task",method={RequestMethod::POST})
     * @Middleware(AuthMiddleware::class)
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
            $id = $this->taskWorkLogic->createTaskWork(
                $names,
                $describe,
                $execution,
                $retry,
                $bodys,
                UID()
            );
            return apiSuccess(['taskId' => $id]);
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }
}
