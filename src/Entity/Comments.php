<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 **/
class Comments
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $comments_id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="Tricks")
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="trick_id")
     */
    private Tricks $trick;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private User $user;

    public function setId(int $comments_id): void
    {
        $this->comments_id = $comments_id;
    }

    public function getId(): int
    {
        return $this->comments_id;
    }

    public function getcontent(): string
    {
        return $this->content;
    }

    public function setcontent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
