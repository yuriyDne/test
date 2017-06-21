<?php
namespace Service;

use Config\Constants;
use Traits\Singleton;

class PdoConnection
{
    use Singleton;

    /**
     * @return \PDO
     */
    protected static function buildInstance()
    {
        $connectionString = sprintf(
            'mysql:host=%s;dbname=%s',
            Constants::MYSQL_HOST,
            Constants::MYSQL_DB_NAME
        );
        return new \PDO(
            $connectionString,
            Constants::MYSQL_USER,
            Constants::MYSQL_PASSWORD
        );
    }

}