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
        \App\Entity\Entity::class => '_proxy__App__Entity__Entity',
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
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Entity/User.php';
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Service/UserService.php';
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Controller/UserController.php';

        self::$entryPoints[\App\Controller\UserController::class] = 'App__Controller__UserController';

        return $this->App__Controller__UserController();
    }

    public function _proxy__App__Entity__Entity()
    {
        include_once $this->rootDirectory . '/srv/http/php-psr/app/Entity/Entity.php';

        self::$entryPoints[\App\Entity\Entity::class] = 'App__Entity__Entity';

        return $this->App__Entity__Entity();
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
        $entry = new \App\Controller\UserController();
        $this->setProperties(
            $entry,
            [
                'service' => $this->singletonEntries['App\Service\UserService'] ?? $this->App__Service__UserService(),
            ]
        );
        return $this->singletonEntries['App\Controller\UserController'] = $entry;
    }

    public function App__Service__UserService()
    {
        $entry = new \App\Service\UserService();
        $this->setProperties(
            $entry,
            [
                'user' => $this->singletonEntries['App\Entity\User'] ?? $this->App__Entity__User(),
            ]
        );
        return $this->singletonEntries['App\Service\UserService'] = $entry;
    }

    public function App__Entity__User()
    {
        return $this->singletonEntries['App\Entity\User'] = new \App\Entity\User();
    }

    public function App__Entity__Entity()
    {
        return $this->singletonEntries['App\Entity\Entity'] = new \App\Entity\Entity();
    }
}
