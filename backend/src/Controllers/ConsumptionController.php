<?php
namespace App\Controllers;

use App\Services\CoffeeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Responses\ProblemDetailsResponse;
use App\Core\UrlHelper;

class ConsumptionController
{
    public function __construct(
        private CoffeeService $coffeeService
    ) {}

    public function getConsumption(Request $request, Response $response): Response
    {
        $consumptions = $this->coffeeService->getLastConsumptions();
        $baseUrl = UrlHelper::getBaseUrl($request);

        $data = array_map(function ($c) use ($baseUrl) {
            return [
                "id" => (int) $c['id'],
                "developerName" => $c['developerName'],
                "drinkName" => $c['drinkName'],
                "caffeineMg" => (int) $c['caffeineMg'],
                "consumedAt" => $c['consumedAt'],
                "_links" => [
                    "delete" => [
                        "href" => "{$baseUrl}/api/consumptions/{$c['id']}",
                        "method" => "DELETE"
                    ]
                ]
            ];
        }, $consumptions);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withStatus(200);
    }

    public function stor(Request $request, Response $response) : Response{
        $body = $request->getParsedBody() ?? json_decode($request->getBody()->getContents(), true);

        if (empty($body['drinkId']) || empty($body['developerName'])){
            return ProblemDetailsResponse::create(
                response: $response,
                status: 400,
                title: "Validation Error",
                detail: "Les champs 'drinkId' et 'developerName sont requis."
            );
        }

        $drinkId = (int) $body['drinkId'];
        $developerName = (string) $body['developerName'];

        $drink = $this->coffeeService->getDrinkById($drinkId);
        if (!$drink){
            return ProblemDetailsResponse::create(
                response: $response,
                status: 404,
                title: "Drink Not found",
                detail: "La boisson demandée n'existe pas."
            );
        }

        $caffeineToday = $this->coffeeService->getTodayCaffeineTotal($developerName);
        $drinkCaffeine = $drink->caffeine_mg ?? $drink->caffeineMg;

        if(($caffeineToday + $drinkCaffeine) > 400){
            return ProblemDetailsResponse::create(
                response: $response,
                status: 429,
                title: "Too many requests",
                detail: "Limite de caféine dépaséee, le total actuel est de : {$caffeineToday}mg, ajout : {$drinkCaffeine}mg (max : 400mg)."
            );
        }

        $consumption = $this->coffeeService->createConsumption($drinkId, $developerName);
        $baseUrl = UrlHelper::getBaseUrl($request);

        $payload = [
            "id" => $consumption['id'],
            "developerName" => $consumption['developerName'],
            "drinkName" => $consumption['drinkName'],
            "caffeineMg" => $consumption['caffeineMg'],
            "consumedAt" => $consumption['consumedAt'],
            "_links" => [
                "delete" => [
                    "href" => "{$baseUrl}/api/consumptions/{$consumption['id']}",
                    "method" => "DELETE"
                ]
            ]
        ];

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $response->withHeader("Content-Type", "application/json")->withStatus(201);    
    }

    public function delete(Request $request, Response $response, array $args): Response{
        $id = (int) $args['id'];

        $deleted = $this->coffeeService->deleteConsumption($id);

        if (!$deleted) {
            return ProblemDetailsResponse::create(
                response: $response,
                status: 404,
                title: "Consumption Not Found",
                detail: "Aucune consommation trouvée avec l'identifiant {$id}."
            );
        }

        return $response->withStatus(204);
    }
}