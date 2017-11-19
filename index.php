<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set('error_reporting', E_ALL);

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Security\Random;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces(
    [
        'Models' => __DIR__ . '/app/models/',
        'Controllers' => __DIR__ . '/app/controllers/',
        'Validation' => __DIR__ . '/app/Validation/',
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '32167',
                'dbname' => 'phalcon',
            ]
        );
    }
);

// Create and bind the DI to the application
$app = new Micro($di);

const USER_APP_KEY = 'rNkJGSL1sg@Jbz@iFWV8|4fB5lP{n#Z%HGGQtQOb'; //b
const DRIVER_APP_KEY = 'rNkJGSL1sg@Jbz@iFWV8|4fB5lP{n#Z%HGGQtQOd'; //d


// Define the routes here

// Обработчик Users
$users = new MicroCollection();
$users->setHandler('Controllers\UsersController', true);
$users->post('/register', 'register');
$users->post('/auth', 'auth');
$app->mount($users);

// Обработчик Orders
$orders = new MicroCollection();
$orders->setHandler('Controllers\OrdersController', true);
$orders->post('/addOrder', 'addOrder');
$app->mount($orders);



$app->notFound(
    function () use ($app) {
        $app->response->sendHeaders();

        $app->response->setJsonContent(['Error' => 'Method\' is not defined']);
        return $app->response;
    }
);

$app->handle();

