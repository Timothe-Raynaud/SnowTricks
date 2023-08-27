<?php

namespace App\Entity;

use App\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 * @ORM\HasLifecycleCallbacks
 **/
class Comments
{
    use TimestampableTrait;

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

    /**
     * @return int
     */
    public function getCommentsId(): int
    {
        return $this->comments_id;
    }

    /**
     * @param int $comments_id
     */
    public function setCommentsId(int $comments_id): void
    {
        $this->comments_id = $comments_id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Tricks
     */
    public function getTrick(): Tricks
    {
        return $this->trick;
    }

    /**
     * @param Tricks $trick
     */
    public function setTrick(Tricks $trick): void
    {
        $this->trick = $trick;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
