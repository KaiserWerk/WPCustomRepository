<?php

declare(strict_types=1);

require '../bootstrap.php';

// require helper
$h = opendir(HELPERDIR);
while($f = readdir($h)) {
    if ($f !== '.' && $f !== '..' && strpos($f, 'Helper.php') !== false) {
        require HELPERDIR . '/' . $f;
    }
}
closedir($h);

// require all controllers
$h = opendir(CONTROLLERDIR);
while($f = readdir($h)) {
    if ($f !== '.' && $f !== '..' && strpos($f, 'Controller.php') !== false) {
        require CONTROLLERDIR . '/' . $f;
    }
}
closedir($h);

$router = new KRouter();
#echo '<pre>';var_dump($router->getRoutes());die;
$router->dispatch();