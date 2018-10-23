<?php declare (strict_types = 1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Service\UserService;
use App\Controller\AbstractController;

class UserController extends AbstractController
{

    public function show(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $data = [
            'data' => $this->db->row('SELECT * FROM users WHERE id = ?', (integer)$request->getAttribute('id'))
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function me(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $server = $request->getServerParams();
        $data = [
            'data' => $this->db->row("SELECT * FROM users WHERE id = ?", $server['AUTH_USER']['id']),
            'user' => $server['AUTH_USER']
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}
