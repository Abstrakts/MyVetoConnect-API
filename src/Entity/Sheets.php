<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SheetsRepository")
 */
class Sheets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $treatments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $allergies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $vaccines;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastVisit;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animales", mappedBy="sheet")
     */
    private $animales;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    

    public function __construct()
    {
        $this->animales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getTreatments(): ?string
    {
        return $this->treatments;
    }

    public function setTreatments(?string $treatments): self
    {
        $this->treatments = $treatments;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getVaccines(): ?string
    {
        return $this->vaccines;
    }

    public function setVaccines(?string $vaccines): self
    {
        $this->vaccines = $vaccines;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLastVisit(): ?string
    {
        return $this->lastVisit;
    }

    public function setLastVisit(?string $lastVisit): self
    {
        $this->lastVisit = $lastVisit;

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
            $animale->setSheet($this);
        }

        return $this;
    }

    public function removeAnimale(Animales $animale): self
    {
        if ($this->animales->contains($animale)) {
            $this->animales->removeElement($animale);
            // set the owning side to null (unless already changed)
            if ($animale->getSheet() === $this) {
                $animale->setSheet(null);
            }
        }

        return $this;
    }

}
