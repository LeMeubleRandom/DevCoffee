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

    public function index(Request $request, Response $response): Response
    {
        $drinks = $this->coffeeService->getAllDrinks();
        $baseUrl = UrlHelper::getBaseUrl($request);

        $data = array_map(fn($d) => [
            "id" => $d->id,
            "name" => $d->name,
            "caffeineMg" => $d->caffeine_mg, 
            "volumeMl" => $d->volume_ml,   
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

    

    public function getSessions(Request $request, Response $response): Response{
      $sessions = $this->coffeeService->getAllConsumptions();

      $baseUrl = UrlHelper::getBaseUrl($request);

      $data = array_map(fn($s) => [
          "id" => $s->id,
          "drinkId" => $s->drinkId,
          "developerName" => $s->developerName,
          "consumedAt" => $s->consumedAt,
          "_links" => [
            "self" => [
              "href" => "{$baseUrl}/api/sessions/{$s->id}",
              "method" => "GET"
              ],
            "add" => [
              "href" => "{$baseUrl}/api/sessions",
              "method" => "POST"
            ]
          ]
        ], $sessions);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES));

        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function storeSession(Request $request, Response $response): Response{

      $body = $request->getParsedBody() ?? json_decode($request->getBody()->getContents(), true);

      if(
        empty($body["drinkId"]) 
        || empty($body["userName"])
        || empty($body["kwhRequested"])
        ){
        return ProblemDetailsResponse::create(
          response: $response, 
          status: 400, 
          title: "Validation Error", 
          detail: "Les champs drinkId, userName et kwhRequested sont requis."
          );
      }

      $drink = $this->coffeeService->getDrinkById((int) $body["drinkId"]);

      if(!$drink){
        return ProblemDetailsResponse::create(
          response: $response,
          status:404,
          title: "Drink Not Found",
          detail: "La boisson demandée n'existe pas."
        );
      }
/////////////////////////////
      /* à modifier
      if((int) $body["kwhRequested"] > $drink->powerKw){
        return ProblemDetailsResponse::create(
          response: $response,
          status: 429,
          title: "Power Exceeded",
          detail: "La puissance demandée ({$body['kwhRequested']} kWh) dépasse la limite de la borne ({$station->powerKw} kW)."
        );
      }

      $session = $this->stationService->createChargeSession(
        stationId: (int) $body["stationId"],
        userName: $body["userName"],
        kwhRequested: (int) $body["kwhRequested"]
      );

      $baseUrl = UrlHelper::getBaseUrl($request);

      $payload = [
        "id" => $session->id,
        "stationId" => $session->stationId,
        "userName" => $session->userName,
        "kwhRequested" => $session->kwhRequested,
        "createdAt" => $session->createdAt,
        "_links" => [
          "station" => [
            "href" => "{$baseUrl}/api/stations/{$session->stationId}",
            "method" => "GET"
            ]
        ]
      ];
    */
/////////////////////////////////

      $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));

      return $response->withHeader("Content-Type", "application/json")->withStatus(201);

    }
}