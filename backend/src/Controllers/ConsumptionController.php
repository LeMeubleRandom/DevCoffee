<?php
namespace App\Controllers;

use App\Services\CoffeeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
}