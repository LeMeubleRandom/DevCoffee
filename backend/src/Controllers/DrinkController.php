<?php
namespace App\Controllers;

use App\Services\CoffeeService;
use App\Models\Drink;
use App\Responses\ProblemDetailsResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

        $data = array_map(
            fn($drink) => $this->serializeDrink($drink, $baseUrl),
            $drinks
        );

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function getDrinkById(Request $request, Response $response, array $args): Response
    {
        $drink = $this->coffeeService->getDrinkById((int) $args["id"]);

        if (!$drink) {
            return ProblemDetailsResponse::create(
                response: $response,
                status: 404,
                title: "Drink Not Found",
                detail: "Aucune boisson trouvée avec l'identifiant {$args["id"]}."
            );
        }

        $baseUrl = UrlHelper::getBaseUrl($request);
        $response->getBody()->write(json_encode(
            $this->serializeDrink($drink, $baseUrl),
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        ));

        return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function newDrink(Request $request, Response $response): Response {
      $body = $request->getParsedBody()
        ?? json_decode($request->getBody()->getContents(), true);

      if (!is_array($body)) {
        return ProblemDetailsResponse::create(
          response: $response, 
          status: 400, 
          title: "Validation Error", 
          detail: "Le corps de la requête doit être un objet JSON contenant name, caffeineMg et volumeMl."
          );
      }

      $name = $body["name"] ?? null;
      $caffeineValue = $body["caffeineMg"] ?? null;
      $volumeValue = $body["volumeMl"] ?? null;
      $caffeineMg = is_int($caffeineValue) || is_string($caffeineValue)
        ? filter_var($caffeineValue, FILTER_VALIDATE_INT)
        : false;
      $volumeMl = is_int($volumeValue) || is_string($volumeValue)
        ? filter_var($volumeValue, FILTER_VALIDATE_INT)
        : false;

      if (
        !is_string($name)
        || trim($name) === ""
        || preg_match('/^.{1,100}$/us', trim($name)) !== 1
        || $caffeineMg === false
        || $caffeineMg < 0
        || $volumeMl === false
        || $volumeMl <= 0
      ) {
        return ProblemDetailsResponse::create(
          response: $response,
          status: 400,
          title: "Validation Error",
          detail: "name doit contenir entre 1 et 100 caractères, caffeineMg être un entier positif ou nul, et volumeMl un entier strictement positif."
        );
      }

      $drink = $this->coffeeService->createDrink(
        name: trim($name),
        caffeineMg: $caffeineMg,
        volumeMl: $volumeMl
      );

      $baseUrl = UrlHelper::getBaseUrl($request);
      $response->getBody()->write(json_encode(
        $this->serializeDrink($drink, $baseUrl),
        JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
      ));

      return $response->withHeader("Content-Type", "application/json")->withStatus(201);
    }

    private function serializeDrink(Drink $drink, string $baseUrl): array
    {
        return [
            "id" => $drink->id,
            "name" => $drink->name,
            "caffeineMg" => $drink->caffeineMg,
            "volumeMl" => $drink->volumeMl,
            "_links" => [
                "self" => [
                    "href" => "{$baseUrl}/api/drinks/{$drink->id}",
                    "method" => "GET"
                ]
            ]
        ];
    }
}