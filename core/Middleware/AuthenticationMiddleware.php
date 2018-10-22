<?php
namespace Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $securedPath;

    /**
     * @var MyAuthenticatorInterface
     */
    protected $authenticator;

    /**
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(string $securedPath, MyAuthenticatorInterface $authenticator, ResponseInterface $errorResponsePrototype)
    {
        $this->securedPath = $securedPath;
        $this->authenticator = $authenticator;
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        // Invoke the remaining middleware and cancel authentication if the current URL is for a public endpoint
        if (substr($request->getUri()->getPath(), 0, strlen($this->securedPath)) !== $this->securedPath) {
            return $handler->handle($request);
        }
    
        // Return Error 401 "Unauthorized" if authentication fails
        if ($this->authenticator->authenticate($request) === false) {
            return $this->errorResponsePrototype->withStatusCode(401);
        }
        
        // Invoke the remaining middleware otherwise
        return $handler->handle($request);
    }
}
