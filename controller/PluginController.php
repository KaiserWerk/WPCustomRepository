<?php

$klein->respond('GET', '/plugin/list', function ($request) {
    
    $db = new DBHelper();
    
    $rows = $db->select('plugin', '*', [
        'ORDER' => [
            'slug' => 'ASC',
            'version' => 'DESC',
        ]
    ]);
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/plugin/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

$klein->respond(['GET', 'POST'], '/plugin/add', function ($request) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/add.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_plugin_add = $_POST['_plugin_add'];
        
        #echo "<pre>";var_dump($_plugin_add);die;
        
        if (
            !empty($_plugin_add['plugin_name']) &&
            !empty($_plugin_add['slug']) &&
            !empty($_plugin_add['version']) &&
            !empty($_plugin_add['section_description'])
        ) {
            $file_name = $_plugin_add['slug'] . '_v' . $_plugin_add['version'] . '.zip';
            $dir = downloadDir() . '/' . $_plugin_add['slug'] . '/';
    
            if (!is_dir($dir)) {
                @mkdir($dir, 0775);
            }
            
            move_uploaded_file($_FILES['_plugin_add_plugin_file']['tmp_name'], $dir . $file_name);
            
            $db = new DBHelper();
            $db->insert('plugin', [
                'plugin_name' => $_plugin_add['plugin_name'],
                'slug' => $_plugin_add['slug'],
                'version' => $_plugin_add['version'],
                'requires' => $_plugin_add['requires'],
                'tested' => $_plugin_add['tested'],
                'homepage' => $_plugin_add['homepage'],
                'section_description' => $_plugin_add['section_description'],
                'last_updated' => date('Y-m-d H:i:s'),
                'added' => date('Y-m-d H:i:s'),
            ]);
        } else {
            die("Missing fields");
        }
        Helper::redirect('/plugin/list');
    }
});

// wirklich änderbar? oder für änderung neue version?
/*$klein->respond(['GET', 'POST'], '/plugin/[:id]/edit', function ($request) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    }
});*/

/*
$klein->respond('GET', '/plugin/[:id]/remove', function ($request) {

});*/
