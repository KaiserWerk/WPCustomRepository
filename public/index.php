<?php

require '../bootstrap.php';

ini_set('log_errors', true);
ini_set('error_log', tempDir() . '/logs/php-errors.log');
ini_set('session.name', getenv('SESSNAME'));
if (getenv('DEBUG') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
} else {
    error_reporting(0);
    ini_set('display_errors', false);
}

// require helper
$helperPath = '../helper/';
$h = opendir($helperPath);
while($f = readdir($h)) {
    if ($f !== '.' && $f !== '..' && strpos($f, 'Helper.php') !== false) {
        require $helperPath . $f;
    }
}
closedir($h);


// require all controllers
$controllerPath = '../controller/';
$h = opendir($controllerPath);
while($f = readdir($h)) {
    if ($f !== '.' && $f !== '..' && strpos($f, 'Controller.php') !== false) {
        require $controllerPath . $f;
    }
}
closedir($h);

AuthHelper::init();

$router->dispatch();