<?php

namespace App\Entity;

use Serializable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @Assert\Uuid()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="6",
     *     max="20",
     *     minMessage="Votre nom d'utilisateur doit contenir 6 caracteres minimum"
     *)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type("datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="author")
     */
    private $tasks;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var
     * @Assert\Image(
     *     mimeTypes="image/png",
     *     mimeTypesMessage="only png",
     *     minHeight="30",
     *     minHeightMessage="min {{ min_height }} pixels",
     *     maxHeight="40",
     *     maxHeightMessage="max {{ max_height }} pixels",
     *     minWidth="30",
     *     minWidthMessage="min {{ min_width }} pixels",
     *     maxWidth="40",
     *     maxWidthMessage="max {{ max_width }} pixels"
     * )
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="author")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="user")
     */
    private $contact;

    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTime();
        $this->valid = false;
        $this->tasks = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->contact = new ArrayCollection();
    }

    /**
     *
     */
    public function validate(): void
    {
        $this->valid = true;
        $this->token = null;
    }

    /**
     *
     */
    public function resetToken(): void
    {
        $this->token = null;
    }

    /**
     * @throws \Exception
     */
    public function updateDate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ? string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return \App\Entity\User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ? string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return \App\Entity\User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return \App\Entity\User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ? string
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ? string
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ? string
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ? string
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return \App\Entity\User
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     *
     * @return \App\Entity\User
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function getValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid($valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return string|null
     */
    public function getToken(): ? string
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param \App\Entity\Task $task
     *
     * @return \App\Entity\User
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Task $task
     *
     * @return \App\Entity\User
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getAuthor() === $this) {
                $task->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(? string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|null
     */
    public function getFile(): ? UploadedFile
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(? UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->image,
            $this->address,
            $this->city,
            $this->zipCode,
            $this->phone,
            $this->roles,
            $this->valid,
            $this->token
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->image,
            $this->address,
            $this->city,
            $this->zipCode,
            $this->phone,
            $this->roles,
            $this->valid,
            $this->token
            ) = unserialize($serialized);
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param \App\Entity\Category $category
     *
     * @return \App\Entity\User
     */
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Category $category
     *
     * @return \App\Entity\User
     */
    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getAuthor() === $this) {
                $category->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    /**
     * @param \App\Entity\Contact $contact
     *
     * @return \App\Entity\User
     */
    public function addContact(Contact $contact): self
    {
        if (!$this->contact->contains($contact)) {
            $this->contact[] = $contact;
            $contact->setUser($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\Contact $contact
     *
     * @return \App\Entity\User
     */
    public function removeContact(Contact $contact): self
    {
        if ($this->contact->contains($contact)) {
            $this->contact->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

}
