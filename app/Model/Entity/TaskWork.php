<?php declare(strict_types=1);


namespace App\Model\Entity;

use App\Exception\TaskStatus;
use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 系统任务表
 * Class TaskWork
 *
 * @since 2.0
 *
 * @Entity(table="task_work")
 */
class TaskWork extends Model
{
    /**
     * 任务id
     * @Id(incrementing=false)
     * @Column(name="task_id", prop="taskId")
     *
     * @var string
     */
    private $taskId;

    /**
     * 任务名称
     *
     * @Column()
     *
     * @var string
     */
    private $names;

    /**
     * 任务描述
     *
     * @Column()
     *
     * @var string|null
     */
    private $describe;

    /**
     * 执行时间
     *
     * @Column()
     *
     * @var int
     */
    private $execution;

    /**
     * 任务重试次数 默认1 重试一次
     *
     * @Column()
     *
     * @var int
     */
    private $retry;

    /**
     * 执行体
     *
     * @Column()
     *
     * @var array
     */
    private $bodys;

    /**
     * 虚拟状态 由模型去控制 默认 0
     *
     * @Column()
     *
     * @var int
     */
    private $status;

    /**
     * 用户id
     *
     * @Column()
     *
     * @var int
     */
    private $uid;

    /**
     * 创建时间
     *
     * @Column(name="created_at", prop="createdAt")
     *
     * @var int
     */
    private $createdAt;

    /**
     * 更新时间
     *
     * @Column(name="updated_at", prop="updatedAt")
     *
     * @var int
     */
    private $updatedAt;


    /**
     * @param string $taskId
     *
     * @return self
     */
    public function setTaskId(string $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * @param string $names
     *
     * @return self
     */
    public function setNames(string $names): self
    {
        $this->names = $names;

        return $this;
    }

    /**
     * @param string|null $describe
     *
     * @return self
     */
    public function setDescribe(?string $describe): self
    {
        $this->describe = $describe;

        return $this;
    }

    /**
     * @param int $execution
     *
     * @return self
     */
    public function setExecution(int $execution): self
    {
        $this->execution = $execution;

        return $this;
    }

    /**
     * @param int $retry
     *
     * @return self
     */
    public function setRetry(int $retry): self
    {
        $this->retry = $retry;

        return $this;
    }

    /**
     * @param array $bodys
     *
     * @return self
     */
    public function setBodys(array $bodys): self
    {
        $this->bodys = $bodys;

        return $this;
    }

    /**
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param int $uid
     *
     * @return self
     */
    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @param int $createdAt
     *
     * @return self
     */
    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param int $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getTaskId(): ?string

    {
        return $this->taskId;
    }

    /**
     * @return string
     */
    public function getNames(): ?string

    {
        return $this->names;
    }

    /**
     * @return string|null
     */
    public function getDescribe(): ?string

    {
        return $this->describe;
    }

    /**
     * @return string
     */
    public function getExecution(): ?string

    {
        return date('Y-m-d H:i:s',$this->execution);
    }

    /**
     * @return int
     */
    public function getRetry(): ?int

    {
        return $this->retry;
    }

    /**
     * @return array
     */
    public function getBodys(): ?array

    {
        return $this->bodys;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return TaskStatus::$errorMessages[$this->status];
    }

    /**
     * @return int
     */
    public function getUid(): ?int

    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): ?string

    {
        return date('Y-m-d H:i:s',$this->createdAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): ?string
    {
        return date('Y-m-d H:i:s',$this->updatedAt);
    }


}
