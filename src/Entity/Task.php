<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     */
    private $author;

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
     * @return mixed
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * @param mixed $done
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
}
