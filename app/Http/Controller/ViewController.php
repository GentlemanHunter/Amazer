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

use App\Common\Wechat;
use App\ExceptionCode\ApiCode;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Redis\Redis;
use Swoft\View\Annotation\Mapping\View;
use App\Http\Middleware\ViewsMiddleware;
use Throwable;

/**
 * Class ViewController
 *
 * @since 2.0
 *
 * @Controller(prefix="/views")
 */
class ViewController
{
    /**
     * @RequestMapping(route="login", method={"GET"})
     * @return Response
     * @throws Throwable
     */
    public function login(): Response
    {
        return view('home/login');
    }

    /**
     * Notes:
     * @RequestMapping(route="rest/password",method={"GET"})
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Throwable
     */
    public function resetPassword(Request $request, Response $response): Response
    {
        $refToken = $request->input('ref-token') ?? $request->getHeaderLine('ref-token');
        if (!empty($refToken) && is_string($refToken)) {
            $account = $request->input('account');
            $refTokenRedis = Redis::get($account . date('Y-m-d-H', time()));
            if ($refTokenRedis && $refToken === $refTokenRedis) {
                return view('user/reset', [
                    'select' => 'password',
                    'data' => [
                        'account' => $account,
                        'ref-token' => $refTokenRedis
                    ]
                ]);
            }
        }

        return $response->withData([
            'code' => ApiCode::AUTH_ERROR,
            'msg' => ApiCode::result(ApiCode::AUTH_ERROR),
            'data' => []
        ])->redirect('/views/login');
    }

    /**
     * @RequestMapping(route="register", method={"GET"})
     * @return Response
     * @throws Throwable
     */
    public function register(): Response
    {
        return view('home/register');
    }

    /**
     * 获取用户菜单
     * @RequestMapping(route="home", method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function home(Request $request): Response
    {
        $menus = config('menu');
        $userInfo = $request->userInfo;
        return view('home/home', [
            'menus' => $menus,
            'userInfo' => $userInfo
        ]);
    }

    /**
     * @RequestMapping(route="userInfo",method={"GET"})
     * @return Response
     * @throws Throwable
     */
    public function userInfo(): Response
    {
        return view('user/info');
    }

    /**
     * @RequestMapping(route="machine",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @return Response
     * @throws Throwable
     */
    public function machine(): Response
    {
        return view('machine/list');
    }

    /**
     * 获取 任务 列表
     * @RequestMapping(route="task",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @return Response
     * @throws Throwable
     */
    public function task(): Response
    {
        return view('task/list');
    }

    /**
     * 新增任务视图
     * @RequestMapping(route="task/add",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @throws Throwable
     */
    public function addTask(): Response
    {
        return view('task/insert');
    }

    /**
     * Notes: edit task view
     * @RequestMapping(route="task/edit",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @throws Throwable
     */
    public function editTask(): Response
    {
        return view('task/edit');
    }
}
