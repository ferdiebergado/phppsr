<?php declare (strict_types = 1);
/**
 * php-psr - A PHP API Application Skeleton using PSR-Compliant Packages
 *
 * @package  php-psr
 * @author   Ferdinand Saporas Bergado <ferdiebergado@gmail.com>
 * MIT License

 * Copyright (c) 2018 Ferdinand Saporas Bergado

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/* FRONT CONTROLLER */

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', __DIR__ . DS . '..' . DS);
define('CONFIG_PATH', BASE_PATH . 'config' . DS);
define('VENDOR_PATH', BASE_PATH . 'vendor' . DS);

include_once VENDOR_PATH . "autoload.php";

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Core\Container\Container;
use App\Controller\HomeController;
use App\Controller\UserController;

/* Initialize the request and the response objects */
$request = ServerRequestFactory::fromGlobals();
$response = new Response();

/* Initialize the router */
$router = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute("GET", '/', [HomeController::class, 'index']);
    $r->addRoute("GET", '/users/{id}', [UserController::class, 'show']);
});

/* Stack the middleware */
$harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());
$container = new Container();
$harmony
    ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware($container));

/* Run! */
$harmony();
