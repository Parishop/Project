<?php
namespace Project\Framework;

/**
 * Class Assets
 * @package Parishop\Framework
 * @since   1.0
 */
class Assets extends \PHPixie\BundleFramework\Assets
{
    /**
     * @var string
     * @since 1.0
     */
    protected $indexDirectory;

    /**
     * @param \PHPixie\BundleFramework\Components $components
     * @param string                              $rootDirectory
     * @param string                              $indexDirectory
     * @since 1.0
     */
    public function __construct($components, $rootDirectory, $indexDirectory = 'web')
    {
        $this->indexDirectory = $indexDirectory;
        parent::__construct($components, $rootDirectory);
    }

    /**
     * @return \PHPixie\Filesystem\Root
     * @since 1.0
     */
    protected function buildWebRoot()
    {
        return $this->buildFilesystemRoot(
            $this->root()->path($this->indexDirectory)
        );
    }

}
