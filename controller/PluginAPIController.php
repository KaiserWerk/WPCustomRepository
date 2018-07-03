<?php

$router->with('/api/plugins', function () use ($router) {
    
    $router->respond('GET', '/check-latest-version/[:slug]', function ($request) {
        
        // @TODO remove cookie from api calls
        
        LoggerHelper::logAPIRequest('/api/plugins/check-latest-version/[:slug]', $_SERVER['REQUEST_METHOD'], getallheaders());
    
        LicenseHelper::checkLicenseValidity();
        
        $slug = $request->slug;
        $db = new DBHelper();
        $base_plugin = $db->get('plugin', [
            'id',
        ], [
            'slug' => $slug,
        ]);
        
        $latest_version = $db->get('plugin_version', [
            'version'
        ], [
            'plugin_entry_id' => $base_plugin['id'],
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);
        
        $response = new stdClass();
        $response->slug = $slug;
        $response->new_version = $latest_version['version'];
        $response->package = Helper::getHost() . '/download/plugin/' . $slug . '/latest';
        
        HTTPHelper::jsonResponse((array)$response);
    });
    
    $router->respond('GET', '/get-plugin-information/[:slug]', function ($request) {

        // @TODO remove cookie from api calls
    
        LoggerHelper::logAPIRequest('/api/plugins/get-plugin-information/[:slug]', $_SERVER['REQUEST_METHOD'], getallheaders());
        
        LicenseHelper::checkLicenseValidity();
        
        $slug = $request->slug;
        
        $db = new DBHelper();
        $base_plugin = $db->get('plugin', '*', [
            'slug' => $slug,
        ]);
        
        $latest_version = $db->get('plugin_version', '*', [
            'plugin_entry_id' => $base_plugin['id'],
            'ORDER' => [
                'version' => 'DESC'
            ]
        ]);
        
        $ratings_sum = $base_plugin['rating5']+$base_plugin['rating4']+$base_plugin['rating3']+$base_plugin['rating2']+$base_plugin['rating1'];
        if ($ratings_sum > 0) {
            $rating = $base_plugin['rating5'] * 100 / $ratings_sum;
        } else {
            $rating = 0.0;
        }
        
        $response = new stdClass();
        $response->name = $base_plugin['plugin_name'];
        $response->version = $latest_version['version'];
        $response->requires_php = $latest_version['requires_php'];
        $response->slug = $slug;
        $response->author = 'Robin Kaiser'; // @TODO turn into DB field
        $response->author_profile = 'https://kaiserrobin.eu'; // @TODO turn into DB field
        $response->requires = $latest_version['requires'];
        $response->tested = $latest_version['tested'];
        $response->rating = $rating;
        $response->num_ratings = $ratings_sum;
        $response->downloaded = $base_plugin['downloaded'];
        #$response->donate_link = 'http://donatelink.com';
        $response->active_installations = $latest_version['active_installations'];
        $response->last_updated = $base_plugin['last_updated'];
        $response->added = $latest_version['added_at'];
        $response->homepage = $base_plugin['homepage'];
        #$response->support_threads = 4588;
        #$response->support_threads_resolved = 4586;
        $response->ratings = [
            '5' => $base_plugin['rating5'],
            '4' => $base_plugin['rating3'],
            '3' => $base_plugin['rating3'],
            '2' => $base_plugin['rating2'],
            '1' => $base_plugin['rating1'],
        ];
        $response->sections = [
            'description' => $base_plugin['section_description'],
            'installation' => $base_plugin['section_installation'],
            'faq' => $base_plugin['section_faq'],
            'screenshots' => $base_plugin['section_screenshots'],
            'changelog' => $base_plugin['section_changelog'],
            #'reviews' => 'none yet', // doesnt work anyway
            'other_notes' => $base_plugin['section_other_notes'],
        ];
        if (!empty($base_plugin['section_installation'])) {
            $response->sections['installation'] = $base_plugin['section_installation'];
        }
        if (!empty($base_plugin['section_faq'])) {
            $response->sections['faq'] = $base_plugin['section_faq'];
        }
        if (!empty($base_plugin['section_screenshots'])) {
            $response->sections['screenshots'] = $base_plugin['section_screenshots'];
        }
        if (!empty($base_plugin['section_changelog'])) {
            $response->sections['changelog'] = $base_plugin['section_changelog'];
        }
        if (!empty($base_plugin['section_reviews'])) {
            $response->sections['reviews'] = $base_plugin['section_reviews'];
        }
        if (!empty($base_plugin['section_other_notes'])) {
            $response->sections['other_notes'] = $base_plugin['section_other_notes'];
        }
        
        $response->banners = array();
        
        if (file_exists(publicDir() . '/banner_files/' . $slug . '/' . $slug . '_banner_low.png')) {
            $response->banners['low'] = Helper::getHost() . '/banner_files/' . $slug . '/' . $slug . '_banner_low.png';
        }
        if (file_exists(publicDir() . '/banner_files/' . $slug . '/' . $slug.'_banner_high.png')) {
            $response->banners['high'] = Helper::getHost() . '/banner_files/' . $slug . '/' . $slug.'_banner_high.png';
        }
        
        $response->download_link = Helper::getHost() . '/download/plugin/' . $slug . '/'.$latest_version['version'];
        
        HTTPHelper::jsonResponse($response);
    });
    
    
    $router->respond('POST', '/track-installations', function () {
        // @TODO remove cookie from api calls
        LoggerHelper::logAPIRequest('/api/plugins/track-installations', $_SERVER['REQUEST_METHOD'], getallheaders());
        $slug = $_POST['slug'] ?? null;
        $version = $_POST['version'] ?? null;
        $action = $_POST['action'] ?? null;
        
        if ($slug === null || $version === null || $action === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'missing or invalid parameters',
            ], 400);
        }
        
        $db = new DBHelper();
        
        $base_plugin = $db->get('plugin', [
            'id',
        ], [
            'slug' => $slug,
        ]);
        
        $bool = $db->has('plugin_version', [
            'plugin_entry_id' => $base_plugin['id'],
            'version' => $version,
            'archived' => 0,
        ]);
        
        if (!$bool) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'no plugin file found for version string',
            ], 404);
        }
        
        $fields = [];
        if ($action === 'installed') {
            $fields = [
                'active_installations[+]' => 1,
            ];
        } elseif ($action === 'uninstalled') {
            $fields = [
                'active_installations[-]' => 1,
            ];
        }
        
        $db->update('plugin_version', $fields, [
            'plugin_entry_id' => $base_plugin['id'],
            'version' => $version,
        ]);
    
        LoggerHelper::logAPIRequest('/track-installations', $_SERVER['REQUEST_METHOD'], getallheaders());
        HTTPHelper::jsonResponse([
            'status' => 'success',
            'message' => 'Action tracked',
        ]);
    });
    
});