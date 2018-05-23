<?php



$klein->respond('GET', '/', function ($request) {
    
    require_once viewsDir().'/header.tpl.php';
    require_once viewsDir().'/index.tpl.php';
    require_once viewsDir().'/footer.tpl.php';
});