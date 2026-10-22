<?php
namespace App\Services;

use App\Models\Drink;
use App\Models\Consumption;
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

    public function getAllConsumptions(): array{
      $req = $this->pdo->prepare("SELECT drink_id, developer_name, consumed_at FROM consumptions ORDER BY id DESC");

      $sessions = $req->fetchAll();

      return array_map(fn($s) => new Consumption(
        id: (int) $s->id,
        drinkId: (int) $s->drink_id,
        developerName: (string) $s->developer_name,
        consumedAt: (string) $s->consumed_at
      ), $sessions);
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

    public function getConsumptionById(int $id): ?Consumption
    {
      $req = $this->pdo->prepare("SELECT id, drink_id, developer_name, consumed_at FROM consumptions WHERE id = :id");
      $req->bindValue(":id", $id, PDO::PARAM_INT);
      $req->execute();

      $r = $req->fetch();
      if(!$r) return null;

      return new Consumption(
        (int) $r->id,
        (int) $r->drink_id,
        (string) $r->developer_name,
        (string) $r->consumed_at
      );
    }

    public function createDrink(
      string $name,
      int $caffeineMg,
      int $volumeMl
    ): Drink {
      $req = $this->pdo->prepare("INSERT INTO drinks(name, caffeine_mg, volume_ml) VALUES(:name, :caffeineMg, :volumeMl)");
      
      $req->bindValue(":name", $name, PDO::PARAM_STR);
      $req->bindValue(":caffeineMg", $caffeineMg, PDO::PARAM_INT);
      $req->bindValue(":volumeMl", $volumeMl, PDO::PARAM_INT);
      $req->execute();

      $id = (int) $this->pdo->lastInsertId();

      return new Drink(
        $id,
        $name,
        $caffeineMg,
        $volumeMl
      );
    }

    public function createConsumption(int $drinkId, string $developerName): Consumption
    {
        $req = $this->pdo->prepare("INSERT INTO consumptions (drink_id, developer_name, consumed_at) VALUES (:drinkId, :developerName, NOW())");
        $req->bindValue(':drinkId', $drinkId, PDO::PARAM_INT);
        $req->bindValue(':developerName', $developerName, PDO::PARAM_STR);
        $req->execute();

      $id = (int) $this->pdo->lastInsertId();

      return new Consumption(
        $id,
        $drinkId,
        $developerName,
        date("Y-m-d H:i:s")
      );
    }

    public function deleteConsumption(int $id): bool
    {
      $req = $this->pdo->prepare("DELETE FROM consumptions WHERE id = :id");
      $req->bindValue(':id', $id, PDO::PARAM_INT);
      $req->execute();

      return $req->rowCount() > 0;
    }

    public function getLast20Consumptions(): array
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
}