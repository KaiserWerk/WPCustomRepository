<?php

use Medoo\Medoo;

class DBHelper extends Medoo
{
    public function __construct()
    {
        global $config;
        if ($config['database']['driver'] === 'mysql') {
            parent::__construct([
                'type' => 'mysql',
                'host' => $config['database']['host'],
                'database' => $config['database']['dbname'],
                'username' => $config['database']['username'],
                'password' => $config['database']['password'],
                'prefix' => $config['database']['prefix'],
                'port' => $config['database']['port'],
                'charset' => $config['database']['charset'],
                /*
                 * 'type' => 'mysql',
                    'host' => 'localhost',
                    'database' => 'name',
                    'username' => 'your_username',
                    'password' => 'your_password',

                 */
            ]);
        } elseif ($config['database']['driver'] === 'sqlite') {
            parent::__construct([
                'type' => 'sqlite',
                'database_file' => $config['database']['dbfile'],
            ]);
        }
    }
}
