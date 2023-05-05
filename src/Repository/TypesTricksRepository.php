<?php

namespace App\Repository;

use App\Entity\TypesTricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypesTricks>
 *
 * @method TypesTricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypesTricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypesTricks[]    findAll()
 * @method TypesTricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypesTricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypesTricks::class);
    }

    public function save(TypesTricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypesTricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
