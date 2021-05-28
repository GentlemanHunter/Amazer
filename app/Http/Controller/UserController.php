<?php


namespace App\Http\Controller;

use App\Listener\ActionLog;
use Swoft\Db\DB;
use App\Helper\JwtHelper;
use App\Helper\AuthHelper;
use App\Model\Entity\User;
use App\Model\Logic\UserLogic;
use Swoft\Http\Message\Concern\CookiesTrait;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ViewsMiddleware;

/**
 * Class UserController
 *
 * @Controller("user")
 * @package App\Http\Controller
 */
class UserController
{
    use AuthHelper;

    use JwtHelper;

    /**
     * @Inject()
     * @var UserLogic
     */
    private $userLogic;

    /**
     * @RequestMapping(route="register",method={RequestMethod::POST})
     * @Validate(validator="UserValidator",fields={"account","password"})
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = $request->parsedBody('account');
            $password = $request->parsedBody('password');
            $code = $request->parsedBody('code') ?? 1;
            $this->userLogic->register($account, $password, $account, $code);
            DB::commit();
            return apiSuccess();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }

    /**
     * 用户登录
     * @RequestMapping(route="login",method={RequestMethod::POST})
     * @Validate(validator="UserValidator",fields={"account"})
     * @param Request $request
     * @param Response $response
     * @return CookiesTrait|Response
     */
    public function login(Request $request, Response $response)
    {
        $account = $request->parsedBody('account');
        $password = $request->parsedBody('password');

        /** @var User $userInfo */
        $userInfo = $this->userLogic->login($account, $password);

        $token = JwtHelper::encrypt($userInfo['id']);
        $userInfo['token'] = $token;
        return $response->withCookie('TOKEN_WHARF', [
            'value' => $token,
            'path' => '/',
        ])->withData(['code' => 0, 'msg' => 'Success', 'data' => $userInfo]);
    }

    /**
     * 用户退出
     * @RequestMapping(route="signout",method={RequestMethod::GET})
     * @Middleware(ViewsMiddleware::class)
     * @param Request $request
     * @param Response $response
     * @return CookiesTrait|Response
     */
    public function signout(Request $request, Response $response)
    {
        return context()->getResponse()->withCookie('TOKEN_WHARF', [
            'value' => '',
            'path' => '/'
        ])->redirect('/views/login');
    }

    /**
     * Notes: 获取用户信息
     * @RequestMapping(route="info",method={RequestMethod::GET})
     * @Middleware(AuthMiddleware::class)
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function userInfo(Request $request)
    {

        return apiSuccess($request->userInfo);
    }

    /**
     * Notes: 修改用户信息
     * @RequestMapping(route="update",method={RequestMethod::POST})
     * @Middleware(AuthMiddleware::class)
     * @Validate(validator="UserValidator",fields={"username"})
     * @param Request $request
     * @return Response|\Swoft\Rpc\Server\Response|\Swoft\Task\Response
     */
    public function changeUserInfo(Request $request)
    {
        try {
            $username = $request->parsedBody('username');
            $result = $this->userLogic->updateInfo($request->user, $username);
            return apiSuccess($result);
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }
}
