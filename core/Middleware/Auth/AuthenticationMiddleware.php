<?php
namespace Core\Middleware\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var AuthenticatorInterface
     */
    protected $authenticator;

    /**
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(AuthenticatorInterface $authenticator, ResponseInterface $errorResponsePrototype)
    {
        $this->authenticator = $authenticator;
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // Return Error 401 "Unauthorized" if the provided API key doesn't match the needed one
        if ($request->getHeader("x-api-key") !== [$this->apiKey]) {
            return $this->errorResponsePrototype->withStatusCode(401);
        }
        
        // Invoke the remaining middleware if authentication was successful
        return $handler->handle($request);
    }
}
