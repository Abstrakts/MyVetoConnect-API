<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 */
class Color
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animales", mappedBy="color")
     */
    private $animales;

    public function __construct()
    {
        $this->animales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Animales[]
     */
    public function getAnimales(): Collection
    {
        return $this->animales;
    }

    public function addAnimale(Animales $animale): self
    {
        if (!$this->animales->contains($animale)) {
            $this->animales[] = $animale;
            $animale->setColor($this);
        }

        return $this;
    }

    public function removeAnimale(Animales $animale): self
    {
        if ($this->animales->contains($animale)) {
            $this->animales->removeElement($animale);
            // set the owning side to null (unless already changed)
            if ($animale->getColor() === $this) {
                $animale->setColor(null);
            }
        }

        return $this;
    }
}
