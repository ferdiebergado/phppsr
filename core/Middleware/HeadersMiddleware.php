<?php
namespace Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HeadersMiddleware implements MiddlewareInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        
        
        // Invoke the remaining middleware otherwise
        return $handler->handle($request);
    }
}
