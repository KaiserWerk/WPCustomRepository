<?php

$klein->respond('GET', '/plugin/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    
    $rows = $db->select('plugin', '*', [
        'archived' => 0,
        'ORDER' => [
            'slug' => 'ASC',
            'version' => 'DESC',
        ]
    ]);
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/plugin/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

$klein->respond('GET', '/plugin/[:id]/show', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    
    $id = $request->id ?? null;
    
    if ($id !== null) {
        $db = new DBHelper();
    
        $plugin = $db->get('plugin', '*', [
            'id' => $id,
        ]);
    
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/show.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        Helper::redirect('/plugin/list?e=none_selected');
    }
});

$klein->respond(['GET', 'POST'], '/plugin/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    
    if (!isset($_POST['btn_plugin_add'])) {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/add.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        if (!AuthHelper::checkCSRFToken()) {
            Helper::redirect('/plugin/add?e=unknown_error');
        }
        $_plugin_add = $_POST['_plugin_add'];
        if (
            !empty($_plugin_add['plugin_name']) &&
            !empty($_plugin_add['slug']) &&
            !empty($_plugin_add['version']) &&
            !empty($_plugin_add['section_description'])
        ) {
            $file_name = $_plugin_add['slug'] . '_v' . $_plugin_add['version'] . '.zip';
            $dir = downloadDir() . '/' . $_plugin_add['slug'] . '/';
    
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
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
                'section_description' => strip_tags($_plugin_add['section_description'], '<b><i><p><strong><ul><ol><li><em><a><img>'),
                'last_updated' => date('Y-m-d H:i:s'),
                'added' => date('Y-m-d H:i:s'),
            ]);
        } else {
            die('Missing fields');
        }
        Helper::redirect('/plugin/list');
    }
});

$klein->respond(['GET', 'POST'], '/plugin/[:id]/edit', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    $id = (int)$request->id;
    $db = new DBHelper();
    if (isset($_POST['btn_plugin_edit'])) {
        if (!AuthHelper::checkCSRFToken()) {
            Helper::redirect('/plugin/add?e=unknown_error');
        }
        $_plugin_edit = $_POST['_plugin_edit'];
        
        $fields = [
            'plugin_name' => '',
            'slug' => '',
            'version' => '',
            'requires' => '',
            'tested' => '',
            'homepage' => '',
            'section_description' => '',
        ];
    
        foreach ($_plugin_edit as $key => $value) {
            if (!empty($_plugin_edit[$key])) {
                $fields[$key] = $_plugin_edit[$key];
            } else {
                unset($fields[$key]);
            }
        }
        
        $db->update('plugin', $fields, [
            'id' => $id,
        ]);
        
        Helper::redirect('/plugin/list?e=edit_success');
    } else {
        $plugin = $db->get('plugin', '*', [
            'id' => $id,
        ]);
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/edit.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});

$klein->respond('GET', '/plugin/[:id]/archive', function ($request) {
    $id = $request->id;
    $do = $_GET['do'] ?? null;
    $db = new DBHelper();
    
    $plugin = $db->get('plugin', [
        'id',
        'plugin_name',
        'slug',
        'version',
    ], [
        'id' => $id,
    ]);
    if ($do !== null) {
        $file_name = $plugin['slug'] . '_v' . $plugin['version'] . '.zip';
        $dir = downloadDir() . '/' . $plugin['slug'] . '/';
        if (file_exists($dir . $file_name)) {
            $newdir = archiveDir() . '/' . $plugin['slug'] . '/';
            if (!is_dir($newdir)) {
                @mkdir($newdir, 0775, true);
            }
            @rename($dir . $file_name, $newdir . $file_name);
        }
        
        $db->update('plugin', [
            'archived' => 1,
        ], [
            'id' => $id,
        ]);
        
        Helper::redirect('/plugin/list');
    } else {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/archive.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});

/*
$klein->respond('GET', '/plugin/[:id]/remove', function ($request) {

});*/
