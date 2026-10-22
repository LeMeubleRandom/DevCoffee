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

    public function getTodayCaffeineTotal(string $developerName): int
    {
        $sql = "SELECT COALESCE(SUM(d.caffeine_mg), 0) AS total
                FROM consumptions c
                JOIN drinks d ON c.drink_id = d.id
                WHERE c.developer_name = :developerName
                AND DATE(c.consumed_at) = CURRENT_DATE()";

        $req = $this->pdo->prepare($sql);
        $req->bindValue(':developerName', $developerName, PDO::PARAM_STR);
        $req->execute();

        return (int) $req->fetchColumn();
    }

    public function createConsumption(int $drinkId, string $developerName): array
    {
        $req = $this->pdo->prepare("INSERT INTO consumptions (drink_id, developer_name, consumed_at) VALUES (:drinkId, :developerName, NOW())");
        $req->bindValue(':drinkId', $drinkId, PDO::PARAM_INT);
        $req->bindValue(':developerName', $developerName, PDO::PARAM_STR);
        $req->execute();

        $id = (int) $this->pdo->lastInsertId();

        $drink = $this->getDrinkById($drinkId);

        return [
            'id' => $id,
            'developerName' => $developerName,
            'drinkName' => $drink->name,
            'caffeineMg' => $drink->caffeine_mg ?? $drink->caffeineMg,
            'consumedAt'  => date('Y-m-d H:i:s')
        ];
    }

    public function deleteConsumption(int $id): bool
    {
        $req = $this->pdo->prepare("DELETE FROM consumptions WHERE id = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();

        return $req->rowCount() > 0;
    }
}