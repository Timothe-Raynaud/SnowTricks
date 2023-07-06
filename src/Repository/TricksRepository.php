<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tricks>
 *
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricks::class);
        $this->em = $this->getEntityManager();
    }

    public function save(Tricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tricks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllTricksWithType() : array
    {
        $sql = "
            SELECT t.trick_id
                , t.description
                , t.name
                , tt.name AS type
                , subquery_image.image 
            FROM tricks t 
            INNER JOIN types_tricks tt ON tt.type_trick_id = t.type_trick_id
            LEFT JOIN (
                SELECT i.filename as image
                    , i.trick_id
                FROM images i 
                WHERE i.is_main = true
            ) subquery_image ON subquery_image.trick_id = t.trick_id
        ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('trick_id', 'trickId', 'integer');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('image', 'image');

        return $this->em->createNativeQuery($sql, $rsm)->getResult();
    }

}
