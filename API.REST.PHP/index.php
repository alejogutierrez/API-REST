<?php

require __DIR__ . "/vendor/autoload.php";

use App\ClientController;
use App\ClientRepository;
use App\ClientService;
use App\SqliteConnection;
use App\Router;

//$db = MySQLConnection::getInstance();
$db = SqliteConnection::getInstance();
$repository = new ClientRepository($db);
$service = new ClientService($repository);
$controller = new ClientController($service);
$router = new Router();

$router->addRoute("GET", "/", function () use ($controller) {
    $controller->getClients();
});

$router->addRoute("POST", "/clients", function () use ($controller) {
    $controller->createClient();
});

$router->addRoute("PUT", "/clients", function () use ($controller) {
    $controller->createClient();
});

$router->addRoute("DELETE", "/clients/:id", function ($id) use ($controller) {
    $controller->deleteClient(intval($id));
});

$router->matchRoute();
