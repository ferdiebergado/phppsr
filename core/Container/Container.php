<?php
namespace Core\Container;

use WoohooLabs\Zen\AbstractCompiledContainer;

class Container extends AbstractCompiledContainer
{
    /**
     * @var string[]
     */
    protected static $entryPoints = [
        \Core\Container\Container::class => 'Core__Container__Container',
        \Psr\Container\ContainerInterface::class => 'Psr__Container__ContainerInterface',
        \App\Controller\HomeController::class => '_proxy__App__Controller__HomeController',
        \App\Controller\UserController::class => '_proxy__App__Controller__UserController',
        \App\Controller\AuthController::class => '_proxy__App__Controller__AuthController',
    ];

    /**
     * @var string
     */
    protected $rootDirectory;

    public function __construct(string $rootDirectory = '')
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function _proxy__App__Controller__HomeController()
    {
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Controller/HomeController.php';

        self::$entryPoints[\App\Controller\HomeController::class] = 'App__Controller__HomeController';

        return $this->App__Controller__HomeController();
    }

    public function _proxy__App__Controller__UserController()
    {
        include_once $this->rootDirectory . '/srv/http/php-psr/core/Database.php';
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Controller/UserController.php';

        self::$entryPoints[\App\Controller\UserController::class] = 'App__Controller__UserController';

        return $this->App__Controller__UserController();
    }

    public function _proxy__App__Controller__AuthController()
    {
        include_once $this->rootDirectory . '/srv/http/php-psr/core/Database.php';
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Controller/AuthController.php';

        self::$entryPoints[\App\Controller\AuthController::class] = 'App__Controller__AuthController';

        return $this->App__Controller__AuthController();
    }

    public function Core__Container__Container()
    {
        return $this;
    }

    public function Psr__Container__ContainerInterface()
    {
        return $this->singletonEntries['Psr\Container\ContainerInterface'] = $this->Core__Container__Container();
    }

    public function App__Controller__HomeController()
    {
        return $this->singletonEntries['App\Controller\HomeController'] = new \App\Controller\HomeController();
    }

    public function App__Controller__UserController()
    {
        return $this->singletonEntries['App\Controller\UserController'] = new \App\Controller\UserController(
            $this->singletonEntries['Core\Database'] ?? $this->Core__Database()
        );
    }

    public function Core__Database()
    {
        return $this->singletonEntries['Core\Database'] = new \Core\Database();
    }

    public function App__Controller__AuthController()
    {
        return $this->singletonEntries['App\Controller\AuthController'] = new \App\Controller\AuthController(
            $this->singletonEntries['Core\Database'] ?? $this->Core__Database()
        );
    }
}
