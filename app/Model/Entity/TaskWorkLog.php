<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 系统任务日志表
 * Class TaskWorkLog
 *
 * @since 2.0
 *
 * @Entity(table="task_work_log")
 */
class TaskWorkLog extends Model
{
    /**
     * 执行体
     *
     * @Column()
     *
     * @var array
     */
    private $bodys;

    /**
     * 完成时间
     *
     * @Column()
     *
     * @var int
     */
    private $complete;

    /**
     * 创建时间
     *
     * @Column(name="created_at", prop="createdAt")
     *
     * @var int
     */
    private $createdAt;

    /**
     * 开始时间
     *
     * @Column()
     *
     * @var int
     */
    private $execution;

    /**
     * 任务日志id
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * 执行时间
     *
     * @Column()
     *
     * @var int
     */
    private $implement;

    /**
     * 当前执行次数 排序
     *
     * @Column()
     *
     * @var int
     */
    private $length;

    /**
     * 超时时间 默认0 单位秒 0不超时
     *
     * @Column()
     *
     * @var int
     */
    private $overtime;

    /**
     * 任务执行结果
     *
     * @Column()
     *
     * @var string
     */
    private $result;

    /**
     * 虚拟状态 由模型去控制 默认 0
     *
     * @Column()
     *
     * @var int
     */
    private $status;

    /**
     * 任务id
     *
     * @Column(name="task_id", prop="taskId")
     *
     * @var string
     */
    private $taskId;

    /**
     * 更新时间
     *
     * @Column(name="updated_at", prop="updatedAt")
     *
     * @var int
     */
    private $updatedAt;


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
     * @param int $complete
     *
     * @return self
     */
    public function setComplete(int $complete): self
    {
        $this->complete = $complete;

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
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param int $implement
     *
     * @return self
     */
    public function setImplement(int $implement): self
    {
        $this->implement = $implement;

        return $this;
    }

    /**
     * @param int $length
     *
     * @return self
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @param int $overtime
     *
     * @return self
     */
    public function setOvertime(int $overtime): self
    {
        $this->overtime = $overtime;

        return $this;
    }

    /**
     * @param string $result
     *
     * @return self
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

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
     * @return array
     */
    public function getBodys(): ?array
    
    {
        return $this->bodys;
    }

    /**
     * @return int
     */
    public function getComplete(): ?int
    
    {
        return $this->complete;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): ?int
    
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getExecution(): ?int
    
    {
        return $this->execution;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getImplement(): ?int
    
    {
        return $this->implement;
    }

    /**
     * @return int
     */
    public function getLength(): ?int
    
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getOvertime(): ?int
    
    {
        return $this->overtime;
    }

    /**
     * @return string
     */
    public function getResult(): ?string
    
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTaskId(): ?string
    
    {
        return $this->taskId;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): ?int
    
    {
        return $this->updatedAt;
    }

}
