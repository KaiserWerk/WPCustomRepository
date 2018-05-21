<?php

$klein->respond('GET', '/api/plugins/check-latest-version/[:slug]', function ($request) {
    $slug = $request->slug;
    
    $db = new DBHelper();
    $row = $db->get('plugin', [
        'version',
        'url',
    ], [
        'slug' => $slug,
        'ORDER' => [
            'version' => 'DESC'
        ]
    ]);
    
    $response = new stdClass();
    $response->slug = $slug;
    $response->new_version = $row['version'];
    $response->url = $row['url'];
    $response->package = 'http://wpcustomrepository.local/dl/' . $slug . '/' . $slug . '_v' . $response->new_version . '.zip';
    
    echo serialize($response);
});

$klein->respond('GET', '/api/plugins/get-plugin-information/[:slug]', function ($request) {
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
        'ORDER' => [
            'version' => 'DESC'
        ]
    ]);
    
    /*$sections = $db->select('plugin_section', [
        'section_name',
        'section_content',
    ], [
        'plugin_entry_id' => $row['id']
    ]);*/
    
    $ratings_sum = $row['rating5']+$row['rating4']+$row['rating3']+$row['rating2']+$row['rating1'];
    $rating = $row['rating5'] * 100 / $ratings_sum;
    
    $response = new stdClass();
    $response->name = $row['plugin_name'];
    $response->version = $row['version'];
    $response->requires_php = '7.0';
    $response->slug = $slug;
    $response->author = 'Robin Kaiser';
    $response->author_profile = 'http://google.de';
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
    $response->ratings = [
        '5' => $row['rating5'],
        '4' => $row['rating3'],
        '3' => $row['rating3'],
        '2' => $row['rating2'],
        '1' => $row['rating1'],
    ];
    /*foreach ($sections as $section) {
        $response->sections[$section['section_name']] = $section['section_content'];
    }*/
    $response->sections = array(
        'description' =>  $row['section_description'],
        #'changelog' =>  'changelock',
        #'screenshots' => 'screensh',
    );
    $response->download_link = 'http://wpcustomrepository.local/dl/' . $slug . '/' . $slug . '_v' . $row['version'] . '.zip';
    
    #echo '<br><br><pre>';
    #var_dump($response);
    #die;
    
    echo serialize($response);
});