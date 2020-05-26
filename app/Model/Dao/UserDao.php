<?php


namespace App\Model\Dao;

use Swoft\Bean\Annotation\Mapping\Bean;
use SwoftMongo\Mongo;

/**
 * Class UserDao
 * @package App\Model\Dao
 * @Bean()
 */
class UserDao
{
    private $Collection = 'user';

    /**
     * 写入mongodb 数据
     * @param array $data
     * @return bool|mixed
     * @throws \SwoftMongo\MongoDBException\
     */
    public function insert(array $data)
    {
        return Mongo::insert($this->Collection,$data);
    }
}
