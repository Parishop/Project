<?php
namespace Project\App;

/**
 * Class AuthRepositories
 * @package Parishop\App
 * @since   1.0
 */
class AuthRepositories extends \PHPixie\Auth\Repositories\Registry\Builder
{
    /**
     * @var \PHPixie\ORM
     * @since 1.0
     */
    protected $orm;

    /**
     * @param \PHPixie\ORM $orm
     * @since 1.0
     */
    public function __construct(\PHPixie\ORM $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @return \Parishop\Wrappers\User\Repository
     * @since 1.0
     */
    protected function buildUserRepository()
    {
        return $this->orm->repository('user');
    }

}

