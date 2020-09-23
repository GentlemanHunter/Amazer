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

use App\ExceptionCode\TaskStatus;
use Swoft\Db\Exception\DbException;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use App\Model\Logic\TaskWorkLogic;
use App\Http\Middleware\AuthMiddleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;

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
     * @throws DbException
     */
    public function getList(Request $request, Response $response)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $taskId = $request->get('taskid', null);

        return apiSuccess($this->taskWorkLogic->getTaskWorkPagingByUid(UID(), (int)$page, (int)$limit, (string)$taskId));
    }
}
