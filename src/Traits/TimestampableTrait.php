<?php

namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{

    /**
     * @var datetime $createdAt
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @var datetime $updatedAt
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;


    /**
     * @return datetime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param datetime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt = new DateTime('now')): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt = new DateTime('now')): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
