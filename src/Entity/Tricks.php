<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\TypesTricks;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tricks")
 **/
class Tricks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $trick_id = null;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypesTricks")
     * @ORM\JoinColumn(name="type_trick_id", referencedColumnName="type_trick_id")
     */
    private TypesTricks $type;

    /**
     * @param int|null $trick_id
     */
    public function setId(?int $trick_id): void
    {
        $this->trick_id = $trick_id;
    }

    public function getId(): int
    {
        return $this->trick_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?TypesTricks
    {
        return $this->type;
    }

    public function setType(TypesTricks $type): self
    {
        $this->type = $type;

        return $this;
    }
}
