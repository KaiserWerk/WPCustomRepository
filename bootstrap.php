<?php

require __DIR__ . '/vendor/autoload.php';

$router = new \Klein\Klein();

try {
// .env Configuration
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
} catch (Exception $exception) {
    trigger_error('Could not load .env file (not found or erroneous).');
}

function projectDir($echo = false) {
    if (!$echo) {
        return __DIR__;
    }
    echo __DIR__;
}
function publicDir($echo = false) {
    if (!$echo) {
        return __DIR__.'/public';
    }
    echo __DIR__.'/public';
}
function tempDir($echo = false) {
    if (!$echo) {
        return __DIR__.'/var';
    }
    echo  __DIR__.'/var';
}
function viewsDir($echo = false) {
    if ( ! $echo ) {
        return __DIR__.'/resources/views';
    }
    echo  __DIR__.'/resources/views';
}

function archiveDir($echo = false) {
    if ( ! $echo ) {
        return tempDir() . '/plugin_archive';
    }
    echo tempDir() . '/plugin_archive';
}

ini_set('log_errors', true);
ini_set('error_log', tempDir() . '/logs/php-errors.log');
ini_set('session.save_handler', 'files');
ini_set('session.save_path', tempDir() . '/sessions/');
/** important for debian systems if you set a custom session save path */
ini_set('session.gc_probability', 1);
ini_set('session.name', getenv('SESSNAME'));
if ((bool)getenv('DEBUG') === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
} else {
    error_reporting(0);
    ini_set('display_errors', false);
}
