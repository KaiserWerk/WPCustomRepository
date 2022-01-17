<?php

const PROJECTDIR = __DIR__;
const TEMPLATEDIR = __DIR__ . '/app/Resources/templates';
const TRANSLATIONDIR = __DIR__ . '/app/Resources/translations';
const CONTROLLERDIR = __DIR__ . '/app/Controller';
const HELPERDIR = __DIR__ . '/app/Helper';
const MODELDIR = __DIR__ . '/app/Model';
const VARDIR = __DIR__ . '/var';

// require the comments check
//require_once __DIR__ . '/bin/requirements_check.php';
// require the autoloader
require_once __DIR__ . '/vendor/autoload.php';

try {
    $configFile = PROJECTDIR . '/config.yml';
    if (!file_exists($configFile)) {
        die('Configuration file ' . $configFile . ' does not exists! Execute composer install first.');
    }
    $config = Symfony\Component\Yaml\Yaml::parseFile($configFile);
} catch (Symfony\Component\Yaml\Exception\ParseException $exception) {
    trigger_error('ParseException: Could not load config file ' . $configFile . ': ' . $exception->getMessage());
    die;
}

// Display errors depending on environment
if ($config['site']['env'] == 'dev') {
    $displayErrors = 'On';
} else {
    $displayErrors = 'Off';
}
ini_set('display_errors', $displayErrors);
// always log errors
ini_set('log_errors', true);
// the error log file path
ini_set('error_log', VARDIR . '/logs/php-errors.log');
// to be on the safe side, set save handler to files (maybe use redis?)
ini_set('session.save_handler', 'files');
// set the session temp dir to inside the project dir
ini_set('session.save_path', VARDIR . '/sessions/');
// important for debian based systems if you set a custom session save path
ini_set('session.gc_probability', 1);
// set the session name
ini_set('session.name', $config['session']['name']);

