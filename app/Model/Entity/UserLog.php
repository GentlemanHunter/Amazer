<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 用户操作日志表
 * Class UserLog
 *
 * @since 2.0
 *
 * @Entity(table="user_log")
 */
class UserLog extends Model
{
    /**
     * 主键
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * 用户ID
     *
     * @Column()
     *
     * @var int
     */
    private $uid;

    /**
     * 用户操作行为
     *
     * @Column()
     *
     * @var int
     */
    private $action;

    /**
     * 用户日志
     *
     * @Column()
     *
     * @var string
     */
    private $log;

    /**
     * 创建时间
     *
     * @Column(name="create_at", prop="createAt")
     *
     * @var int
     */
    private $createAt;

    /**
     * 操作ip地址
     *
     * @Column()
     *
     * @var string
     */
    private $visitor;


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
     * @param int $action
     *
     * @return self
     */
    public function setAction(int $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param string $log
     *
     * @return self
     */
    public function setLog(string $log): self
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @param int $createAt
     *
     * @return self
     */
    public function setCreateAt(int $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @param string $visitor
     *
     * @return self
     */
    public function setVisitor(string $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
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
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getAction(): ?int
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getLog(): ?string
    {
        return $this->log;
    }

    /**
     * @return int
     */
    public function getCreateAt(): ?int
    {
        return $this->createAt;
    }

    /**
     * @return string
     */
    public function getVisitor(): ?string
    {
        return $this->visitor;
    }

}
