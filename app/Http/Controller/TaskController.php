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

use App\Exception\TaskStatus;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use App\Model\Logic\TaskWorkLogic;
use App\Http\Middleware\AuthMiddleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;
use Swoft\Task\Task;
use Swoft\Validator\Annotation\Mapping\Validate;

/**
 * Class TaskController
 *
 * @since 2.0
 *
 * @Controller(prefix="task")
 */
class TaskController
{
    /**
     * @Inject()
     * @var TaskWorkLogic
     */
    private $taskWorkLogic;

    /**
     * @RequestMapping(route="list",method={"GET"})
     * @Middleware(AuthMiddleware::class)
     * @param Request $request
     * @param Response $response
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     * @throws \Swoft\Db\Exception\DbException
     */
    public function getList(Request $request, Response $response)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $uid = $request->get('uid', UID());
        $task = $request->get('taskId');

        return apiSuccess($this->taskWorkLogic->getTaskWorkPagingByUid($uid, (int)$page, (int)$limit, (string)$task));
    }

    /**
     * Notes: 获取 单个 task 详细信息
     * @RequestMapping(route="info",method={"GET"})
     * @Validate(validator="TaskWorkValidator",fields={"taskId"})
     * @Middleware(Authmiddleware::class)
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     * @throws \Swoft\Db\Exception\DbException
     */
    public function getTaskInfo(Request $request)
    {
        $task = $request->get('taskId');

        /** @var array $taskData */
        $taskData = $this->taskWorkLogic->findByTaskIdInfo($task);

        if ($taskData) {
            $taskData = $taskData->toArray();
            $username = getUserInfo($taskData['uid'])->getUsername() ?? '此用户异常';
            $taskData['username'] = $username;
            $taskData['status'] = TaskStatus::$errorMessages[$taskData['status']];
        }

        return apiSuccess($taskData);
    }
}
