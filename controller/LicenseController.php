<?php

$klein->respond('GET', '/license/list', function ($request) {
    $db = new DBHelper();
    
    $licenses = $db->select('license', '*', [
        'ORDER' => [
            'created_at' => 'DESC',
        ]
    ]);
    
    require_once viewsDir().'/header.tpl.php';
    require_once viewsDir().'/license/list.tpl.php';
    require_once viewsDir().'/footer.tpl.php';
});

$klein->respond(['GET', 'POST'], '/license/add', function ($request) {
    
    require_once viewsDir().'/header.tpl.php';
    require_once viewsDir().'/license/add.tpl.php';
    require_once viewsDir().'/footer.tpl.php';
});

$klein->respond(['GET', 'POST'], '/license/renew', function ($request) {
    $db = new DBHelper();
    $id = $request->id;
    

});