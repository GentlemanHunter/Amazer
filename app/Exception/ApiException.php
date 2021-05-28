<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception;

use App\ExceptionCode\ApiCode;
use phpDocumentor\Reflection\Types\This;
use Throwable;

/**
 * Class ApiException
 *
 * @since 2.0
 */
class ApiException extends \Exception implements Throwable
{
    /** @var array 消息体内容 */
    protected $params = [];

    /**
     * ApiException constructor.
     * @param int $code ApiCode 状态码
     * @param array $content i18n 内容体
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, array $content = [], Throwable $previous = null)
    {
        if (!empty($content)) {
            $this->params = $content;
        }
        parent::__construct('', $code, $previous);
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        if (empty($params)) {
            $this->params = $params;
        }
        return $this;
    }
}
