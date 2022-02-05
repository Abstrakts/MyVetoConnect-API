<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompaniesRepository")
 */
class Companies
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bossName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="companies")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siret;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Veterinaries", mappedBy="company")
     */
    private $veterinaries;

    public function __construct()
    {
        $this->veterinaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBossName(): ?string
    {
        return $this->bossName;
    }

    public function setBossName(?string $bossName): self
    {
        $this->bossName = $bossName;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection|Veterinaries[]
     */
    public function getVeterinaries(): Collection
    {
        return $this->veterinaries;
    }

    public function addVeterinary(Veterinaries $veterinary): self
    {
        if (!$this->veterinaries->contains($veterinary)) {
            $this->veterinaries[] = $veterinary;
            $veterinary->setCompany($this);
        }

        return $this;
    }

    public function removeVeterinary(Veterinaries $veterinary): self
    {
        if ($this->veterinaries->contains($veterinary)) {
            $this->veterinaries->removeElement($veterinary);
            // set the owning side to null (unless already changed)
            if ($veterinary->getCompany() === $this) {
                $veterinary->setCompany(null);
            }
        }

        return $this;
    }

    
}
