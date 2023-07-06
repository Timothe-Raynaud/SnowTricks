<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity()
 * @ORM\Table(name="images")
 **/
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tricks")
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="trick_id", nullable=true)
     */
    private ?Tricks $trick;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $filename;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $isMain;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Tricks
     */
    public function getTrick(): Tricks
    {
        return $this->trick;
    }

    /**
     * @param Tricks|null $trick
     */
    public function setTrick(?Tricks $trick): void
    {
        $this->trick = $trick;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->isMain;
    }

    /**
     * @param bool $isMain
     */
    public function setIsMain(bool $isMain = false): void
    {
        $this->isMain = $isMain;
    }
}
