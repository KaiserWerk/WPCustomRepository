<?php

require __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

// .env Configuration
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

function projectDir($echo = false) {
    if (!$echo) {
        return '/';
    }
    echo '/';
}
function fileDir($echo = false) {
    if (!$echo) {
        return 'public';
    }
    echo 'public';
}
function tempDir($echo = false) {
    if (!$echo) {
        return 'var';
    }
    echo  'var';
}
function viewsDir($echo = false) {
    if ( ! $echo ) {
        return __DIR__.'/resources/views';
    }
    echo  __DIR__.'/resources/views';
}
function downloadDir($echo = false) {
    if ( ! $echo ) {
        return 'plugin_files';
    }
    echo 'plugin_files';
}