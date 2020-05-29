<?php


namespace App\Http\Controller;

use App\Model\Entity\User;
use Swoft\Db\DB;
use App\Helper\JwtHelper;
use App\Helper\AuthHelper;
use App\Model\Logic\UserLogic;
use Swoft\Http\Message\Request;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Validator\Annotation\Mapping\Validate;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use App\Http\Middleware\AuthMiddleware;

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
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = $request->parsedBody('account');
            $password = $request->parsedBody('password');
            $code = $request->parsedBody('code')??1;
            $this->userLogic->register($account,$password,$account,$code);
            DB::commit();
            return apiSuccess();
        } catch (\Throwable $throwable){
            DB::rollBack();
            return apiError($throwable->getCode(),$throwable->getMessage());
        }
    }

    /**
     * 用户登录
     * @RequestMapping(route="login",method={RequestMethod::POST})
     * @Validate(validator="UserValidator",fields={"account","password"})
     */
    public function login(Request $request, Response $response)
    {
        try {
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
        } catch (\Throwable $throwable) {
            return apiError($throwable->getCode(), $throwable->getMessage());
        }
    }

    /**
     * 用户退出
     * @RequestMapping(route="signOut",method={RequestMethod::GET})
     * @Middleware(AuthMiddleware::class)
     */
    public function signOut(Request $request, Response $response)
    {
        return apiSuccess();
    }
}
