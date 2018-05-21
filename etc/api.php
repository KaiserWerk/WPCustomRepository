<?php

$action = $_REQUEST['action'];
$slug = $_REQUEST['slug'];

// Create new object
$response = new stdClass;

switch( $action ) {
    
    // API is asked for the existence of a new version of the plugin
    case 'check-latest-version':
        if ($slug === 'test-plugin-update') {
            $response->slug = 'test-plugin-update';
            $response->new_version = '1.1.5';
            $response->url = 'http://plugin.url/';
            $response->package = 'http://localhost/projects/wp-autoupdate-repository/' . $response->slug . '.zip';
        } else if ($slug === 'test-plugin-update-2') {
            $response->slug = 'test-plugin-update-2';
            $response->new_version = '0.7.3';
            $response->url = 'http://plugin.url/';
            $response->package = 'http://localhost/projects/wp-autoupdate-repository/' . $response->slug . '.zip';
        }
        break;
    
    // Request for detailed information
    case 'get-plugin-information':
        if ($slug === 'test-plugin-update') {
            $response->name = 'Test Plugin Update';
            $response->slug = 'test-plugin-update';
            $response->requires = '4.9';
            $response->tested = '4.9.6';
            $response->rating = 100.0; //just for fun, gives us a 5-star rating :)
            $response->num_ratings = 1000000000; //just for fun, a lot of people rated it :)
            $response->downloaded = 1000000000; //just for fun, a lot of people downloaded it :)
            $response->last_updated = '2018-04-18';
            $response->added = '2018-02-01';
            $response->homepage = "http://localhost/projects/wp-autoupdate-repository/";
            $response->sections = array(
                'description' =>  'Add a description of your plugin',
                'changelog' =>  'Add a list of changes to your plugin',
                'screenshots' => 'screenshots',
            );
            $response->download_link = 'http://localhost/projects/wp-autoupdate-repository/'.$response->slug.'.zip';
        } else if ($slug === 'test-plugin-update-2') {
            $response->name = 'Test Plugin Update 2';
            $response->slug = 'test-plugin-update-2';
            $response->requires = '4.5';
            $response->tested = '4.5.2';
            $response->rating = 72.1; //just for fun, gives us a 5-star rating :)
            $response->num_ratings = 645; //just for fun, a lot of people rated it :)
            $response->downloaded = 498463; //just for fun, a lot of people downloaded it :)
            $response->last_updated = '2018-04-17';
            $response->added = '2017-02-01';
            $response->homepage = "http://localhost/projects/wp-autoupdate-repository/";
            $response->sections = array(
                'description' =>  'Add a description of your plugin 2',
                'changelog' =>  'Add a list of changes to your plugin 2',
                'screenshots' => 'screenshots 2',
                'faq' => 'faq 2',
            );
            $response->download_link = 'http://localhost/projects/wp-autoupdate-repository/'.$response->slug.'.zip';
        }
        break;
    
}

echo serialize( $response );