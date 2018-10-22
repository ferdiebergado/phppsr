<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $data = [
            'data' => 'Welcome'
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
