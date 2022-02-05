<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalesRepository")
 */
class Animales
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
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $race;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sterillised;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $particularities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="animales")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Color", inversedBy="animales")
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="animales")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EyeColor", inversedBy="animales")
     */
    private $eyeColor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Veterinaries", inversedBy="animales")
     */
    private $veterinary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sheets", inversedBy="animales")
     */
    private $sheet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $qrcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $birthday;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getSterillised(): ?bool
    {
        return $this->sterillised;
    }

    public function setSterillised(?bool $sterillised): self
    {
        $this->sterillised = $sterillised;

        return $this;
    }

    public function getParticularities(): ?string
    {
        return $this->particularities;
    }

    public function setParticularities(?string $particularities): self
    {
        $this->particularities = $particularities;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEyeColor(): ?EyeColor
    {
        return $this->eyeColor;
    }

    public function setEyeColor(?EyeColor $eyeColor): self
    {
        $this->eyeColor = $eyeColor;

        return $this;
    }

    public function getVeterinary(): ?Veterinaries
    {
        return $this->veterinary;
    }

    public function setVeterinary(?Veterinaries $veterinary): self
    {
        $this->veterinary = $veterinary;

        return $this;
    }

    public function getSheet(): ?Sheets
    {
        return $this->sheet;
    }

    public function setSheet(?Sheets $sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function getChip(): ?string
    {
        return $this->chip;
    }

    public function setChip(?string $chip): self
    {
        $this->chip = $chip;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(string $qrcode): self
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }
}
