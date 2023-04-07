<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="types_tricks")
 **/
class TypesTricks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $type_trick_id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->type_trick_id;
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
}
