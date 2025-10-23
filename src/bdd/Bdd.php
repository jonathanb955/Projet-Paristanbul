<?php
namespace bdd;

use PDO;
use Throwable;

class Bdd
{
    private PDO $bdd;

    public function __construct()
    {
        // Essaie plusieurs configs courantes (MAMP, WAMP/XAMPP)
        $tries = [
            // MAMP (macOS) par défaut
            ['host' => '127.0.0.1', 'port' => 8889, 'user' => 'root', 'pass' => 'root'],
            // WAMP/XAMPP courants
            ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => ''],
        ];

        $dbName = 'bdd_paristanbul';
        $lastErr = null;

        foreach ($tries as $t) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                    $t['host'],
                    $t['port'],
                    $dbName
                );

                $this->bdd = new PDO(
                    $dsn,
                    $t['user'],
                    $t['pass'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
                // Succès → on sort du constructeur
                return;
            } catch (Throwable $e) {
                $lastErr = $e;
            }
        }

        // Si on arrive ici, aucun essai n'a fonctionné
        throw new \RuntimeException(
            "Connexion MySQL impossible (testé ports 8889/3306 avec mdp root/vide). ".
            ($lastErr ? "Dernière erreur: ".$lastErr->getMessage() : "")
        );
    }

    public function getBdd(): PDO
    {
        return $this->bdd;
    }
}