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

use App\ExceptionCode\ApiCode;
use App\Model\Dao\UserDao;
use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

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
    private $userDao;

    /**
     * 根据用户账号 返回信息
     * @param string $account
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
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
     * @throws \Swoft\Db\Exception\DbException
     */
    public function register(string $account, string $password, string $username, string $code)
    {
        $userInfo = $this->findUserInfoByAccount($account);
        if ($userInfo) {
            throw new \Exception('', ApiCode::USER_ACCOUNT_ALREADY_USER);
        }

//        \bean(VerifyLogic::class)->enterVerify($account, $code);

        $request = context()->getRequest();
        $ip = empty($request->getHeaderLine('x-real-ip')) ? $request->getServerParams()['remote_addr']
            : $request->getHeaderLine('x-real-ip');

        return $this->createUser(
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
     * 创建用户
     * @param array $data
     * @return bool
     */
    public function createUser(array $data)
    {
        return $this->userDao->createUser($data);
    }

    /**
     * 登陆事件
     * @param string $account
     * @param string $password
     * @return array
     * @throws \Swoft\Db\Exception\DbException
     */
    public function login(string $account, string $password)
    {
        $userInfo = $this->findUserInfoByAccount($account);
        if (!$userInfo || $userInfo['delete_at'] != null) {
            throw new \Exception('', ApiCode::USER_NOT_FOUND);
        }
        if (!password_verify($password, $userInfo['password'])) {
            throw new \Exception('', ApiCode::USER_PASSWORD_ERROR);
        }
        $this->updateUserLogVisitor($userInfo->getId());

        return $userInfo->toArray();
    }

    /**
     * 更新用户登录ip
     * @param int $userId
     * @return int
     * @throws \Swoft\Db\Exception\DbException
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
}
