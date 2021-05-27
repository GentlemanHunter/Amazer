<?php


namespace App\Model\Dao;

use App\Model\Entity\User;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Eloquent\Builder;
use Swoft\Stdlib\Collection;
use Swoft\Db\Eloquent\Model;
use Swoft\Db\Exception\DbException;

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
    public $userEntity;

    /**
     * 返回用户信息
     * @param int $userId
     * @return object|Builder|Collection|Model|null
     * @throws DbException
     */
    public function findUserInfoById(int $userId)
    {
        return $this->userEntity::whereNull('delete_at')->find($userId);
    }

    /**
     * 根据 用户 账号获取信息
     * @param string $account
     * @return object|Builder|Model|null
     * @throws DbException
     */
    public function findUserInfoByAccount(string $account)
    {
        return $this->userEntity::whereNull('delete_at')
            ->where('account', '=', $account)
            ->first();
    }

    /**
     * 写入数据集合
     * @param array $data
     * @return string
     */
    public function createUser(array $data): string
    {
        return $this->userEntity::insertGetId($data);
    }

    /**
     * 更新数据集
     * @param int $userId
     * @param array $data
     * @return int
     * @throws DbException
     */
    public function updateById(int $userId, array $data): int
    {
        return $this->userEntity::whereNull('delete_at')
            ->where('id', '=', $userId)
            ->update($data);
    }

    /**
     * 返回所有超级管理员
     * @return array
     */
    public function getAdminList(): array
    {
        $userList = [];
        $callback = function (Collection $user) use (&$userList) {
            foreach ($user as $item) {
                $userList[] = $item;
            }
        };
        $this->userEntity::whereNull('delete_at')
            ->where(['status' => 1, 'is_sys' => 1])
            ->orderBy('id', 'desc')
            ->chunkById(100, $callback);

        return $userList;
    }
}
