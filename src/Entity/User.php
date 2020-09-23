<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class User
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @Vich\Uploadable()
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     * @Groups({"user", "auth-token"})
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"user", "auth-token"})
     */
    private $username;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"user", "auth-token"})
     */
    private $firstname;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"user", "auth-token"})
     */
    private $lastname;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"user", "auth-token"})
     */
    private $address;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $password;

//    /**
//     * @var string|null
//     * @ORM\Column(type="string", length=255)
//     * @Assert\NotBlank()
//     * @Assert\Email()
//     * @Groups({"user", "auth-token"})
//     */
//    private $email;

    private $plainPassword;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user", "auth-token"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user", "auth-token"})
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Formation", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"user", "auth-token", "formation"})
     */
    private $formations;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Experience", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"user", "auth-token", "experience"})
     */
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Competance", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"user", "auth-token", "competance"})
     */
    private $competances;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user", "auth-token"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user", "auth-token"})
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user", "auth-token"})
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="users_images", fileNameProperty="filename")
     */
    private $imageFile;

    public function __construct()
    {
        $this->created_at  = new \DateTime();
        $this->updated_at  = new \DateTime();
        $this->formations  = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->competances = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(string $role): self
    {
        $roles = $this->roles;
        $roles[] = $role;
        $this->roles = array_unique($roles);

        return $this;
    }

    public function getPassword(): ?String
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function eraseCredentials()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * @param Formation $formation
     * @return $this
     */
    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setUser($this);
        }

        return $this;
    }

    /**
     * @param Formation $formation
     * @return $this
     */
    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            // set the owning side to null (unless already changed)
            if ($formation->getUser() === $this) {
                $formation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    /**
     * @param Experience $experience
     * @return $this
     */
    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    /**
     * @param Experience $experience
     * @return $this
     */
    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->contains($experience)) {
            $this->experiences->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCompetances(): Collection
    {
        return $this->competances;
    }

    /**
     * @param Competance $competance
     * @return $this
     */
    public function addCompetance(Competance $competance): self
    {
        if (!$this->competances->contains($competance)) {
            $this->competances[] = $competance;
            $competance->setUser($this);
        }

        return $this;
    }

    /**
     * @param Competance $competance
     * @return $this
     */
    public function removeCompetance(Competance $competance): self
    {
        if ($this->competances->contains($competance)) {
            $this->competances->removeElement($competance);
            // set the owning side to null (unless already changed)
            if ($competance->getUser() === $this) {
                $competance->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getlastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setlastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Property
     */
    public function setImageFile(?File $imageFile): User
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
