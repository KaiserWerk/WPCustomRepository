<?php
AuthHelper::init();
$router->respond('GET', '/', function ($request) {
    Helper::renderPage('/index.tpl.php');
});