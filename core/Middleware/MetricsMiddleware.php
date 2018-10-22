<?php
namespace Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MetricsMiddleware implements MiddlewareInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        // Invoke the remaining middleware and cancel authentication if the current URL is for a public endpoint
        if (substr($request->getUri()->getPath(), 0, strlen($this->securedPath)) !== $this->securedPath) {
            return $handler->handle($request);
        }
        
        // Invoke the remaining middleware otherwise
        return $handler->handle($request);
    }

    private function getMemUsage()
    {
        convert(memory_get_usage());
    }
}
