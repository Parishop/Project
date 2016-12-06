<?php
namespace Project\Framework;

/**
 * Class Bundles
 * @package Parishop\Framework
 * @since   1.0
 */
class Bundles extends \PHPixie\BundleFramework\Bundles
{
    /**
     * Список бандлов
     * @return array
     * @since 1.0
     */
    protected function buildBundles()
    {
        return array(
            new \Project\App($this->builder),
        );
    }

}

