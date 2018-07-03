<?php

require '../bootstrap.php';



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

$router->dispatch();