<?php



$router->respond('GET', '/', function ($request) {
    Helper::renderPage('/index.tpl.php');
});