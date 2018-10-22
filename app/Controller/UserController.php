<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Service\UserService;

class UserController
{
    /**
     * @Inject
     * @var UserService
     */
    protected $service;

    public function show(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $data = [
            'data' => $this->service->handle('find', (integer)$request->getAttribute('id'))
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
