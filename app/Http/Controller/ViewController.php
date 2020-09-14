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
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
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
     * @RequestMapping("index")
     *
     * @param Response $response
     *
     * @return Response
     */
    public function index(Response $response): Response
    {
        $response = $response->withContent('<html lang="en"><h1>Swoft framework</h1></html>');
        $response = $response->withContentType(ContentType::HTML);
        return $response;
    }

    /**
     * Will render view by annotation tag View
     *
     * @RequestMapping("/home")
     * @View("home/index")
     *
     * @throws Throwable
     */
    public function indexByViewTag(): array
    {
        return [
            'msg' => 'hello'
        ];
    }

    /**
     * @RequestMapping(route="login", method={"GET"})
     * @return Response
     * @throws Throwable
     */
    public function login()
    {
        return view('home/login');
    }

    /**
     * @RequestMapping(route="register", method={"GET"})
     * @return Response
     * @throws Throwable
     */
    public function register()
    {
        return view('home/register');
    }

    /**
     * 获取用户菜单
     * @RequestMapping(route="home", method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     */
    public function home(Request $request, Response $response)
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
     */
    public function userInfo(Request $request, Response $response)
    {
        return view('user/info');
    }

    /**
     * @RequestMapping(route="machine",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @return Response
     * @throws Throwable
     */
    public function machine()
    {
        return view('machine/list');
    }

    /**
     * @RequestMapping(route="task",method={"GET"})
     * @Middleware(ViewsMiddleware::class)
     * @return Response
     * @throws Throwable
     */
    public function task()
    {
        return view('task/list');
    }

    /**
     * @RequestMapping(route="/test",method={"GET"})
     */
    public function test()
    {
        /** @var Wechat $wechat */
        $wechat = bean('App\Common\Wechat');
        $message = sprintf(
            Wechat::$message[Wechat::ERRORLOG],
            'test-test',
            date('Y/m/d H:i:s',time()),
            'http://baidu.com',
            '{"url": "http://host.docker.internal/test/request.php?id=2", "method": "POST", "form_params": {"id": 1}}'
        );
        $wechat->sendMarkdownMessage($message);

        return apiSuccess();
    }
}
