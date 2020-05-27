<?php


namespace App\Model\Dao;

use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use SwoftMongo\Mongo;

/**
 * Class UserDao
 * @package App\Model\Dao
 * @Bean()
 */
class UserDao
{
    /**
     * @Inject()
     * @var User
     */
    private $userEntity;

    /**
     * 返回用户信息
     * @param int $userId
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Collection|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
     */
    public function findUserInfoById(int $userId)
    {
        return $this->userEntity::whereNull('delete_at')->find($userId);
    }

    /**
     * 根据 用户 账号获取信息
     * @param string $account
     * @return object|\Swoft\Db\Eloquent\Builder|\Swoft\Db\Eloquent\Model|null
     * @throws \Swoft\Db\Exception\DbException
     */
    public function findUserInfoByAccount(string $account)
    {
        return $this->userEntity::whereNull('delete_at')->where('account', '=', $account)->first();
    }

    /**
     * 写入数据集合
     * @param array $data
     * @return bool
     */
    public function createUser(array $data)
    {
        return $this->userEntity::insert($data);
    }

    /**
     * 更新数据集
     * @param int $userId
     * @param array $data
     * @return int
     * @throws \Swoft\Db\Exception\DbException
     */
    public function updateById(int $userId, array $data)
    {
        return $this->userEntity::whereNull('delete_at')->where('id', '=', $userId)->update($data);
    }
}
