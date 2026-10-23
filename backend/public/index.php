<?php

use App\Controllers\DrinkController;
use App\Controllers\ConsumptionController;
use App\Core\Database;
use App\Middlewares\CorsMiddleware;
use App\Middlewares\EcoMetricsMiddleware;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . "/../vendor/autoload.php";


$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([
  PDO::class => function(){
    return Database::getInstance();
  },
  LoggerInterface::class => function(){
    $logger = new Logger("devcoffee");
    $logger->pushHandler(new StreamHandler(__DIR__ . "/../src/var/logs/app.log", Logger::ERROR));

    return $logger;
  }
]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$preflight = function ($request, $response) {
  return $response;
};

// Drinks routes
$app->options("/api/drinks", $preflight);
$app->options("/api/drinks/{id}", $preflight);
$app->get("/api/drinks", [DrinkController::class, "getDrink"]);
$app->get("/api/drinks/{id}", [DrinkController::class, "getDrinkById"]);
$app->post("/api/drinks", [DrinkController::class, "newDrink"]);

// Consumptions routes
$app->options("/api/consumptions", $preflight);
$app->options("/api/consumptions/{id}", $preflight);
$app->get('/api/consumptions', [ConsumptionController::class, "getConsumption"]);
$app->post('/api/consumptions', [ConsumptionController::class, "createConsumption"]);
$app->delete('/api/consumptions/{id}', [ConsumptionController::class, "delete"]);

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(
    displayErrorDetails: false,
    logErrors: true,
    logErrorDetails: true
);

$errorMiddleware->setDefaultErrorHandler(
    new \App\Handlers\ProblemDetailsErrorHandler(
        $app->getResponseFactory(),
        $container->get(LoggerInterface::class)
    )
);

$app->add(new EcoMetricsMiddleware());
$app->add(new CorsMiddleware());

$app->run();