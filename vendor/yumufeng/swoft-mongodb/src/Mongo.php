<?php

namespace SwoftMongo;


use MongoDB\Driver\Exception\Exception;
use Swoft\App;

class Mongo
{
    /**
     * 返回满足filer的全部数据
     *
     * @param string $namespace
     * @param array $filter
     * @param array $options
     * @return array
     * @throws MongoDBException
     */
    public static function fetchAll(string $namespace, array $filter = [], array $options = []): array
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->executeQueryAll($namespace, $filter, $options);
        } catch (\Exception  $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return [];
        } catch (Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return [];
        }
    }

    /**
     * 返回满足filer的分页数据
     *
     * @param string $namespace
     * @param int $limit
     * @param int $currentPage
     * @param array $filter
     * @param array $options
     * @return array
     * @throws MongoDBException
     */
    public static function fetchPagination(string $namespace, int $limit, int $currentPage, array $filter = [], array $options = []): array
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->execQueryPagination($namespace, $limit, $currentPage, $filter, $options);
        } catch (\Exception  $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return [];
        } catch (Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return [];
        }
    }

    /**
     * 批量插入
     * @param $namespace
     * @param array $data
     * @return bool|string
     * @throws \Swoft\Db\Exception\DbException
     * @throws \Swoft\Exception\ConnectionException
     * @throws MongoDBException
     */
    public static function insertAll($namespace, array $data)
    {
        if (count($data) == count($data, 1)) {
            throw new  MongoDBException('data is can only be a two-dimensional array');
        }
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->insertAll($namespace, $data);
        } catch (MongoDBException $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 数据插入数据库
     *
     * @param $namespace
     * @param array $data
     * @return bool|mixed
     * @throws MongoDBException
     */
    public static function insert($namespace, array $data = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->insert($namespace, $data);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 更新数据满足$filter的行的信息成$newObject
     *
     * @param $namespace
     * @param array $filter
     * @param array $newObj
     * @return bool
     * @throws MongoDBException
     */
    public static function updateRow($namespace, array $filter = [], array $newObj = []): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->updateRow($namespace, $filter, $newObj);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 只更新数据满足$filter的行的列信息中在$newObject中出现过的字段
     *
     * @param $namespace
     * @param array $filter
     * @param array $newObj
     * @return bool
     * @throws MongoDBException
     */
    public static function updateColumn($namespace, array $filter = [], array $newObj = []): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->updateColumn($namespace, $filter, $newObj);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 删除满足条件的数据，默认只删除匹配条件的第一条记录，如果要删除多条$limit=true
     *
     * @param string $namespace
     * @param array $filter
     * @param bool $limit
     * @return bool
     * @throws MongoDBException
     */
    public static function delete(string $namespace, array $filter = [], bool $limit = false): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->delete($namespace, $filter, $limit);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 返回collection中满足条件的数量
     *
     * @param string $namespace
     * @param array $filter
     * @return bool
     * @throws MongoDBException
     */
    public static function count(string $namespace, array $filter = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->count($namespace, $filter);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }


    /**
     * 聚合查询
     * @param string $namespace
     * @param array $filter
     * @return bool
     * @throws Exception
     * @throws MongoDBException
     */
    public static function command(string $namespace, array $filter = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = self::getConnection();
            return $collection->command($namespace, $filter);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取一个当前的可用连接
     *
     * @throws \Exception
     * @throws \Swoft\Db\Exception\DbException
     * @throws \Swoft\Exception\ConnectionException
     */
    private static function getConnection()
    {
        /**
         * @var $pool MongoDBPool
         */
        $pool = App::getPool('mongo');
        return $pool->getConnection();
    }
}
