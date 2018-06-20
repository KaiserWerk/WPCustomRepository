<?php
// @TODO update updated_at when version is added/edited/removed

$router->with('/plugin/version', function () use ($router) {
    
    $router->respond('GET', '/[:id]/list', function ($request) {
        AuthHelper::requireLogin();
        
        
        $id = $request->id ?? null;
        if ($id === null) {
            Helper::setMessage('Invalid entry!', 'danger');
            Helper::redirect('/plugin/base/list');
        }
        
        $db = new DBHelper();
        $base_plugin = $db->get('plugin', '*', [
            'id' => (int)$id,
        ]);
        if ($base_plugin === false) {
            Helper::setMessage('Base plugin not found!', 'danger');
            Helper::redirect('/plugin/base/list');
        }
        
        $plugin_versions = $db->select('plugin_version', '*', [
            'plugin_entry_id' => (int)$id,
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);
    
        if ($plugin_versions === false) {
            Helper::setMessage('No plugin versions found!', 'danger');
            Helper::redirect('/plugin/base/list');
        }
        
        Helper::renderPage('/plugin/list_versions.tpl.php', [
            'base_plugin' => $base_plugin,
            'plugin_version' => $plugin_versions,
        ]);
    });
    
    $router->respond('GET', '/[:id]/show', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id ?? null;
        
        if ($id !== null) {
            $db = new DBHelper();
            
            $plugin_version = $db->get('plugin_version', '*', [
                'id' => (int)$id,
            ]);
    
            if ($plugin_version === false) {
                Helper::setMessage('Entry not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
            
            $base_plugin = $db->get('plugin', '*', [
                'id' => (int)$plugin_version['plugin_entry_id'],
            ]);
    
            if ($base_plugin === false) {
                Helper::setMessage('Base plugin not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
            
            Helper::renderPage('/plugin/show_version.tpl.php', [
                'plugin_version' => $plugin_version,
                'base_plugin' => $base_plugin,
            ]);
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/plugin/list');
        }
    });
    
    $router->respond(['GET', 'POST'], '/add', function ($request) {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        if (isset($_POST['btn_plugin_add'])) {
            // @TODO
        } else {
            $base_plugins = $db->select('plugin', [
                'id',
                'plugin_name',
            ]);
    
            if ($base_plugins === false) {
                Helper::setMessage('No plugins found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
    
            Helper::renderPage('/plugin/add_version.tpl.php', [
                'base_plugin' => $base_plugins,
            ]);
        }
    });
    
    $router->respond(['GET', 'POST'], '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
        
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
    
            if ($plugin_version === false) {
                Helper::setMessage('Plugin version not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
            
            $base_plugin = $db->get('plugin', '*', [
                'id' => $plugin_version['plugin_entry_id'],
            ]);
    
            if ($base_plugin === false) {
                Helper::setMessage('Base plugin not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
            
            Helper::setMessage('Changes saved!', 'success');
            Helper::redirect('/plugin/version/' . $base_plugin['id'] . '/list');
        } else {
            $plugin_version = $db->get('plugin_version', '*', [
                'id' => $id,
            ]);
    
            if ($plugin_version === false) {
                Helper::setMessage('Plugin version not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
            
            $base_plugin = $db->get('plugin', '*', [
                'id' => $plugin_version['plugin_entry_id'],
            ]);
    
            if ($base_plugin === false) {
                Helper::setMessage('Base plugin not found!', 'danger');
                Helper::redirect('/plugin/base/list');
            }
    
            Helper::renderPage('/plugin/edit_version.tpl.php', [
                'plugin_version' => $plugin_version,
                'base_plugin' => $base_plugin,
            ]);
        }
    });
    
    // base plugin entries cannot be archived
    $router->respond('GET', '/[:id]/archive', function ($request) {
        AuthHelper::requireLogin();
        
        /*$id = $request->id;
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
    
        if ($plugin === false) {
            Helper::setMessage('Plugin not found!', 'danger');
            Helper::redirect('/plugin/base/list');
        }
        
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
            Helper::renderPage('/plugin/archive.tpl.php');
        }*/
    });
    
    
    /*$router->respond('GET', '/[:id]/toggle-archived', function ($request) {
    
    });
    
    $router->respond('GET', '/[:id]/remove', function ($request) {
    
    });*/
    
});