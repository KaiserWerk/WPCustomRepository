<?php
$router->onHttpError(function ($code, $router) {
    AuthHelper::init();
    http_response_code($code);
    require viewsDir() . '/error/' . $code . '.tpl.php';
});