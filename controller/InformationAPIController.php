<?php
$router->with('/api/information', function () use ($router) {
    
    $router->respond('GET', '/license', function ($request) {
        $headers = getallheaders();
        $licenseKey  = $headers['X-License-Key'] ?? null;
        
        if ($licenseKey === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Missing license data',
            ], 400);
        }
        
        $db = new DBHelper();
        $license = $db->get('license', '*', [
            'license_key' => $licenseKey
        ]);
        
        HTTPHelper::jsonResponse([
            'status' => 'success',
            'message' => '',
            'body' => [
                'valid_until' => $license['valid_until'],
            ]
        ]);
        
    });
    
    
});