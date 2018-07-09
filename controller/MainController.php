<?php
$router->respond('GET', '/', function ($request) {
    AuthHelper::init();
    Helper::renderPage('/index.tpl.php');
});