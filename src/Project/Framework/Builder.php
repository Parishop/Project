<?php
namespace Project\Framework;

/**
 * Class Builder
 * @method Assets assets()
 * @package Parishop\Framework
 * @since   1.0
 */
class Builder extends \PHPixie\BundleFramework\Builder
{
    /**
     * @var string
     * @since 1.0
     */
    protected $indexDirectory;

    /**
     * @param string $indexDirectory
     * @since 1.0
     */
    public function __construct($indexDirectory = 'web')
    {
        $this->indexDirectory = $indexDirectory;
    }

    /**
     * @return Assets
     * @since 1.0
     */
    protected function buildAssets()
    {
        return new Assets(
            $this->components(),
            $this->getRootDirectory(),
            $this->indexDirectory
        );
    }

    /**
     * @return Bundles
     * @since 1.0
     */
    protected function buildBundles()
    {
        return new Bundles($this);
    }

    /**
     * @return Extensions
     * @since 1.0
     */
    protected function buildExtensions()
    {
        return new Extensions($this);
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

