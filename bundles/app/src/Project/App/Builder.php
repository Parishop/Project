<?php
namespace Project\App;

/**
 * Class Builder
 * @package Parishop\App
 * @since   1.0
 */
class Builder extends \PHPixie\DefaultBundle\Builder
{
    /**
     * @return string
     * @since 1.0
     */
    public function bundleName()
    {
        return 'app';
    }

    /**
     * @return AuthRepositories
     * @since 1.0
     */
    protected function buildAuthRepositories()
    {
        return new AuthRepositories($this->components()->orm());
    }

    /**
     * @return HTTPProcessors
     * @since 1.0
     */
    protected function buildHttpProcessor()
    {
        return new HTTPProcessors($this);
    }

    /**
     * @return ORMWrappers
     * @since 1.0
     */
    protected function buildORMWrappers()
    {
        return new ORMWrappers($this);
    }

    /**
     * @return string
     * @since 1.0
     */
    protected function getRootDirectory()
    {
        return realpath(__DIR__ . '/../../../');
    }

}

