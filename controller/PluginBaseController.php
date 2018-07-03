<?php
AuthHelper::init();
$router->with('/plugin/base', function () use ($router) {
    
    $router->respond('GET', '/list', function ($request) {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        $base_plugins = $db->select('plugin', '*');
        $base_plugins_count = count($base_plugins);
        for ($i = 0; $i < $base_plugins_count; ++$i) {
            $base_plugins[$i]['entries'] = $db->select('plugin_version', '*', [
                'plugin_entry_id' => $base_plugins[$i]['id'],
                'ORDER' => [
                    'version' => 'DESC',
                ],
                'LIMIT' => 3,
            ]);
        }
        
        Helper::renderPage('/plugin/list_base.tpl.php', [
            'base_plugins' => $base_plugins,
        ]);
    });
    
    $router->respond('GET', '/[:id]/show', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id ?? null;
        
        if ($id !== null) {
            $db = new DBHelper();
        
            $base_plugin = $db->get('plugin', '*', [
                'id' => $id,
            ]);
            
            Helper::renderPage('/plugin/show_base.tpl.php', [
                'base_plugin' => $base_plugin,
            ]);
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/plugin/list');
        }
    });
    
    
    $router->respond(['GET', 'POST'], '/add', function ($request) {
        AuthHelper::requireLogin();
        
        if (isset($_POST['btn_plugin_base_add'])) {
            AuthHelper::requireValidCSRFToken();
            
            $_plugin_base_add = $_POST['_plugin_base_add'];
            if (
                !empty($_plugin_base_add['plugin_name']) &&
                !empty($_plugin_base_add['slug']) &&
                !empty($_plugin_base_add['homepage']) &&
                !empty($_plugin_base_add['section_description'])
            ) {
                if (in_array($_FILES['_plugin_add_banner_low']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $parts = explode('.', $_FILES['_plugin_add_banner_low']['name']);
                    $end = $parts[count($parts)-1];
                    $dir = publicDir() . '/banner_files/' . $_plugin_base_add['slug'] . '/';
                    $file_name = $file_name = $_plugin_base_add['slug'] . '_banner_low.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_add_banner_low']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner low has incorrect file type', 'warn');
                }
        
                if (in_array($_FILES['_plugin_add_banner_high']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $parts = explode('.', $_FILES['_plugin_add_banner_high']['name']);
                    $end = $parts[count($parts)-1];
                    $dir = publicDir() . '/banner_files/' . $_plugin_base_add['slug'] . '/';
                    $file_name = $file_name = $_plugin_base_add['slug'] . '_banner_high.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_add_banner_high']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner high has incorrect file type', 'warn');
                }
                
                $allowable_tags = '<b><i><p><strong><ul><ol><li><em><a><img>';
                
                $db = new DBHelper();
                $db->insert('plugin', [
                    'plugin_name' => $_plugin_base_add['plugin_name'],
                    'slug' => $_plugin_base_add['slug'],
                    'homepage' => $_plugin_base_add['homepage'],
                    'section_description' => strip_tags($_plugin_base_add['section_description'], $allowable_tags),
                    'section_installation' => strip_tags($_plugin_base_add['section_installation'], $allowable_tags),
                    'section_faq' => strip_tags($_plugin_base_add['section_faq'], $allowable_tags),
                    'section_screenshots' => strip_tags($_plugin_base_add['section_screenshots'], $allowable_tags),
                    'section_changelog' => strip_tags($_plugin_base_add['section_changelog'], $allowable_tags),
                    'section_other_notes' => strip_tags($_plugin_base_add['section_other_notes'], $allowable_tags),
                    'last_updated' => date('Y-m-d H:i:s'),
                ]);
        
                Helper::setMessage('Base plugin added!', 'success');
                Helper::redirect('/plugin/base/list');
            } else {
                Helper::setMessage('Please fill in all required fields!', 'warning');
                Helper::redirect('/plugin/base/add');
            }
            Helper::redirect('/plugin/base/list');
        } else {
            Helper::renderPage('/plugin/add_base.tpl.php');
        }
    });
    
    
    
    $router->respond(['GET', 'POST'], '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
        $id = (int)$request->id;
        $db = new DBHelper();
        if (isset($_POST['btn_plugin_edit'])) {
            AuthHelper::requireValidCSRFToken();
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
        
            Helper::setMessage('Changes saved!', 'success');
            Helper::redirect('/plugin/list');
        } else {
            $plugin = $db->get('plugin', '*', [
                'id' => $id,
            ]);

            Helper::renderPage('/plugin/edit_base.tpl.php', [
                'plugin' => $plugin,
            ]);
        }
    });
    
});