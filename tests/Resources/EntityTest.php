<?php declare(strict_types=1);

namespace Arus\Doctrine\RepositoryFactory\Tests\Resources;

/**
 * Import classes
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(
 *   repositoryClass="Arus\Doctrine\RepositoryFactory\Tests\Resources\RepositoryTest"
 * )
 */
class EntityTest
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     *
     * @var null|int
     */
    public $id;
}
