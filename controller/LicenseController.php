<?php

$router->respond('GET', '/license/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
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

$router->respond(['GET', 'POST'], '/license/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    if (!isset($_POST['license_button'])) {
        $pluginSlugSelections = @array_unique($db->select('plugin', ['plugin_name', 'slug']));
        
        $key = AuthHelper::generateToken(200);
    
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/license/add.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        $_add = $_POST['_add'];
        
        if (!AuthHelper::checkCSRFToken()) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/license/add');
        }
        if (!AuthHelper::checkHoneypot()) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/license/add');
        }
        
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
            Helper::setMessage('This license already exists! Please renew as needed!', 'danger');
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
    }
});

$router->respond(['GET', 'POST'], '/license/[:id]/renew', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
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

$router->respond('GET', '/license/[:id]/auto-renewal/toggle', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
    $id = $request->id ?? null;
    if ($id !== null) {
        $db = new DBHelper();
        // @TODO auto renewal toggle
    } else {
        Helper::setMessage('You are trying to access an invalid entry!', 'danger');
        Helper::redirect('/license/list');
    }
});

$router->respond(['GET', 'POST'], '/license/[:id]/edit', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
    $id = $request->id ?? null;
    $db = new DBHelper();
    if ($id !== null) {
        if (!isset($_POST['btn_license_edit'])) {
            $pluginSlugSelections = @array_unique($db->select('plugin', ['plugin_name', 'slug']));
            
            $license = $db->get('license', '*', [
                'id' => $id,
            ]);
            
            require_once viewsDir() . '/header.tpl.php';
            require_once viewsDir() . '/license/edit.tpl.php';
            require_once viewsDir() . '/footer.tpl.php';
        } else {
            $_edit = $_POST['_edit'];
        
            if (!AuthHelper::checkCSRFToken()) {
                Helper::setMessage('Unknown error!', 'danger');
                Helper::redirect('/license/'.$id.'/edit');
            }
            if (!AuthHelper::checkHoneypot()) {
                Helper::setMessage('Unknown error!', 'danger');
                Helper::redirect('/license/'.$id.'/edit');
            }
        
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
        }
    } else {
        Helper::setMessage('You are trying to access an invalid entry!', 'danger');
        Helper::redirect('/license/list');
    }
});

$router->respond('GET', '/license/[:id]/remove', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    
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