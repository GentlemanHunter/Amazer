<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Task\Task;

use App\Model\Dao\Test3Dao;
use App\Model\Entity\Test;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class TestTask
 *
 * @since 2.0
 *
 * @Task(name="testTask")
 */
class TestTask
{
    /**
     * @TaskMapping(name="list")
     *
     * @param int    $id
     * @param string $default
     *
     * @return array
     */
    public function getList(int $id, string $default = 'def'): array
    {
        return [
            'list'    => [1, 3, 3],
            'id'      => $id,
            'default' => $default
        ];
    }

    /**
     * @TaskMapping()
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($id > 10) {
            return true;
        }

        return false;
    }

    /**
     * @TaskMapping()
     *
     * @param string $name
     *
     * @return null
     */
    public function returnNull(string $name)
    {
        return null;
    }

    /**
     * @TaskMapping()
     *
     * @param string $name
     */
    public function returnVoid(string $name): void
    {
        return;
    }

    /**
     * @Inject()
     * @var Test3Dao
     */
    private $test3Dao;

    /**
     * @TaskMapping()
     * @param $name
     * @param $run_time
     */
    public function test($name,$run_time)
    {
        // TODO: 消费task
        $data = [
            'name' => $name,
            'runTime' => $run_time,
            'createtime' => time(),
            'updatetime' => time(),
            'status' => 0
        ];
        $id = $this->test3Dao->addData($data);
    }
}
