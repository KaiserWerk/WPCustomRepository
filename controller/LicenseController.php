<?php

$router->with('/license', function () use ($router) {
    
    $router->respond('GET', '/list', function ($request) {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        $licenses = $db->select('license', '*', [
            'ORDER' => [
                'created_at' => 'DESC',
            ]
        ]);
        
        Helper::renderPage('/license/list.tpl.php', [
            'licenses' => $licenses,
        ]);
    });
    
    $router->respond(['GET', 'POST'], '/add', function ($request) {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        if (isset($_POST['license_button'])) {
            $_add = $_POST['_add'];
            
            AuthHelper::requireValidCSRFToken();
            AuthHelper::requireValidHonepot();
            
            if (empty($_add['license_user']) || empty($_add['license_key']) || empty($_add['valid_until'])) {
                Helper::setMessage('Please fill in all required fields!', 'warning');
                Helper::redirect('/license/add');
            }
            
            if ($db->has('license', [
                'license_key' => $_add['license_key'],
            ])) {
                Helper::setMessage('This license key is already in use!', 'danger');
                Helper::redirect('/license/add');
            }
            
            if ($db->has('license', [
                'AND' => [
                    'license_user' => $_add['license_user'],
                    'license_host' => $_add['license_host'],
                    'plugin_slug' => $_add['plugin_slug'],
                ]
            ])) {
                Helper::setMessage('This license already exists! Please renew as needed.', 'danger');
                Helper::redirect('/license/add');
            }
            
            $db->insert('license', [
                'license_user' => $_add['license_user'],
                'license_key' => $_add['license_key'],
                'license_host' => $_add['license_host'],
                'plugin_slug' => $_add['plugin_slug'],
                'valid_until' => $_add['valid_until'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        
            Helper::setMessage('License added!', 'success');
            Helper::redirect('/license/list');
        } else {
            $pluginSlugSelections = @array_unique($db->select('plugin', ['plugin_name', 'slug']));
    
            $key = AuthHelper::generateToken(200);
            
            Helper::renderPage('/license/add.tpl.php', [
                'pluginSlugSelection' => $pluginSlugSelections,
                'key' => $key,
            ]);
        }
    });
    
    $router->respond(['GET', 'POST'], '/[:id]/renew', function ($request) {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        $id = $request->id ?? null;
        
        if ($id !== null) {
            $license = $db->get('license', [
                'id',
                'valid_until',
                'renewals',
            ], [
                'id' => $id,
            ]);
        
            if (new \DateTime($license['valid_until']) <= new \DateTime('+12 month')) {
                // renew until end of next year
                $db->update('license', [
                    'valid_until' => date('Y') + 1 . '-12-31 23:59:59',
                    'renewals' => $license['renewals'] + 1,
                ], [
                    'id' => $id,
                ]);
            
                Helper::setMessage('License renewed!', 'success');
                Helper::redirect('/license/list');
            } else {
                Helper::setMessage('No changes were made.');
                Helper::redirect('/license/list');
            }
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    });
    
    $router->respond('GET', '/[:id]/auto-renewal/toggle', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id ?? null;
        if ($id !== null) {
            $db = new DBHelper();
            // @TODO auto renewal toggle if at all
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    });
    
    $router->respond(['GET', 'POST'], '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id ?? null;
        $db = new DBHelper();
        if ($id !== null) {
            if (isset($_POST['btn_license_edit'])) {
                $_edit = $_POST['_edit'];
            
                AuthHelper::requireValidCSRFToken();
                AuthHelper::requireValidHonepot();
            
                if (empty($_edit['license_user']) || empty($_edit['license_key']) || empty($_edit['valid_until'])) {
                    Helper::setMessage('Please fill in all required fields!', 'warning');
                    Helper::redirect('/license/'.$id.'/edit');
                }
            
                if ($db->has('license', [
                    'license_key' => $_edit['license_key'],
                    'id[!]' => $id,
                ])) {
                    Helper::setMessage('This license key is already in use!', 'danger');
                    Helper::redirect('/license/'.$id.'/edit');
                }
            
                if ($db->has('license', [
                    'AND' => [
                        'license_user' => $_edit['license_user'],
                        'license_host' => $_edit['license_host'],
                        'plugin_slug' => $_edit['plugin_slug'],
                        'id[!]' => $id,
                    ]
                ])) {
                    Helper::setMessage('This license already exists! Please renew as needed!', 'danger');
                    Helper::redirect('/license/'.$id.'/edit');
                }
            
                $db->update('license', [
                    'license_user' => $_edit['license_user'],
                    'license_key' => $_edit['license_key'],
                    'license_host' => $_edit['license_host'],
                    'plugin_slug' => $_edit['plugin_slug'],
                    'valid_until' => $_edit['valid_until'],
                ], [
                    'id' => $id,
                ]);
        
                Helper::setMessage('Changes saved!', 'success');
                Helper::redirect('/license/list');
            } else {
                $pluginSlugSelections = @array_unique($db->select('plugin', ['plugin_name', 'slug']));
    
                $license = $db->get('license', '*', [
                    'id' => $id,
                ]);
                
                Helper::renderPage('/license/edit.tpl.php', [
                    'pluginSlugSelection' => $pluginSlugSelections,
                    'license' => $license,
                ]);
            }
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    });
    
    $router->respond('GET', '/[:id]/remove', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id ?? null;
        if ($id !== null) {
            $db = new DBHelper();
            $db->delete('license', [
                'id' => $id,
            ]);
            
            Helper::setMessage('License removed!', 'success');
            Helper::redirect('/license/list');
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    });
    
});