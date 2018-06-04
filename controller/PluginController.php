<?php

$klein->respond('GET', '/plugin/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    
    $base_plugins = $db->select('plugin', '*');
    $base_plugins_count = count($base_plugins);
    for ($i = 0; $i < $base_plugins_count; ++$i) {
        $base_plugins[$i]['entries'] =
            $db->select('plugin_version', '*', [
                'plugin_entry_id' => $base_plugins[$i]['id'],
                'ORDER' => [
                    'version' => 'DESC',
                ],
        ]);
    }
    
    #echo '<pre>';var_dump($base_plugins);die;
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/plugin/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

$klein->respond('GET', '/plugin/base/[:id]/show', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    
    $id = $request->id ?? null;
    
    if ($id !== null) {
        $db = new DBHelper();
    
        $base_plugin = $db->get('plugin', '*', [
            'id' => $id,
        ]);
    
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/show_base.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        Helper::redirect('/plugin/list?e=none_selected');
    }
});
$klein->respond('GET', '/plugin/version/[:id]/show', function ($request) {

});

$klein->respond(['GET', 'POST'], '/plugin/base/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    
    if (!isset($_POST['btn_plugin_add'])) {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/add_base.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        if (!AuthHelper::checkCSRFToken()) {
            Helper::redirect('?e=unknown_error');
        }
        $_plugin_add = $_POST['_plugin_add'];
        
        if (
            !empty($_plugin_add['plugin_name']) &&
            !empty($_plugin_add['slug']) &&
            !empty($_plugin_add['homepage']) &&
            !empty($_plugin_add['section_description'])
        ) {
            if (in_array($_FILES['_plugin_add_banner_low']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                $parts = explode('.', $_FILES['_plugin_add_banner_low']['name']);
                $end = $parts[count($parts)-1];
                $dir = publicDir() . '/banner_files/' . $_plugin_add['slug'] . '/';
                $file_name = $file_name = $_plugin_add['slug'] . '_banner_low.' . $end;
                if (!is_dir($dir)) {
                    @mkdir($dir, 0775, true);
                }
                move_uploaded_file($_FILES['_plugin_add_banner_low']['tmp_name'], $dir . $file_name);
            } else {
                die('banner low incorrect file type');
            }
    
            if (in_array($_FILES['_plugin_add_banner_high']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                $parts = explode('.', $_FILES['_plugin_add_banner_high']['name']);
                $end = $parts[count($parts)-1];
                $dir = publicDir() . '/banner_files/' . $_plugin_add['slug'] . '/';
                $file_name = $file_name = $_plugin_add['slug'] . '_banner_high.' . $end;
                if (!is_dir($dir)) {
                    @mkdir($dir, 0775, true);
                }
                move_uploaded_file($_FILES['_plugin_add_banner_high']['tmp_name'], $dir . $file_name);
            } else {
                die('banner low incorrect file type');
            }
            
            $allowable_tags = '<b><i><p><strong><ul><ol><li><em><a><img>';
            
            $db = new DBHelper();
            $db->insert('plugin', [
                'plugin_name' => $_plugin_add['plugin_name'],
                'slug' => $_plugin_add['slug'],
                'homepage' => $_plugin_add['homepage'],
                'section_description' => strip_tags($_plugin_add['section_description'], $allowable_tags),
                'section_installation' => strip_tags($_plugin_add['section_installation'], $allowable_tags),
                'section_faq' => strip_tags($_plugin_add['section_faq'], $allowable_tags),
                'section_screenshots' => strip_tags($_plugin_add['section_screenshots'], $allowable_tags),
                'section_changelog' => strip_tags($_plugin_add['section_changelog'], $allowable_tags),
                'section_other_notes' => strip_tags($_plugin_add['section_other_notes'], $allowable_tags),
                'last_updated' => date('Y-m-d H:i:s'),
            ]);
        } else {
            die('Missing fields');
        }
        Helper::redirect('/plugin/list');
    }
});

$klein->respond(['GET', 'POST'], '/plugin/version/add', function ($request) {

});

$klein->respond(['GET', 'POST'], '/plugin/base/[:id]/edit', function ($request) {
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

$klein->respond(['GET', 'POST'], '/plugin/version/[:id]/edit', function ($request) {

});

// base plugin entries cannot be archived
$klein->respond('GET', '/plugin/version/[:id]/archive', function ($request) {
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
