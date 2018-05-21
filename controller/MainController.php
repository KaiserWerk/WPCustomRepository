<?php

$klein->onHttpError(function ($code, $router) {
    switch ( $code ) {
        case 403:
            echo 'error 403';
            break;
        case 404:
            echo 'error 404';
            break;
        case 401:
            echo 'error 401';
            break;
        default:
            echo 'unknown error';
            break;
    }
});

$klein->respond('GET', '/', function ($request) {
    
    require_once viewsDir().'/header.tpl.php';
    require_once viewsDir().'/index.tpl.php';
    require_once viewsDir().'/footer.tpl.php';
});