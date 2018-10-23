<?php declare (strict_types = 1);
/**
 * php-psr - A fast PHP API framework using the Middleware Approach with PSR-Compliant Components
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
define('DATE_FORMAT_SHORT', 'Y-m-d h:i:s');
define('DATE_FORMAT_LONG', 'Y-m-d h:i:s A e');

include_once VENDOR_PATH . "autoload.php";

use Psr\Http\Message \{
    ResponseInterface,
        ServerRequestInterface
};
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware \{
    DispatcherMiddleware,
        FastRouteMiddleware,
        HttpHandlerRunnerMiddleware
};
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Core\Container\Container;
use App\Controller \{
    HomeController,
        UserController,
        AuthController
};
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Middlewares\Cors;
use Neomerx\Cors\Strategies\Settings;
use Neomerx\Cors\Analyzer;
use Tuupola\Middleware\JwtAuthentication;
use WoohooLabs\Harmony\Condition\PathPrefixCondition;
use Core\Middleware\Auth\AuthenticationMiddleware;

$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();
$response = (new Psr17Factory())->createResponse();

/* Initialize the router */
$router = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute("GET", '/', [HomeController::class, 'index']);
    $r->addRoute("POST", '/auth/login', [AuthController::class, 'login']);
    $r->addRoute("GET", '/users/{id}', [UserController::class, 'show']);
    $r->addRoute("GET", '/auth/me', [UserController::class, 'me']);
    $r->addRoute("GET", '/debug', [HomeController::class, 'debug']);
});

/* Initialize Cors */
$settings = new Settings();
// $settings->setServerOrigin([
//     // 'scheme' => 'http',
//     // 'host' => 'localhost',
//     // 'port' => 3000,
// ]);

$analyzer = Analyzer::instance($settings);

/* Load environment variables */
$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

/* Stack the middleware */
$harmony = new Harmony($serverRequest, $response);
$container = new Container();

$harmony
    ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware($container))
    ->addMiddleware(new Cors($analyzer))
    ->addMiddleware(new AuthenticationMiddleware((new Psr17Factory())->createResponse()));
    // ->addCondition(
    //     new PathPrefixCondition(['/users']),
    //     function (Harmony $harmony) use ($response) {
    //     }
    // );
/* Run! */
$harmony();
    