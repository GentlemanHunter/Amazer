<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\AlphaDash;
use Swoft\Validator\Annotation\Mapping\Email;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class UserValidator
 * @package App\Validator
 * @Validator(name="UserValidator")
 */
class UserValidator
{
    /**
     * @IsString()
     * @Required()
     * @NotEmpty(message="账号不能为空")
     * @Length(min=5,max=50,message="User account length does not match !")
     * @var string
     */
    protected $account = '';


    /**
     * @IsString()
     * @Required()
     * @NotEmpty(message="User nickname cannot be empty")
     * @Length(max=30,message="The maximum length of user nickname is 30")
     * @var string
     */
    protected $username = '';

    /**
     * @IsString()
     * @AlphaDash(message="必须是大小写字母、数字、短横 -、下划线 _")
     * @Required()
     * @NotEmpty(message="密码不能为空")
     * @Length(min=8,max=20,message="User password length does not match !")
     * @var string
     */
    protected $password = '';

    /**
     * @IsInt()
     * @Required()
     * @Enum(values={0,1})
     * @var int
     */
    protected $status = 0;

    /**
     * @IsString()
     * @Required()
     * @NotEmpty()
     * @var string
     */
    protected $code = '';
}
