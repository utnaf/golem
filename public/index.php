<?php
include '../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

echo (new \App\Controller\HomeController())->index();
