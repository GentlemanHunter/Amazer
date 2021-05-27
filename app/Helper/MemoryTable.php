<?php
/**
 * @author yxk yangxiukang@ketangpai.com
 */


namespace App\Helper;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoole\Table;

/**
 * Class MemoryTable
 * @package App\Helper
 * @Bean()
 */
class MemoryTable
{

    const TASK_TO_ID = 'taskToId';
    const TASK_ADMIN = 'taskAdmin';

    private $table;

    public function __construct()
    {
        $tables = config('table');
        foreach ($tables as $key => $table) {
            $this->table[$key] = new Table($table['size']);
            foreach ($table['columns'] as $columnKey => $column) {
                $this->table[$key]->column($columnKey, $column['type'], $column['size']);
            }
            $this->table[$key]->create();
        }
    }

    public function store(string $tableKey, string $key, array $value)
    {
        return $this->table[$tableKey]->set($key, $value);
    }

    public function forget(string $tableKey, string $key): bool
    {
        return $this->table[$tableKey]->del($key);
    }

    public function get(string $tableKey, string $key, string $field = null)
    {
        return $this->table[$tableKey]->get($key, $field);
    }

    public function count(string $tableKey){
        return $this->table[$tableKey]->count();
    }

    public function getTable(string $tableKey){
        return $this->table[$tableKey];
    }

}
