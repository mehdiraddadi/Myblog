<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"exprience", "auth-token", "user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"exprience", "auth-token", "user"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"exprience", "auth-token", "user"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"exprience", "auth-token", "user"})
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"exprience", "auth-token", "user"})
     */
    private $end_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="experiences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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
