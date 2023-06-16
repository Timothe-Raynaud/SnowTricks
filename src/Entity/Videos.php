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
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="trick_id")
     */
    private Tricks $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Tricks
     */
    public function getType(): Tricks
    {
        return $this->type;
    }

    /**
     * @param Tricks $type
     */
    public function setType(Tricks $type): void
    {
        $this->type = $type;
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

}
