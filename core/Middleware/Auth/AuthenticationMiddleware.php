<?php
namespace Core\Middleware\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Core\Database;
use Firebase\JWT\JWT;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseInterface
     */
    protected $errorResponsePrototype;

    public function __construct(ResponseInterface $errorResponsePrototype)
    {
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // Return Error 401 "Unauthorized" if the provided API key doesn't match the needed one
        $token = str_ireplace('bearer ', '', $request->getHeader('Authorization'));
        $db = new Database;
        $user = $db->row("SELECT id FROM users WHERE apikey = ?", $token[0]);
        if (empty($user)) {
            $this->errorResponsePrototype->getBody()->write(json_encode($user));
            return $this->errorResponsePrototype->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
        
        // Invoke the remaining middleware if authentication was successful
        return $handler->handle($request);
    }
}
