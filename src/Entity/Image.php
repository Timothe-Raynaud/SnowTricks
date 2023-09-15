<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "images")]
#[ORM\HasLifecycleCallbacks]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: "Trick", cascade: ["persist"], inversedBy: "images")]
    #[ORM\JoinColumn(name: "trick_id", referencedColumnName: "trick_id", nullable: false)]
    private ?Trick $trick;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $filename = null;

    /**
     * @Assert\Image
     */
    private ?UploadedFile $file = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    #[ORM\PreRemove]
    public function removeImageFile(): void
    {
        $filePath = 'uploads/images/tricks/' . $this->getFilename();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
