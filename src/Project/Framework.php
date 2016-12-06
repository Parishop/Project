<?php
namespace Project;

/**
 * Class Framework
 * @package Project
 * @since   1.0
 */
class Framework extends \PHPixie\BundleFramework
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
        parent::__construct();
    }

    /**
     * @return Framework\Builder
     * @since 1.0
     */
    protected function buildBuilder()
    {
        return new Framework\Builder();
    }

}

