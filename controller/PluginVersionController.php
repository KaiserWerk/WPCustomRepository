<?php
// @TODO update updated_at when version is added/edited/removed
$router->respond('GET', '/plugin/version/[:id]/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    
    $id = $request->id ?? null;
    
    if ($id !== null) {
        
        $base_plugin = $db->get('plugin', '*', [
            'id' => (int)$id,
        ]);
        
        $plugin_versions = $db->select('plugin_version', '*', [
            'plugin_entry_id' => (int)$id,
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);
    }
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/plugin/list_versions.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

$router->respond('GET', '/plugin/version/[:id]/show', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
    $id = $request->id ?? null;
    
    if ($id !== null) {
        $db = new DBHelper();
        
        $plugin_version = $db->get('plugin_version', '*', [
            'id' => (int)$id,
        ]);
        
        $base_plugin = $db->get('plugin', '*', [
            'id' => (int)$plugin_version['plugin_entry_id'],
        ]);

        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/show_version.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        Helper::setMessage('You are trying to access an invalid entry!', 'danger');
        Helper::redirect('/plugin/list');
    }
});

$router->respond(['GET', 'POST'], '/plugin/version/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    if (isset($_POST['btn_plugin_add'])) {
    
    } else {
        $base_plugins = $db->select('plugin', [
            'id',
            'plugin_name',
        ]);
        
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/add_version.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});

$router->respond(['GET', 'POST'], '/plugin/version/[:id]/edit', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    $id = (int)$request->id;
    $db = new DBHelper();
    if (isset($_POST['btn_plugin_version_edit'])) {
        if (!AuthHelper::checkCSRFToken()) {
            Helper::setMessage('An unknown error occured!', 'danger');
            Helper::redirect('/plugin/version/' . $id . '/edit');
        }
        $_plugin_version_edit = $_POST['_plugin_version_edit'];
        
        $fields = [
            'version' => '',
            'requires' => '',
            'requires_php' => '',
            'tested' => '',
        ];
        
        foreach ($_plugin_version_edit as $key => $value) {
            if (!empty($_plugin_version_edit[$key])) {
                $fields[$key] = $_plugin_version_edit[$key];
            } else {
                unset($fields[$key]);
            }
        }
        
        $db->update('plugin_version', $fields, [
            'id' => $id,
        ]);
    
        $plugin_version = $db->get('plugin_version', '*', [
            'id' => $id,
        ]);
        $base_plugin = $db->get('plugin', '*', [
            'id' => $plugin_version['plugin_entry_id'],
        ]);
        
        Helper::setMessage('Changes saved!', 'success');
        Helper::redirect('/plugin/version/' . $base_plugin['id'] . '/list');
    } else {
        $plugin_version = $db->get('plugin_version', '*', [
            'id' => $id,
        ]);
        #var_dump($plugin_version);die;
        $base_plugin = $db->get('plugin', '*', [
            'id' => $plugin_version['plugin_entry_id'],
        ]);
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/edit_version.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});

// base plugin entries cannot be archived
$router->respond('GET', '/plugin/version/[:id]/archive', function ($request) {
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
        
        Helper::setMessage('Plugin version archived!', 'success');
        Helper::redirect('/plugin/list');
    } else {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/plugin/archive.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});


$router->respond('GET', '/plugin/version/[:id]/toggle-archived', function ($request) {

});

$router->respond('GET', '/plugin/version/[:id]/remove', function ($request) {

});

