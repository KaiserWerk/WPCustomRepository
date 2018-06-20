<?php

$router->onHttpError(function ($code, $router) {
    http_response_code($code);
    require viewsDir() . '/error/' . $code . '.tpl.php';
});