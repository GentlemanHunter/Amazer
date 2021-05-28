<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Exception\ApiException;
use App\ExceptionCode\ApiCode;
use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Swoft\Log\Helper\CLog;
use Swoft\Log\Helper\Log;
use Throwable;

/**
 * Class ApiExceptionHandler
 *
 * @since 2.0
 *
 * @ExceptionHandler(ApiException::class)
 */
class ApiExceptionHandler extends AbstractHttpErrorHandler
{
    /**
     * @param ApiException $e
     * @param Response $response
     *
     * @return Response
     */
    public function handle($e, Response $response): Response
    {
        // Log error message
        Log::error($e->getMessage());
        CLog::error('%s. (At %s line %d)', $e->getMessage(), $e->getFile(), $e->getLine());

        // 这里code默认为-1 因为api成功返回的code为0
        $code = ($e->getCode() == 0) ? ApiCode::UNKNOWN : $e->getCode();
        $message = ApiCode::result($code);

        // Debug is true
        if (APP_DEBUG) {
            $message = sprintf('(%s) %s', get_class($e), $e->getMessage());
        }

        return apiError($code, $message);
    }
}
