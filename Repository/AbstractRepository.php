<?php

namespace Repository;

use Service\PdoConnection;
use Traits\Singleton;

abstract class AbstractRepository
{
    use Singleton;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * AbstractRepository constructor.
     * @param \PDO $pdo
     */
    protected function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return static
     */
    protected static function buildInstance()
    {
        return new static(PdoConnection::getInstance());
    }

    /**
     * @param $sql
     * @return \PDOStatement
     */
    public function getQueryBuilder($sql)
    {
        return $this->pdo->prepare($sql);
    }
}