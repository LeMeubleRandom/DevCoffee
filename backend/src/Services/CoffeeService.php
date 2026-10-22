<?php
namespace App\Services;

use App\Models\Drink;
use App\Models\ChargeSession;
use PDO;

class CoffeeService
{

    public function __construct(
        private PDO $pdo
    ) {}

    public function getAllDrinks(): array
    {
        $req = $this->pdo->query("SELECT id, name, caffeine_mg, volume_ml FROM drinks");

        $rows = $req->fetchAll();

        return array_map(fn($r) => new Drink(
            (int) $r->id,
            $r->name,
            (int) $r->caffeine_mg,
            (int) $r->volume_ml
        ), $rows);
    }

    public function getDrinkById(int $id): ?Drink
    {
      $req = $this->pdo->prepare("SELECT id, name, caffeine_mg, volume_ml FROM drinks WHERE id = :id");
      $req->bindValue(":id", $id, PDO::PARAM_INT);
      $req->execute();

      $r = $req->fetch();
      if(!$r) return null;

      return new Drink(
        (int) $r->id,
        $r->name,
        (int) $r->caffeine_mg,
        (int) $r->volume_ml
      );
    }

    public function getLastConsumptions(): array
    {
        $sql = "SELECT  
                    c.id, 
                    c.developer_name AS developerName, 
                    d.name AS drinkName, 
                    d.caffeine_mg AS caffeineMg, 
                    c.consumed_at AS consumedAt
                FROM consumptions c
                JOIN drinks d ON c.drink_id = d.id
                ORDER BY c.consumed_at DESC
                LIMIT 20";

        $req = $this->pdo->query($sql);

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createConsumption(
      int $drinkId,
      string $developerName
    ): Consumption {
      $req = $this->pdo->prepare("INSERT INTO consumptions(drink_id, developer_name) VALUES(:drinkId, :developerName)");
      
      $req->bindValue(":drinkId", $drinkId, PDO::PARAM_INT);
      $req->bindValue(":developerName", $developerName, PDO::PARAM_STR);
      $req->execute();

      $id = (int) $this->pdo->lastInsertId();

      $updateReq = $this->pdo->prepare("UPDATE drinks SET is_available = 0 WHERE id=:id");
      $updateReq->bindValue(":id", $drinksId, PDO::PARAM_INT);
      $updateReq->execute();

      return new Consumption(
        $id,
        $drinksId,
        $developerName,
        date("Y-m-d H:i:s")
      );

    }

}