<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 30)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $nbbooks = null;

    #[ORM\OneToMany(mappedBy: 'Author', targetEntity: Book::class,cascade:["all"],orphanRemoval:true)]
    private Collection $Author;

    public function __construct()
    {
        $this->Author = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNbbooks(): ?int
    {
        return $this->nbbooks;
    }

    public function setNbbooks(int $nbbooks): static
    {
        $this->nbbooks = $nbbooks;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getAuthor(): Collection
    {
        return $this->Author;
    }

    public function addAuthor(Book $author): static
    {
        if (!$this->Author->contains($author)) {
            $this->Author->add($author);
            $author->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthor(Book $author): static
    {
        if ($this->Author->removeElement($author)) {
          
            if ($author->getAuthor() === $this) {
                $author->setAuthor(null);
            }
        }

        return $this;
    }
//when i use author as object to show it 
     public function __toString()
     {
        return (String)$this->getUsername();
     }
}
