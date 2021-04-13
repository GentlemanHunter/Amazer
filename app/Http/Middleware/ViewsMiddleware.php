<?php


namespace App\Http\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ViewsMiddleware
 * @Bean()
 * @package App\Http\Middleware
 */
class ViewsMiddleware implements MiddlewareInterface
{
    /**
     * @throws \App\Exception\ApiException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = context()->getResponse();

        if (!$userId = checkAuth()) {
            // 没有登录 验证
            return $response->redirect('/views/login');
        }

        return $handler->handle($request);
    }
}
