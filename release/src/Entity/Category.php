<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="categories")
     */
    private $article_category;

    public function __construct()
    {
        $this->article_category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticleCategory(): Collection
    {
        return $this->article_category;
    }

    public function addArticleCategory(Article $articleCategory): self
    {
        if (!$this->article_category->contains($articleCategory)) {
            $this->article_category[] = $articleCategory;
        }

        return $this;
    }

    public function removeArticleCategory(Article $articleCategory): self
    {
        if ($this->article_category->contains($articleCategory)) {
            $this->article_category->removeElement($articleCategory);
        }

        return $this;
    }
}
