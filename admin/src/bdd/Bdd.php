<?php
namespace bdd;

use PDO;
use PDOException;

class Bdd
{
    private PDO $bdd;

    public function __construct()
    {
        try {
            // remonte de src/bdd/ -> racine du projet
            $root = dirname(__DIR__, 2);
            $envFile = $root . '/.env.local';

            $env = file_exists($envFile)
                ? parse_ini_file($envFile, false, INI_SCANNER_TYPED)
                : [];

            $host = $env['DB_HOST'] ?? '127.0.0.1';
            $port = (int)($env['DB_PORT'] ?? 8889);
            $name = $env['DB_NAME'] ?? 'bdd_paristanbul';
            $user = $env['DB_USER'] ?? 'root';
            $pass = $env['DB_PASS'] ?? 'root';

            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);

            $this->bdd = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ]);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function getBdd(): PDO
    {
        return $this->bdd;
    }
}
