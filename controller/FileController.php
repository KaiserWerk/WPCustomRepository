<?php

// returns the most recent plugin version
$klein->respond('GET', '/download/plugin/[:slug]/[:version]', function ($request) {
    
    if (!AuthHelper::isLoggedIn()) {
        LicenseHelper::checkLicenseValidity($request->headers);
    }
    
    $slug = $request->slug ?? null;
    $version = $request->version ?? null;
    
    $db = new DBHelper();
    
    $base_plugin = $db->get('plugin', [
        'id',
    ], [
        'slug' => $slug,
    ]);
    
    $latest_version = $db->max('plugin_version', 'version', [
        'plugin_entry_id' => $base_plugin['id'],
    ]);
    
    if ($latest_version === false) {
        die('No files available');
    }
    
    if ($slug !== null && $version !== null) {
        
        if ($version === 'latest') {
            $download_version = $latest_version;
        } else {
            $download_version = $version;
        }
        
        $db->update('plugin_version', [
            'downloaded[+]' => 1,
        ], [
            'plugin_entry_id' => $base_plugin['id'],
            'version' => $download_version,
        ]);
        
        $file_name = $slug . '_v' . $download_version . '.zip';
        $dir = downloadDir() . '/' . $slug . '/';
        
        if (file_exists($dir . $file_name)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            readfile($dir . $file_name);
            die;
        } else {
            die('Version file does not exist');
        }
    } else {
        die('invalid plugin slug or version constraint');
    }
});