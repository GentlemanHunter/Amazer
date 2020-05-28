<?php


namespace App\Model\Dao;

use Swoft\Redis\Redis;

class RedisSsetDao
{
    const KEY = 'zset_data';

    /**
     * 协程 内部 使用方法
     * @param $score
     * @param $value
     * @return int
     */
    public function addTaskDataAux($score, $value)
    {
        return Redis::zAdd(self::KEY, [$value => $score]);
    }

    /**
     * 协程 异步 使用方法
     * @param $score
     * @return mixed
     */
    public function findByScoureCo($score)
    {
        return Redis::zRangeByScore(self::KEY, $score, $score);
    }
}
