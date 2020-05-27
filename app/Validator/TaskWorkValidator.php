<?php


namespace App\Validator;

use Swoft\Validator\Annotation\Mapping\AfterDate;
use Swoft\Validator\Annotation\Mapping\AlphaNum;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Min;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Range;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\Validator;

/**
 * Class TaskWorkValidator
 * @package App\Validator
 * @Validator(name="TaskWorkValidator")
 */
class TaskWorkValidator
{
    /**
     * @IsString()
     * @Required()
     * @NotEmpty(message="任务名称不能为空")
     * @Length(min=2,max=15,message="长度限制为 2-15")
     * @var string
     */
    protected $names = '';

    /**
     * @IsString()
     * @Length(min=5,max=50,message="长度限制为 5-50")
     * @var string
     */
    protected $describe = '此任务没有描述';

    /**
     * @IsString()
     * @Required()
     * @NotEmpty(message="执行时间不能为空")
     * @AfterDate(message="执行时间必须是时间格式")
     * @var string
     */
    protected $execution = '';

    /**
     * @IsInt()
     * @NotEmpty(message="重试次数不能为空")
     * @Range(min=1,max=10,message="重试次数范围错误 1-10")
     * @var int
     */
    protected $retry = 1;

    /**
     * @IsString()
     * @Required()
     * @NotEmpty(message="请求体不能为空")
     * @var string
     */
    protected $bodys = '';
}
