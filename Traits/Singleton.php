<?php

namespace Traits;

trait Singleton
{
    protected static $instance;

    protected static function buildInstance()
    {
        throw new \LogicException('Must be realized in child class');
    }

    protected function __construct() {}
    private function __clone() {}

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = self::buildInstance();
        }

        return self::$instance;
    }
}