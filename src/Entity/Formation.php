<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"formation", "auth-token", "user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"formation", "auth-token", "user"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"formation", "auth-token", "user"})
     */
    private $establishment;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"formation", "auth-token", "user"})
     */
    private $dateObtained;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getEstablishment(): ?string
    {
        return $this->establishment;
    }

    public function setEstablishment(string $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    public function getDateObtained(): ?\DateTimeInterface
    {
        return $this->dateObtained;
    }

    public function setDateObtained(\DateTimeInterface $dateObtained): self
    {
        $this->dateObtained = $dateObtained;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
