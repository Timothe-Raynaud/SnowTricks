<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);

        $this->em = $this->getEntityManager();
    }

    public function save(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCommentsByTrick(int $trickId, int $limit, ?int $startingId) : array
    {
        $where = '';
        if ($startingId !== null){
            $where = 'AND c.comments_id <= :startingId';
        }

        $sql = "
            SELECT c.comments_id
                , c.content
                , c.created_at
                , c.updated_at
                , u.username
            FROM comments c 
            INNER JOIN user u ON u.user_id = c.user_id
            WHERE c.trick_id = :trickId
            {$where}
            ORDER BY c.comments_id DESC
            LIMIT :limit
        ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('comments_id', 'commentId', 'integer');
        $rsm->addScalarResult('content', 'content');
        $rsm->addScalarResult('created_at', 'createdAt');
        $rsm->addScalarResult('updated_at', 'updatedAt');
        $rsm->addScalarResult('username', 'username');

        $query = $this->em->createNativeQuery($sql, $rsm)
            ->setParameter(':startingId', $startingId)
            ->setParameter(':trickId', $trickId)
            ->setParameter(':limit', $limit);

        return $query->getResult();
    }

}
