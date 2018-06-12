<?php

$klein->respond('GET', '/api/themes/theme-update/[:slug]', function($request) {
    $slug = $request->slug ?? null;
    
    if ($slug === null) {
        die('invalid slug');
    }
    
    LicenseHelper::checkLicenseValidity($request->headers);
    
    $db = new DBHelper();
    
    $latest_version = $db->get('theme_version', '*', [
        'slug' => $slug,
        'ORDER' => [
            'version' => 'DESC',
        ],
    ]);
    
    $response = array();
    $response['package'] = Helper::getHost() . '/download/theme/' . $slug . '/latest';
    $response['new_version'] = $latest_version['version'];
    $response['url'] = $latest_version['url'];
    $response['tags'] = array(
        'one' => 'ins',
        'two' => 'tzwi',
    );
    
    echo json_encode($response);
    
});

$klein->respond('GET', '/api/themes/theme-information/[:slug]', function($request) {
    $slug = $request->slug ?? null;
    
    if ($slug === null) {
        die('invalid slug');
    }
    
    LicenseHelper::checkLicenseValidity($request->headers);
    
    $db = new DBHelper();
    
    $base_theme = $db->get('theme', '*', [
        'slug' => $slug,
    ]);
    
    $latest_version = $db->get('theme_version', '*', [
        'slug' => $slug,
        'ORDER' => [
            'version' => 'DESC',
        ],
    ]);
    
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
    echo json_encode($data);
    
});