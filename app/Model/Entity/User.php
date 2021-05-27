<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * System User Table
 * Class User
 *
 * @since 2.0
 *
 * @Entity(table="user")
 */
class User extends Model
{
    /**
     * ID
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * User`s account
     *
     * @Column()
     *
     * @var string
     */
    private $account;

    /**
     * User`s email
     *
     * @Column()
     *
     * @var string
     */
    private $email;

    /**
     * User`s mobile
     *
     * @Column()
     *
     * @var string
     */
    private $mobile;

    /**
     * User`s nickname
     *
     * @Column()
     *
     * @var string
     */
    private $username;

    /**
     * User`s password
     *
     * @Column(hidden=true)
     *
     * @var string
     */
    private $password;

    /**
     * Last login ip
     *
     * @Column()
     *
     * @var string
     */
    private $visitor;

    /**
     * Creation time
     *
     * @Column(name="create_at", prop="createAt")
     *
     * @var int
     */
    private $createAt;

    /**
     * Update time
     *
     * @Column(name="update_at", prop="updateAt")
     *
     * @var int
     */
    private $updateAt;

    /**
     * Delete time, null is not deleted
     *
     * @Column(name="delete_at", prop="deleteAt")
     *
     * @var int|null
     */
    private $deleteAt;

    /**
     * Whether it is created by the system, 0 is not
     *
     * @Column(name="is_sys", prop="isSys")
     *
     * @var int
     */
    private $isSys;

    /**
     * user status
     *
     * @Column()
     *
     * @var int
     */
    private $status;


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
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $mobile
     *
     * @return self
     */
    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

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
     * @param int|null $deleteAt
     *
     * @return self
     */
    public function setDeleteAt(?int $deleteAt): self
    {
        $this->deleteAt = $deleteAt;

        return $this;
    }

    /**
     * @param int $isSys
     *
     * @return self
     */
    public function setIsSys(int $isSys): self
    {
        $this->isSys = $isSys;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
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
     * @return int|null
     */
    public function getDeleteAt(): ?int
    {
        return $this->deleteAt;
    }

    /**
     * @return int
     */
    public function getIsSys(): ?int
    {
        return $this->isSys;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

}
