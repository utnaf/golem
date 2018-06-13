<?php

namespace App\Service;

final class DbService {

    /** @var \PDO */
    private $db;

    /** @var string */
    private $lastError = '';

    public function __construct() {
        $dbName     = getenv('DB_DATABASE');
        $dbHost     = getenv('DB_HOST');
        $dbUser     = getenv('DB_USERNAME');
        $dbPassword = getenv('DB_PASSWORD');

        try {
            $this->db = new \PDO("mysql:dbname=$dbName;host=$dbHost", $dbUser, $dbPassword);
        }
        catch (\Exception $e) {
            $this->lastError = $e->getMessage();
        }
    }

    public function isConnected(): bool {
        return $this->db instanceof \PDO;
    }

    public function getLastError(): string {
        return $this->lastError;
    }
}
