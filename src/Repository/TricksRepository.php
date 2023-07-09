<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Tricks;
use App\Entity\Videos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

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
    public function __construct(ManagerRegistry $registry, Filesystem $filesystem, ParameterBagInterface $parameterBag)
    {
        parent::__construct($registry, Tricks::class);

        $this->em = $this->getEntityManager();
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
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
                , t.slug
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
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('image', 'image');

        return $this->em->createNativeQuery($sql, $rsm)->getResult();
    }

    public function addNewFromForm(Tricks $trick) : bool
    {
        $imageTemporary = $this->parameterBag->get('images_temporary');
        $imageDirectory = $this->parameterBag->get('images_directory');
        $newTrick = new Tricks();
        $newTrick->setType($trick->getType())
            ->setName($trick->getName())
            ->setDescription($trick->getDescription())
            ->setSlug(strtolower(str_replace(' ', '-', $trick->getName())));

        $this->em->persist($newTrick);
        $this->em->flush();

        $isMain = True;
        foreach ($trick->getImages() as $image){
            $newImage = new Images();
            $newImage->setFilename($image);
            $newImage->setTrick($newTrick);
            $newImage->setIsMain($isMain);
            $this->em->persist($newImage);
            $isMain = False;

            $this->filesystem->rename($imageTemporary.$image, $imageDirectory. '/tricks/' .$image);
        }

        foreach ($trick->getVideos() as $video){
            $newVideo = new Videos();
            $newVideo->setUrl($video);
            $newVideo->setTrick($newTrick);
            $this->em->persist($newVideo);
        }

        $this->em->flush();
        $this->filesystem->remove($imageTemporary.'/');

        return True;
    }

}
