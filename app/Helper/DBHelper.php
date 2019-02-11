<?php

use Medoo\Medoo;

class DBHelper extends Medoo
{
    public function __construct()
    {
        global $config;
        if ($config['database']['driver'] === 'mysql') {
            parent::__construct([
                'database_type' => 'mysql',
                'server' => $config['database']['host'],
                'database_name' => $config['database']['dbname'],
                'username' => $config['database']['username'],
                'password' => $config['database']['password'],
                'prefix' => $config['database']['prefix'],
                'port' => $config['database']['port'],
                'charset' => $config['database']['charset'],
            ]);
        } elseif ($config['database']['driver'] === 'sqlite') {
            parent::__construct([
                'database_type' => 'sqlite',
                'database_file' => $config['database']['dbfile'],
            ]);
        }
    }
}
