<?php

// returns the most recent plugin version
$klein->respond('GET', '/download/plugin/[:slug]/[:version]', function ($request) {
    
    if (!AuthHelper::isLoggedIn()) {
        LicenseHelper::checkLicenseValidity($request);
    }
    
    $slug = $request->slug ?? null;
    $version = $request->version ?? null;
    
    if ($slug !== null && $version !== null) {
        $db = new DBHelper();
        $base_plugin = $db->get('plugin', [
            'id',
        ], [
            'slug' => $slug,
        ]);
    
        $db->update('plugin', [
            'downloaded[+]' => 1,
        ], [
            'id' => $base_plugin['id'],
        ]);
        
        if ($version === 'latest') {
            if ($db->has('plugin_version', [
                'plugin_entry_id' => $base_plugin['id'],
            ])) {
                $plugin_version = $db->get('plugin_version', [
                    'version',
                ], [
    
                ]);
            } else {
                $plugin_version = null;
            }
        } else {
            if ($db->has('plugin_version', [
                'plugin_entry_id' => $base_plugin['id'],
                'version' => $version,
            ])) {
                $plugin_version = $db->get('plugin_version', [
                    'version',
                ], [
                    'plugin_entry_id' => $base_plugin['id'],
                ]);
            } else {
                $plugin_version = null;
            }
        }
        
        if ($plugin_version === null) {
            die('No files available');
        }
    
        $file_name = $slug . '_v' . $plugin_version['version'] . '.zip';
        $dir = downloadDir() . '/' . $slug . '/';
        
        if (file_exists($dir . $file_name)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            readfile($dir . $file_name);
            die;
        } else {
            echo 'Version file does not exist';
        }
    } else {
        echo 'invalid plugin slug or version constraint';
    }
});