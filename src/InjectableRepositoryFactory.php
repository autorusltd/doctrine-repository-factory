<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2019, Autorus Ltd.
 * @license https://github.com/autorusltd/doctrine-repository-factory/blob/master/LICENSE
 * @link https://github.com/autorusltd/doctrine-repository-factory
 */

namespace Arus\Doctrine\RepositoryFactory;

/**
 * Import classes
 */
use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;

/**
 * Import functions
 */
use function spl_object_hash;

/**
 * InjectableRepositoryFactory
 */
final class InjectableRepositoryFactory implements RepositoryFactory
{

    /**
     * The dependency injection container
     *
     * @var Container
     */
    private $container;

    /**
     * The list of EntityRepository instances
     *
     * @var \Doctrine\Common\Persistence\ObjectRepository[]
     */
    private $repositories = [];

    /**
     * Constructor of the class
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $entityMetadata = $entityManager->getClassMetadata($entityName);
        $repositoryHash = $entityMetadata->getName() . spl_object_hash($entityManager);

        if (isset($this->repositories[$repositoryHash])) {
            return $this->repositories[$repositoryHash];
        }

        $customRepositoryClassName = $entityMetadata->customRepositoryClassName;
        $defaultRepositoryClassName = $entityManager->getConfiguration()->getDefaultRepositoryClassName();
        $determinedRepositoryClassName = $customRepositoryClassName ?: $defaultRepositoryClassName;

        $this->repositories[$repositoryHash] = new $determinedRepositoryClassName($entityManager, $entityMetadata);
        $this->container->injectOn($this->repositories[$repositoryHash]);

        return $this->repositories[$repositoryHash];
    }
}
