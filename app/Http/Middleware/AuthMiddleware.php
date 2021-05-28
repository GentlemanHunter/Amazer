<?php


namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use App\ExceptionCode\ApiCode;
use App\Exception\ApiException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 用户基本 token 认证体系
 * Class AuthMiddleware
 * @Bean()
 * @package App\Http\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ApiException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeaderLine('Authorization');

        $prefix = 'Bearer ';

        if (empty($authorization)) {
            $params = array_merge($request->getParsedBody(), $request->getQueryParams());
            $authorization = $prefix . ($params['token'] ?? '');
        }

        $publicKey = config('jwt.public_key');

        if (empty($publicKey)) {
            throw new ApiException(ApiCode::JWT_PUBLIC_KEY_EMPTY);
        }

        if (empty($authorization) || !is_string($authorization) || strpos($authorization, $prefix) !== 0) {
            throw new ApiException(ApiCode::AUTH_ERROR);
        }

        $jwt = substr($authorization, strlen($prefix));

        if (strlen(trim($jwt)) <= 0) {
            throw new ApiException(ApiCode::AUTH_ERROR);
        }

        $payload = JWT::decode($jwt, $publicKey, [config('jwt.alg')]);


        if (isset($payload->user) && !is_numeric($payload->user)) {
            throw new ApiException(ApiCode::AUTH_ERROR);
        }

        $request->user = $payload->user;

        $userInfo = bean('App\Model\Dao\UserDao')->findUserInfoById($request->user);

        if (empty($userInfo)) {
            throw new ApiException(ApiCode::AUTH_ERROR);
        }

        $request->userInfo = $userInfo;

        return $handler->handle($request);
    }
}
