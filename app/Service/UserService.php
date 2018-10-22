<?php

namespace App\Service;

use App\Entity\User;

class UserService
{
    /**
     * @Inject
     * @var User
     */
    protected $user;

    public function handle(string $task, $param)
    {
        return $this->user->{$task}($param);
    }
}
