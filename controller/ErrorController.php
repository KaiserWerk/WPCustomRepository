<?php

$router->onHttpError(function ($code, $router) {
    http_response_code($code);
    require_once viewsDir().'/error/' . $code . '.tpl.php';
});