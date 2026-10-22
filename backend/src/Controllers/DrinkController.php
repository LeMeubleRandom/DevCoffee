<?php
namespace App\Controllers;

use App\Services\CoffeeService;
use App\Responses\ProblemDetailsResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use App\Core\UrlHelper;

class DrinkController
{

    public function __construct(
        private CoffeeService $coffeeService
    ) {}

    public function getDrink(Request $request, Response $response): Response
    {
        $drinks = $this->coffeeService->getAllDrinks();
        $baseUrl = UrlHelper::getBaseUrl($request);

        $data = array_map(fn($d) => [
            "id" => $d->id,
            "name" => $d->name,
            "caffeineMg" => $d->caffeineMg, 
            "volumeMl" => $d->volumeMl,   
            "_links" => [
                "self" => [
                    "href" => "{$baseUrl}/api/drinks/{$d->id}",
                    "method" => "GET"
                ]
            ]
        ], $drinks);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function newDrink(Request $request, Response $response): Response {
      $body = $request->getParsedBody() ?? json_decode($request->getBody()->getContents(), true);
      
      if(
        empty($body["name"]) 
        || empty($body["caffeineMg"])
        || empty($body["volumeMl"])
        ){
        return ProblemDetailsResponse::create(
          response: $response, 
          status: 400, 
          title: "Validation Error", 
          detail: "Les champs name, caffeineMg et volumeMl sont requis."
          );
      }

      $baseUrl = UrlHelper::getBaseUrl($request);
      $payload = [
        "id" => $session->id,
        "name" => $session->name,
        "caffeineMg" => $session->caffeineMg,
        "volumeMl" => $session->volumeMl,
        "_links" => [
          "drink" => [
            "href" => "{$baseUrl}/api/drinks/{$session->drinkId}",
            "method" => "GET"
            ]
        ]
      ];
    }
}