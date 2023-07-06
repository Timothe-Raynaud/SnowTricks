<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="video")
 **/
class Videos
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
     * @ORM\Column(type="string", length=255)
     */
    private string $url;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

}
