<?php


namespace App\Model\Dao;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Redis\Redis;

/**
 * Class RedisListDao
 * @Bean()
 * @package App\Model\Dao
 */
class RedisListDao
{
    private static $Key = 'log_list';

    /**
     * 写入 list 一条数据
     * @param array $value
     * @return bool|int
     */
    public function addListDataAux(array $value)
    {
        return Redis::lPush(self::$Key, json_encode($value, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 返回一条数据
     * @return bool|string
     */
    public function getPopListDataAux()
    {
        return Redis::lPop(self::$Key);
    }

    /**
     * 返回当前的 长度
     * @return int
     */
    public function getCount()
    {
        return Redis::lLen(self::$Key);
    }

    /**
     * 范围获取 数据
     * @param int $start
     * @param int $end
     * @return array
     */
    public function getRangList(int $start, int $end)
    {
        return Redis::lRange(self::$Key, $start, $end);
    }

    /**
     * 移除 某个元素
     * @param string $value
     * @param int $count
     * @return bool|int
     */
    public function delListData(string $value, int $count)
    {
        return Redis::lRem(self::$Key, $value, $count);
    }
}
