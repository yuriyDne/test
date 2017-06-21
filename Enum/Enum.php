<?php

namespace Enum;

abstract class Enum
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $constantList;

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw  new \InvalidArgumentException('argument value must be string');
        }

        $constantList = $this->getConstantList();
        if (!isset($constantList[$value])) {
            throw  new \OutOfBoundsException('invalid action: '.$value);
        }

        $this->value = $value;
    }

    /**
     * @return array
     */
    protected function getConstantList()
    {
        if (is_null($this->constantList)) {
            $reflectionClass = new \ReflectionClass($this);
            $this->constantList =  array_flip($reflectionClass->getConstants());
        }

        return $this->constantList;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}