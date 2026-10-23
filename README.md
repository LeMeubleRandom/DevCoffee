# DevCoffee

DevCoffee est une application web de suivi des consommations de caféine des développeurs. Elle permet d'enregistrer une boisson consommée, de consulter les 20 dernières consommations et de les supprimer. L'application limite les consommations à **400 mg de caféine par jour et par développeur**.

L'interface affiche également, pour la dernière requête API, le temps de réponse, la taille de la réponse et une estimation des émissions de CO₂.

## Technologies

- **Frontend :** Vue 3, TypeScript, Vite et Pinia
- **Backend :** PHP, Slim 4 et PDO
- **Base de données :** MySQL

## Prérequis

- PHP avec l'extension `pdo_mysql`
- [Composer](https://getcomposer.org/)
- MySQL
- [Bun](https://bun.sh/)

## Installation

Depuis la racine du dépôt :

1. Installez les dépendances du backend :

   ```powershell
   cd backend
   composer install
   Copy-Item .env.example .env
   cd ..
   ```

2. Renseignez dans `backend/.env` les identifiants de connexion MySQL. La base attendue par défaut dans le script de schéma est `devcoffee` :

   ```dotenv
   DB_HOST=127.0.0.1
   DB_NAME=devcoffee
   DB_USERNAME=votre_utilisateur
   DB_PASSWORD=votre_mot_de_passe
   ```

3. Créez les tables et les boissons initiales avec le script `backend/schema.sql`. Par exemple, depuis une invite de commandes Windows :

   ```cmd
   mysql -u root -p < backend\schema.sql
   ```

4. Installez les dépendances du frontend :

   ```powershell
   cd frontend
   bun install
   ```

## Lancement

Une fois l'installation terminée, depuis la racine du dépôt, lancez :

```cmd
launchServers.bat
```

Le script ouvre deux fenêtres de terminal :

- le backend PHP à l'adresse `http://127.0.0.1:5500`
- le frontend Vite à l'adresse `http://localhost:5173`

Ouvrez ensuite `http://localhost:5173` dans votre navigateur.

Vous pouvez aussi démarrer les serveurs séparément :

```powershell
# Terminal 1
cd backend
php -S 127.0.0.1:5500 -t public/
```

```powershell
# Terminal 2
cd frontend
bun dev
```

## API

L'API est préfixée par `/api` et écoute sur `http://127.0.0.1:5500`.

| Méthode | Route | Description |
| --- | --- | --- |
| `GET` | `/api/drinks` | Liste les boissons disponibles |
| `GET` | `/api/drinks/{id}` | Récupère une boisson |
| `POST` | `/api/drinks` | Ajoute une boisson (`name`, `caffeineMg`, `volumeMl`) |
| `GET` | `/api/consumptions` | Liste les 20 dernières consommations |
| `POST` | `/api/consumptions` | Enregistre une consommation (`drinkId`, `developerName`) |
| `DELETE` | `/api/consumptions/{id}` | Supprime une consommation |

Une consommation qui ferait dépasser la limite quotidienne de 400 mg est refusée. Les réponses de l'API incluent aussi des en-têtes de métriques (`X-Response-Time-Ms`, `X-Payload-Size-Bytes` et `X-Estimated-Co2-Grams`).

## Build du frontend

Depuis le dossier `frontend` :

```powershell
bun run build
```
