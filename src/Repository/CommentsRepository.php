<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);

        $this->em = $this->getEntityManager();
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCommentsByTrick(int $trickId, int $limit, ?int $startingId) : array
    {
        $queryBuilder = $this->createQueryBuilder('c')
        ->select('c.comment_id, c.content, c.createdAt, c.updatedAt, u.username')
        ->innerJoin('c.user', 'u')
        ->where('c.trick = :trickId')
        ->orderBy('c.comment_id', 'DESC')
            ->setParameter('trickId', $trickId)
            ->setMaxResults($limit);

        if ($startingId !== null) {
            $queryBuilder->andWhere('c.commentId <= :startingId')
                ->setParameter('startingId', $startingId);
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
