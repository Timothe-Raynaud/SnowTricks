<?php

namespace App\Repository;

use App\Entity\TypeTricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeTricks>
 *
 * @method TypeTricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTricks[]    findAll()
 * @method TypeTricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypesTricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeTricks::class);
    }

    public function save(TypeTricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeTricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
