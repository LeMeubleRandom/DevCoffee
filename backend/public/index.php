<?php

use App\Controllers\DrinkController;
use App\Controllers\ConsumptionController;
use App\Core\Database;
use App\Middlewares\CorsMiddleware;
use App\Middlewares\EcoMetricsMiddleware;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require_once "../vendor/autoload.php";


$dotenv = Dotenv::createImmutable("../");
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([
  PDO::class => function(){
    return Database::getInstance();
  }
]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->add(new EcoMetricsMiddleware());
$app->add(new CorsMiddleware);

$app->options("/{routes:.+}", function ($request, $response){
  return $response;
});

// 404 routes
$app->

// Drinks routes
$app->get("/api/drinks", [DrinkController::class, "getDrink"]);
$app->post("/api/drinks", [DrinkController::class, "newDrink"]);

// Consumptions routes
$app->get('/api/consumptions', [ConsumptionController::class, "getConsumption"]);
$app->post('/api/consumptions', [ConsumptionController::class, "createConsumption"]);
$app->delete('/api/consumptions/{id}', [ConsumptionController::class . "delete"]);

// Sessions routes
$app->get("/api/sessions", [DrinkController::class, "getSessions"]);
$app->post("/api/sessions", [DrinkController::class, "storeSession"]);

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->run();