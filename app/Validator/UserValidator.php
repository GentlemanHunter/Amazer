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
     * @IsString(message="2000")
     * @Required()
     * @NotEmpty(message="账号不能为空")
     * @Length(min=5,max=50,message="3012")
     * @var string
     */
    protected $account = '';


    /**
     * @IsString(message="2000")
     * @Required()
     * @NotEmpty(message="3013")
     * @Length(max=30,message="3014")
     * @var string
     */
    protected $username = '';

    /**
     * @IsString(message="2000")
     * @AlphaDash(message="3015")
     * @Required()
     * @NotEmpty(message="3016")
     * @Length(min=8,max=20,message="3011")
     * @var string
     */
    protected $password = '';

    /**
     * @IsInt(message="2002")
     * @Required()
     * @Enum(values={0,1})
     * @var int
     */
    protected $status = 0;

    /**
     * @IsString(message="2000")
     * @Required()
     * @NotEmpty(message="2001")
     * @var string
     */
    protected $code = '';
}
