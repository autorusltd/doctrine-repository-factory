<?php declare(strict_types=1);

namespace Arus\Doctrine\RepositoryFactory\Tests\Resources;

/**
 * Import classes
 */
use Doctrine\ORM\EntityRepository;

/**
 * RepositoryTest
 */
class RepositoryTest extends EntityRepository
{

    /**
     * @Inject("foo")
     *
     * @var string
     */
    public $foo;

    /**
     * @Inject("bar")
     *
     * @var string
     */
    public $bar;
}
