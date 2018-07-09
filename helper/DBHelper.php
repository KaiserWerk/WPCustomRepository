<?php

use Medoo\Medoo;

class DBHelper extends Medoo
{
    public function __construct()
    {
        if (getenv('DB_DRIVER') === 'mysql') {
            parent::__construct([
                'database_type' => getenv('DB_DRIVER'),
                'server' => getenv('DB_HOST'),
                'database_name' => getenv('DB_NAME'),
                'username' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'prefix' => getenv('DB_PREFIX'),
                'port' => getenv('DB_PORT'),
                'charset' => getenv('DB_CHARSET'),
            ]);
        } elseif (getenv('DB_DRIVER') === 'sqlite') {
            parent::__construct([
                'database_type' => getenv('DB_DRIVER'),
                'database_file' => getenv('DB_FILE'),
            ]);
        }
    }

    public function hasError()
    {
        return !empty($this->error()) && $this->error()[0] !== '00000';
    }
}
