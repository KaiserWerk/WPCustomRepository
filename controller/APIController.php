<?php

// @TODO remove cookie from api calls

$klein->respond('GET', '/api/plugins/check-latest-version/[:slug]', function ($request) {
    // @TODO log API request

    LicenseHelper::checkLicenseValidity($request);
    
    $slug = $request->slug;
    $db = new DBHelper();
    $row = $db->get('plugin', [
        'version',
        'url',
    ], [
        'slug' => $slug,
        'archived' => 0,
        'ORDER' => [
            'version' => 'DESC'
        ]
    ]);
    
    $response = new stdClass();
    $response->slug = $slug;
    $response->new_version = $row['version'];
    $response->url = $row['url'];
    $response->package = Helper::getHost() . '/download/plugin/' . $slug;
    
    if ((bool)getenv('API_USE_JSON') === true) {
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo serialize($response);
    }
});

$klein->respond('GET', '/api/plugins/get-plugin-information/[:slug]', function ($request) {
    // @TODO log API request

    LicenseHelper::checkLicenseValidity($request);
    
    $slug = $request->slug;
    
    $db = new DBHelper();
    $row = $db->get('plugin', [
        'id',
        'plugin_name',
        'version',
        'requires',
        'tested',
        'rating5',
        'rating4',
        'rating3',
        'rating2',
        'rating1',
        'downloaded',
        'last_updated',
        'added',
        'homepage',
        'section_description',
    ], [
        'slug' => $slug,
        'archived' => 0,
        'ORDER' => [
            'version' => 'DESC'
        ]
    ]);
    
    // maybe re-add them later
    /*$sections = $db->select('plugin_section', [
        'section_name',
        'section_content',
    ], [
        'plugin_entry_id' => $row['id']
    ]);*/
    
    $ratings_sum = $row['rating5']+$row['rating4']+$row['rating3']+$row['rating2']+$row['rating1'];
    if ($ratings_sum > 0) {
        $rating = $row['rating5'] * 100 / $ratings_sum;
    } else {
        $rating = 0.0;
    }
    
    $response = new stdClass();
    $response->name = $row['plugin_name'];
    $response->version = $row['version'];
    $response->requires_php = '7.0';
    $response->slug = $slug;
    $response->author = 'Robin Kaiser';
    $response->author_profile = 'https://kaiserrobin.eu';
    $response->requires = $row['requires'];
    $response->tested = $row['tested'];
    $response->rating = $rating;
    $response->num_ratings = $ratings_sum;
    $response->downloaded = $row['downloaded'];
    #$response->donate_link = 'http://donatelink.com';
    $response->active_installations = $row['downloaded'];
    $response->last_updated = $row['last_updated'];
    $response->added = $row['added'];
    $response->homepage = $row['homepage'];
    $response->support_threads = 4588;
    $response->support_threads_resolved = 4586;
    $response->ratings = [
        '5' => $row['rating5'],
        '4' => $row['rating3'],
        '3' => $row['rating3'],
        '2' => $row['rating2'],
        '1' => $row['rating1'],
    ];
    
    // maybe re-add them later
    /*foreach ($sections as $section) {
        $response->sections[$section['section_name']] = $section['section_content'];
    }*/
    $response->sections = array(
        'description' =>  $row['section_description'],
        #'changelog' =>  'changelock',
        #'screenshots' => 'screensh',
    );
    
    $response->download_link = Helper::getHost() . '/download/plugin/' . $slug;
    
    if ((bool)getenv('API_USE_JSON') === true) {
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo serialize($response);
    }
});

$klein->respond('GET', '/info/plugin/[:slug]', function ($request) {

});