<?php

namespace App\Service;

final class DbService {

    /** @var \PDO */
    private $db;

    public function __construct() {
        $dbName     = getenv('DB_DATABASE');
        $dbHost     = getenv('DB_HOST');
        $dbUser     = getenv('DB_USERNAME');
        $dbPassword = getenv('DB_PASSWORD');

        try {
            $this->db = new \PDO("mysql:dbname=$dbName;host=$dbHost", $dbUser, $dbPassword);
        }
        catch (\Exception $e) {
        }
    }

    public function isConnected(): bool {
        return $this->db instanceof \PDO;
    }
}
