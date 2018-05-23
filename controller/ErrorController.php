<?php

$klein->onHttpError(function ($code, $router) {
    require_once viewsDir().'/error/' . $code . '.tpl.php';
});