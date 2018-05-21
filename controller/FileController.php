<?php

// returns the most recent plugin version
$klein->respond('GET', '/download/plugin/[:slug]', function ($request) {
    $slug = $request->slug;
    $db = new DBHelper();
    
    $row = $db->get('plugin', [
        'id',
        'version',
        'downloaded',
    ], [
        'slug' => $slug,
        'ORDER' => [
            'version' => 'DESC'
        ]
    ]);
    
    $db->update('plugin', [
        'downloaded' => $row['downloaded'] + 1,
    ]);
    
    $file_name = $slug . '_v' . $row['version'] . '.zip';
    $dir = downloadDir() . '/' . $slug . '/';
    
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="'.$file_name.'"');
    readfile($dir . $file_name);
    die;
});