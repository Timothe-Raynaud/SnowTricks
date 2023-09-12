<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);

        $this->em = $this->getEntityManager();
    }

    public function save(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTricksWithType(int $limit, ?int $startingId) : array
    {
        $where = '';
        if ($startingId !== null){
            $where = 'AND t.trick_id <= :startingId';
        }

        $sql = "
            SELECT t.trick_id
                , t.description
                , t.name
                , t.slug
                , tt.name AS type
                , subquery_image.image 
            FROM tricks t 
            INNER JOIN types_tricks tt ON tt.type_trick_id = t.type_trick_id
            LEFT JOIN (
                SELECT i.filename as image
                    , i.trick_id
                FROM images i
                ORDER BY i.id
                LIMIT 1
            ) subquery_image ON subquery_image.trick_id = t.trick_id
            WHERE 1
            {$where}
            ORDER BY t.trick_id DESC
            LIMIT :limit
        ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('trick_id', 'trickId', 'integer');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('image', 'image');

        $query = $this->em->createNativeQuery($sql, $rsm)
            ->setParameter(':startingId', $startingId)
            ->setParameter(':limit', $limit);

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findTrickBySlugWithMedia(string $slug) : ?Trick
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'i', 'v')
            ->leftJoin('t.images', 'i')
            ->leftJoin('t.videos', 'v')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
