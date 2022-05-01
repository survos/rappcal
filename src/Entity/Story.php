<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
class Story
{

    public function __construct(
    ) {


    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $url;

    #[ORM\Column(type: 'text', nullable: true)]
    private $html;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageUrl;

    #[ORM\Column(type: 'string', length: 255)]
    private $headline;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'date')]
    private $publicationDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $author;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $doerName;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDoer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDoerName(): ?string
    {
        return $this->doerName;
    }

    public function setDoerName(?string $doerName): self
    {
        $this->doerName = $doerName;

        return $this;
    }

    public function getIsDoer(): ?bool
    {
        return $this->isDoer;
    }

    public function setIsDoer(?bool $isDoer): self
    {
        $this->isDoer = $isDoer;

        return $this;
    }
}
