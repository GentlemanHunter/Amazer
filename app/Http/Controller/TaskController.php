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

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use App\Model\Logic\TaskWorkLogic;
use App\Http\Middleware\AuthMiddleware;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

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
        $page = $request->parsedBody('page') ?? 1;
        $limit = $request->parsedBody('limit') ?? 10;

        return apiSuccess($this->taskWorkLogic->getTaskWorkPagingByUid(UID(), (int)$page, (int)$limit));
    }
}
