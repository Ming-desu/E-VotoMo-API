<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set('Asia/Manila');

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'db_evotomo',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();