<?php

namespace App\Entity;

use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "comments")]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private int $comment_id;

    #[ORM\Column(type: "string", length: 500)]
    private string $content;

    #[ORM\ManyToOne(targetEntity: "Trick")]
    #[ORM\JoinColumn(name: "trick_id", referencedColumnName: "trick_id")]
    private Trick $trick;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id", nullable: false)]
    private User $user;

    public function getCommentId(): int
    {
        return $this->comment_id;
    }

    public function setCommentId(int $comment_id): void
    {
        $this->comment_id = $comment_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function setTrick(Trick $trick): void
    {
        $this->trick = $trick;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
