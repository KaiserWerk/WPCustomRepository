<?php

$router->with('/api/themes', function () use ($router) {
    
    $router->respond('GET', '/theme-update/[:slug]', function($request) {
        $slug = $request->slug ?? null;
        
        if ($slug === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'invalid slug',
            ], 400);
        }
        
        LicenseHelper::checkLicenseValidity();
        
        $db = new DBHelper();
    
        $base_theme = $db->get('theme', [
            'id',
        ], [
            'slug' => $slug,
        ]);
    
        if ($base_theme === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Base theme not found',
            ], 404);
        }
        
        $latest_version = $db->get('theme_version', '*', [
            'theme_entry_id' => $base_theme['id'],
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);

        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Theme version not found',
            ], 404);
        }
        
        $response = array();
        $response['package'] = Helper::getHost() . '/download/theme/' . $slug . '/latest';
        $response['new_version'] = $latest_version['version'];
        $response['url'] = $latest_version['url'];
        $response['tags'] = array(
            'one' => 'ins',
            'two' => 'tzwi',
        );
        
        HTTPHelper::jsonResponse($response);
        
    });
    
    $router->respond('GET', '/theme-information/[:slug]', function($request) {
        $slug = $request->slug ?? null;
        
        if ($slug === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'invalid slug',
            ], 400);
        }
        
        LicenseHelper::checkLicenseValidity();
        
        $db = new DBHelper();
        
        $base_theme = $db->get('theme', '*', [
            'slug' => $slug,
        ]);
    
        if ($base_theme === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Base theme not found',
            ], 404);
        }
        
        $latest_version = $db->get('theme_version', '*', [
            'theme_entry_id' => $base_theme['id'],
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);
    
        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Theme version not found',
            ], 404);
        }
        
        $data = new stdClass;
        $data->slug = $slug;
        $data->name = $base_theme['theme_name'];
        $data->version = $latest_version['version'];
        $data->last_updated = $base_theme['updated_at'];
        $data->download_link = Helper::getHost() . '/download/theme/' . $slug . '/latest';
        $data->author = $base_theme['author'];
        $data->requires = $latest_version['requires'];
        $data->tested = $latest_version['tested'];
        $data->screenshot_url = 'https://www.whatsbroadcast.com/wp-content/uploads/2017/09/API.png'; // TODO
        $data->sections = [
            'description' => $base_theme['section_description'],
        ];
        
        HTTPHelper::jsonResponse($data);
    });
    
});