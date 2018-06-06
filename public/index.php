<?php
include "../vendor/autoload.php";

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$dbName = getenv("DB_DATABASE");
$dbHost = getenv("DB_HOST");
try {
    $db = new \PDO("mysql:dbname=$dbName;host=$dbHost", getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
} catch(\Exception $e) {
    echo "<strong>Error</strong>: " . $e->getMessage();
    die();
}

echo "Heyo! Seems everything is working here, also the db connection ;)";