<?php

require __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

// .env Configuration
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
