<?php

namespace App\Entity;

use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity()
 * @ORM\Table(name="tricks")
 * @ORM\HasLifecycleCallbacks
 **/
class Trick
{
    use TimestampableTrait;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", length=100)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity="TypeTricks")
     * @ORM\JoinColumn(name="type_trick_id", referencedColumnName="type_trick_id")
     */
    private TypeTricks $type;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="trick", cascade={"persist", "remove"})
     * @Groups({"exclude_from_serialization"})
     */
    private ?Collection $images;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="trick", cascade={"persist", "remove"})
     */
    private ?Collection $videos;

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

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(): void
    {
        $this->slug = strtolower(str_replace(' ', '-', $this->name));
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

    public function getType(): ?TypeTricks
    {
        return $this->type;
    }

    public function setType(TypeTricks $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTrickId(): ?int
    {
        return $this->trick_id;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

}