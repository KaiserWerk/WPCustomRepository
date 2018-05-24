<?php

$klein->respond('GET', '/license/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
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

$klein->respond(['GET', 'POST'], '/license/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!isset($_POST['license_button'])) {
        $key = AuthHelper::generateToken(200);
    
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/license/add.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    } else {
        $_add = $_POST['_add'];
        $db = new DBHelper();
        
        if (!AuthHelper::checkCSRFToken()) {
            Helper::redirect('/license/add?e=unknown_error');
        }
        if (!AuthHelper::checkHoneypot()) {
            Helper::redirect('/license/add?e=unknown_error');
        }
        
        if (empty($_add['license_user']) || empty($_add['license_key']) || empty($_add['valid_until'])) {
            Helper::redirect('/license/add?e=missing_input');
        }
        
        if ($db->has('license', [
            'license_key' => $_add['license_key'],
        ])) {
            Helper::redirect('/license/add?e=key_in_use');
        }
        
        if ($db->has('license', [
            'AND' => [
                'license_user' => $_add['license_user'],
                'license_host' => $_add['license_host'],
                'plugin_slug' => $_add['plugin_slug'],
            ]
        ])) {
            Helper::redirect('/license/add?e=license_exists');
        }
        
        $db->insert('license', [
            'license_user' => $_add['license_user'],
            'license_key' => $_add['license_key'],
            'license_host' => $_add['license_host'],
            'plugin_slug' => $_add['plugin_slug'],
            'valid_until' => $_add['valid_until'],
            'auto_renewal' => $_add['auto_renewal'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    
        Helper::redirect('/license/add?e=add_success');
    }
});

$klein->respond(['GET', 'POST'], '/license/[:id]/renew', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    $id = $request->id;
    
    $license = $db->get('license', [
        'id',
        'valid_until',
        'renewals',
    ], [
        'id' => $id,
    ]);
    
    if (new \DateTime($license['valid_until']) <= new \DateTime('+6 month')) {
        // renew until end of next year
        $db->update('license', [
            'valid_until' => date('Y') + 1 . '-12-31 23:59:59',
            'renewals' => $license['renewals'] + 1,
        ], [
            'id' => $id,
        ]);
    
        Helper::redirect('/license/list?e=renewal_success');
    } else {
        Helper::redirect('/license/list?e=no_change');
    }
});

$klein->respond('GET', '/license/[:id]/auto-renewal/toggle', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    $db = new DBHelper();
    $id = $request->id;
    
    
});