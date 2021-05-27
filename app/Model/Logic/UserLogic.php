<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Model\Logic;

use App\Enum\ActionEnum;
use App\Exception\ApiException;
use App\ExceptionCode\ApiCode;
use App\Listener\ActionLog;
use App\Model\Dao\UserDao;
use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;

/**
 * Class UserLogic
 * @Bean()
 * @package App\Model\Logic
 */
class UserLogic
{
    /**
     * @Inject()
     * @var UserDao
     */
    public $userDao;

    /**
     * 根据用户账号 返回信息
     * @param string $account
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     * @throws DbException
     */
    public function findUserInfoByAccount(string $account)
    {
        return $this->userDao->findUserInfoByAccount($account);
    }

    /**
     * 注册用户
     * TODO: 验证码占位
     * @param string $account
     * @param string $password
     * @param string $username
     * @param string $code
     * @return bool
     * @throws DbException|\Exception
     */
    public function register(string $account, string $password, string $username, string $code)
    {
        $userInfo = $this->findUserInfoByAccount($account);
        if ($userInfo) {
            throw new ApiException(ApiCode::USER_ACCOUNT_ALREADY_USER);
        }

//        \bean(VerifyLogic::class)->enterVerify($account, $code);

        $request = context()->getRequest();
        $ip = empty($request->getHeaderLine('x-real-ip')) ? $request->getServerParams()['remote_addr']
            : $request->getHeaderLine('x-real-ip');

        return $this->userDao->createUser(
            [
                'account' => $account,
                'username' => $username,
                'password' => password_hash($password, CRYPT_BLOWFISH),
                'visitor' => $ip,
                'create_at' => time(),
                'update_at' => time()
            ]
        );
    }

    /**
     * 登陆事件
     * @param string $account
     * @param string $password
     * @return array
     * @throws DbException
     * @throws \Exception
     */
    public function login(string $account, string $password)
    {
        /** @var User $userInfo */
        $userInfo = $this->findUserInfoByAccount($account);
        if (!$userInfo || $userInfo['delete_at'] != null) {
            throw new ApiException(ApiCode::USER_NOT_FOUND);
        }
        if (!password_verify($password, $userInfo['password'])) {
            throw new ApiException(ApiCode::USER_PASSWORD_ERROR);
        }

        $this->updateUserLogVisitor($userInfo->getId());

        actionLog(ActionLog::USERLOGIN, ActionEnum::USERLOGIN, [
            'uid' => $userInfo->getId()
        ]);

        return $userInfo->toArray();
    }

    /**
     * 更新用户登录ip
     * @param int $userId
     * @return int
     * @throws DbException
     */
    public function updateUserLogVisitor(int $userId)
    {
        $request = context()->getRequest();
        $ip = empty($request->getHeaderLine('x-real-ip')) ? $request->getServerParams()['remote_addr']
            : $request->getHeaderLine('x-real-ip');
        $data = [
            'visitor' => $ip
        ];
        return $this->userDao->updateById($userId, $data);
    }

    /**
     * 更新用户信息
     * @param int $userId
     * @param string $username
     * @return int
     * @throws DbException
     */
    public function updateInfo(int $userId, string $username)
    {
        return $this->userDao->updateById($userId, [
            'username' => $username
        ]);
    }
}
