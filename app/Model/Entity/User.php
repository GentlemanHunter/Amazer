<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 系统用户表
 * Class User
 *
 * @since 2.0
 *
 * @Entity(table="user")
 */
class User extends Model
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
     * 用户登录帐号
     *
     * @Column()
     *
     * @var string
     */
    private $account;

    /**
     * 用户昵称
     *
     * @Column()
     *
     * @var string
     */
    private $username;

    /**
     * 用户密码
     *
     * @Column(hidden=true)
     *
     * @var string
     */
    private $password;

    /**
     * 用户上次登录ip地址
     *
     * @Column()
     *
     * @var string
     */
    private $visitor;

    /**
     * 创建时间
     *
     * @Column(name="create_at", prop="createAt")
     *
     * @var int
     */
    private $createAt;

    /**
     * 更新时间
     *
     * @Column(name="update_at", prop="updateAt")
     *
     * @var int
     */
    private $updateAt;

    /**
     * 删除时间 为NULL未删除
     *
     * @Column(name="delete_at", prop="deleteAt")
     *
     * @var int
     */
    private $deleteAt;


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
     * @param string $account
     *
     * @return self
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

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
     * @param int $updateAt
     *
     * @return self
     */
    public function setUpdateAt(int $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @param int $deleteAt
     *
     * @return self
     */
    public function setDeleteAt(int $deleteAt): self
    {
        $this->deleteAt = $deleteAt;

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
     * @return string
     */
    public function getAccount(): ?string

    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string

    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string

    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getVisitor(): ?string

    {
        return $this->visitor;
    }

    /**
     * @return int
     */
    public function getCreateAt(): ?int

    {
        return $this->createAt;
    }

    /**
     * @return int
     */
    public function getUpdateAt(): ?int

    {
        return $this->updateAt;
    }

    /**
     * @return int
     */
    public function getDeleteAt(): ?int

    {
        return $this->deleteAt;
    }


}
