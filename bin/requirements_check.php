<?php

if (version_compare(PHP_VERSION, '7.0.0') < 0) {
    die('PHP 7 is required; your version is ' . PHP_VERSION);
}

$extensions = get_loaded_extensions();

$requiredExtensions = [
    'json',
    'zip',
    'xml',
    'curl',
    'PDO',
    'pdo_mysql',
    'session',
];

foreach ($requiredExtensions as $key => $value) {
    if (!extension_loaded($value)) {
        die('Required extension ' . $value . ' is not available!');
    }
}