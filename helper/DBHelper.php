<?php

use Medoo\Medoo;

class DBHelper extends Medoo
{
    public function __construct()
    {
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
    }

    public function hasError()
    {
        if (!empty($this->error()) && $this->error()[0] !== '00000') {
            return true;
        }

        return false;
    }
}
