<?php

// returns the most recent plugin version
$klein->respond('GET', '/download/plugin/[:slug]', function ($request) {
    
    // check license validity
    LicenseHelper::checkLicenseValidity($request);
    
    $slug = $request->slug ?? null;
    if ($slug !== null) {
        $db = new DBHelper();
        $row = $db->get('plugin', [
            'id',
            'version',
        ], [
            'slug' => $slug,
            'ORDER' => [
                'version' => 'DESC'
            ],
            'LIMIT' => 1,
        ]);
    
        $db->update('plugin', [
            'downloaded[+]' => 1,
        ]);
    
        $file_name = $slug . '_v' . $row['version'] . '.zip';
        $dir = downloadDir() . '/' . $slug . '/';
    
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        readfile($dir . $file_name);
        die;
    } else {
        echo 'invalid plugin slug';
    }
});