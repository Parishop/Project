<?php
namespace Project;

/**
 * Class App
 * @package Parishop
 * @since   1.0
 */
class App extends \PHPixie\DefaultBundle
{
    /**
     * @var App\Builder
     * @since 1.0
     */
    protected $builder;

    /**
     * @param Framework\Builder $frameworkBuilder
     * @return App\Builder
     * @since 1.0
     */
    protected function buildBuilder($frameworkBuilder)
    {
        return new App\Builder($frameworkBuilder);
    }

}

