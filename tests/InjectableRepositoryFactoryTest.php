<?php declare(strict_types=1);

namespace Arus\Doctrine\RepositoryFactory\Tests;

/**
 * Import classes
 */
use Arus\Doctrine\RepositoryFactory\InjectableRepositoryFactory;
use Arus\Doctrine\RepositoryFactory\Tests\Resources\EntityTest;
use Arus\Doctrine\RepositoryFactory\Tests\Resources\RepositoryTest;
use DI\Container;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use PHPUnit\Framework\TestCase;

/**
 * InjectableRepositoryFactoryTest
 */
class InjectableRepositoryFactoryTest extends TestCase
{

    /**
     * @var null|Container
     */
    private $container;

    /**
     * @return void
     */
    public function testConstructor() : void
    {
        $factory = new InjectableRepositoryFactory($this->container);

        $this->assertInstanceOf(RepositoryFactory::class, $factory);
    }

    /**
     * @return void
     */
    public function testGetRepository() : void
    {
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $this->container->set('foo', 'bar');
        $this->container->set('bar', 'baz');
        $repository = $entityManager->getRepository(EntityTest::class);

        $this->assertInstanceOf(RepositoryTest::class, $repository);
        $this->assertEquals($this->container->get('foo'), $repository->foo);
        $this->assertEquals($this->container->get('bar'), $repository->bar);
    }

    /**
     * @return void
     */
    public function testGetRepositoryRepeatedly() : void
    {
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $this->container->set('foo', 'bar');
        $this->container->set('bar', 'baz');
        $first = $entityManager->getRepository(EntityTest::class);

        $this->container->set('foo', 'baz');
        $this->container->set('bar', 'qux');
        $second = $entityManager->getRepository(EntityTest::class);

        $this->assertSame($first, $second);
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        $builder->useAutowiring(false);

        $this->container = $builder->build();

        $this->container->set(EntityManagerInterface::class, function (Container $container) : EntityManagerInterface {
            $config = DoctrineSetup::createAnnotationMetadataConfiguration([__DIR__], true, null, null, false);
            $config->setRepositoryFactory(new InjectableRepositoryFactory($container));

            // See the file "phpunit.xml.dist" in the package root
            return EntityManager::create(['url' => $_ENV['DATABASE_URL']], $config);
        });
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        if ($this->container->has(EntityManagerInterface::class)) {
            $entityManager = $this->container->get(EntityManagerInterface::class);
            $entityManager->getConnection()->close();
            $entityManager->close();
        }

        $this->container = null;
    }
}
