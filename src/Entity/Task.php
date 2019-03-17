<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @Assert\Uuid()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

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
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type("datetime")
     */
    private $doneAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tasks")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var
     * @Assert\Image(
     *     mimeTypes="image/jpeg",
     *     mimeTypesMessage="only jpeg",
     *     minHeight="100",
     *     minHeightMessage="min {{ min_height }} pixels",
     *     maxHeight="400",
     *     maxHeightMessage="max {{ max_height }} pixels",
     *     minWidth="100",
     *     minWidthMessage="min {{ min_width }} pixels",
     *     maxWidth="400",
     *     maxWidthMessage="max {{ max_width }} pixels"
     * )
     */
    private $file;

    /**
     * Task constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new \DateTime();
        $this->done = false;
        $this->pin = false;
    }

    /**
     * @throws \Exception
     */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @throws \Exception
     */
    public function doneDate()
    {
        $this->doneAt = new \DateTime();
    }

    /**
     *
     */
    public function done()
    {
        $this->done = true;
    }

    /**
     *
     */
    public function notDone()
    {
        $this->done = false;
    }

    /**
     *
     */
    public function pin()
    {
        $this->pin = true;
    }

    /**
     *
     */
    public function notPin()
    {
        $this->pin = false;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return \App\Entity\Task
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return \App\Entity\Task
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
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
     * @return \App\Entity\Task
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
     * @return \App\Entity\Task
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * @param $done
     */
    public function setDone($done): void
    {
        $this->done = $done;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->doneAt;
    }

    /**
     * @param \DateTimeInterface $doneAt
     */
    public function setDoneAt(\DateTimeInterface $doneAt): void
    {
        $this->doneAt = $doneAt;
    }


    /**
     * @return \App\Entity\User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param \App\Entity\User|null $author
     *
     * @return \App\Entity\Task
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPin(): bool
    {
        return $this->pin;
    }

    /**
     * @param bool $pin
     */
    public function setPin(bool $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return \App\Entity\Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param \App\Entity\Category|null $category
     *
     * @return \App\Entity\Task
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ? string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(? string $image)
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


}
