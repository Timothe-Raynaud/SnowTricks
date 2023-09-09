<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

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
    private ParameterBagInterface $parameterBag;
    private Filesystem $filesystem;

    public function __construct(ManagerRegistry $registry, Filesystem $filesystem, ParameterBagInterface $parameterBag)
    {
        parent::__construct($registry, Trick::class);

        $this->em = $this->getEntityManager();
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
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
                ORDER BY i.id ASC
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
    public function getTrickContentBySlug(string $slug) : ?array
    {
        $sql = "
            SELECT t.trick_id
                , t.description
                , t.name
                , t.slug
                , tt.name AS type
            FROM tricks t 
            INNER JOIN types_tricks tt ON tt.type_trick_id = t.type_trick_id
            WHERE slug = :slug
        ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('trick_id', 'trickId', 'integer');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('type', 'type');

        $query = $this->em->createNativeQuery($sql, $rsm)
            ->setParameter(':slug', $slug);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findTrickBySlugWithMedia(string $slug) : Trick
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
