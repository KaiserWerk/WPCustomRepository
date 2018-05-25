<?php

require __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

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
function fileDir($echo = false) {
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
function downloadDir($echo = false) {
    if ( ! $echo ) {
        return tempDir() . '/plugin_files';
    }
    echo tempDir() . '/plugin_files';
}
function archiveDir($echo = false) {
    if ( ! $echo ) {
        return tempDir() . '/plugin_archive';
    }
    echo tempDir() . '/plugin_archive';
}