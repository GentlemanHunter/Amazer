<?php


namespace App\Model\Dao;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Redis\Redis;

/**
 * Class RedisHashDao
 * @Bean()
 * @package App\Model\Dao
 */
class RedisHashDao
{
    private static $KEY = 'hash_data';

    /**
     * 存储单条 redis 数据
     * @param string $gid
     * @param array $data
     * @return bool
     */
    public function addHashDataAux($gid, $data)
    {
        $redisId = Redis::hSet(self::$KEY, $gid, serialize($data));
        return ($redisId) ? $gid : false;
    }

    /**
     * 存储全部 redis 数据
     * @param $data
     */
    public function addAllHashDataAux($data): void
    {
        foreach ($data as $key => $value) {
            $gid = getGuid();
            Redis::hSet(self::$KEY, $gid, serialize($value));
        }
    }

    /**
     * 返回单条数据
     * @param $key
     * @return mixed
     */
    public function findByKeyAux($key)
    {
        return Redis::hGet(self::$KEY, $key);
    }

    /**
     * 返回当前 redis 内的长度
     * @return int
     */
    public function getLengthAux()
    {
        return Redis::hLen(self::$KEY);
    }

    /**
     * 删除 指定的 key
     * @param $key
     * @return string
     */
    public function delByKeyAux($key)
    {
        return Redis::hDel(self::$KEY, $key);
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        self::$KEY = $key;
        return $this;
    }
}
