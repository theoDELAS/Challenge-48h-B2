<?php

namespace App\Entity;

use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StoryRepository::class)
 */
class Story
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publication_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity=StoryCategory::class, inversedBy="stories")
     */
    private $category_story;

    public function __construct()
    {
        $this->category_story = new ArrayCollection();
    }

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(\DateTimeInterface $last_update_date): self
    {
        $this->last_update_date = $last_update_date;

        return $this;
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

    /**
     * @return Collection|StoryCategory[]
     */
    public function getCategoryStory(): Collection
    {
        return $this->category_story;
    }

    public function addCategoryStory(StoryCategory $categoryStory): self
    {
        if (!$this->category_story->contains($categoryStory)) {
            $this->category_story[] = $categoryStory;
        }

        return $this;
    }

    public function removeCategoryStory(StoryCategory $categoryStory): self
    {
        if ($this->category_story->contains($categoryStory)) {
            $this->category_story->removeElement($categoryStory);
        }

        return $this;
    }
}
