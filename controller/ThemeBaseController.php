<?php

$router->respond('GET', '/theme/base/list', function () {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
    $db = new DBHelper();
    
    $base_themes = $db->select('theme', '*', [
        'ORDER' => [
            'theme_name' => 'DESC',
        ]
    ]);
    
    require viewsDir() . '/header.tpl.php';
    require viewsDir() . '/theme/theme_list.tpl.php';
    require viewsDir() . '/footer.tpl.php';
});

$router->respond('GET', '/theme/base/add', function () {

});

$router->respond('GET', '/theme/base/edit', function () {

});

$router->respond('GET', '/theme/base/remove', function () {

});

