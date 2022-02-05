<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
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
     * @ORM\OneToMany(targetEntity="App\Entity\Zip", mappedBy="country")
     */
    private $zips;

    public function __construct()
    {
        $this->zips = new ArrayCollection();
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
     * @return Collection|Zip[]
     */
    public function getZips(): Collection
    {
        return $this->zips;
    }

    public function addZip(Zip $zip): self
    {
        if (!$this->zips->contains($zip)) {
            $this->zips[] = $zip;
            $zip->setCountry($this);
        }

        return $this;
    }

    public function removeZip(Zip $zip): self
    {
        if ($this->zips->contains($zip)) {
            $this->zips->removeElement($zip);
            // set the owning side to null (unless already changed)
            if ($zip->getCountry() === $this) {
                $zip->setCountry(null);
            }
        }

        return $this;
    }
}
