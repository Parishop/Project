<?php
namespace Project\App;

/**
 * Class ORMWrappers
 * @package Parishop\App
 * @since   1.0
 */
class ORMWrappers extends \Parishop\ORMWrappers
{
    /**
     * @return string
     * @since 1.0
     */
    protected function path()
    {
        return __DIR__;
    }

}