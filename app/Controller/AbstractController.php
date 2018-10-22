<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Service\AuthenticationService;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @Inject
     * @var AuthenticationService
     */
    protected $authenticationService;
}
