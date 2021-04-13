<?php


namespace App\Rpc\Lib;


interface TaskInterface
{
    /**
     * Notes:
     */
    public function server(array $taskList): void;

    public function delTask(string $taskId): bool;

    public function inserTask(string $taskId, int $execution): bool;
}
