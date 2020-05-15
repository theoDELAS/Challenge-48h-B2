<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
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
    private $email;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user", orphanRemoval=true)
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity=Story::class, mappedBy="user", orphanRemoval=true)
     */
    private $story;

    public function __construct()
    {
        $this->role = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->article = new ArrayCollection();
        $this->story = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->role->contains($role)) {
            $this->role->removeElement($role);
        }

        return $this;

    }

    public function getSalt() {}
    public function eraseCredentials() {}


    public function getRoles()
    {
        $roles = $this->role->map(function ($role)
        {
            return $role->getLabel();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Story[]
     */
    public function getStory(): Collection
    {
        return $this->story;
    }

    public function addStory(Story $story): self
    {
        if (!$this->story->contains($story)) {
            $this->story[] = $story;
            $story->setUser($this);
        }

        return $this;
    }

    public function removeStory(Story $story): self
    {
        if ($this->story->contains($story)) {
            $this->story->removeElement($story);
            // set the owning side to null (unless already changed)
            if ($story->getUser() === $this) {
                $story->setUser(null);
            }
        }

        return $this;
    }



}

