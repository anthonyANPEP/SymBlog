<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern = "/^[\w\s\áàâäãéèêëíìîïóòôöõúùûüýÿÁÀÂÄÃÉÈÊËÍÌÎÏÓÒÔÖÕÚÙÛÜÝ\'\-]+$/i",
     *      htmlPattern  = "[\w\s\dáàâäãéèêëíìîïóòôöõúùûüýÿÁÀÂÄÃÉÈÊËÍÌÎÏÓÒÔÖÕÚÙÛÜÝ\'\-]+",
     *      message = "Le caractère est invalide "
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *      pattern = "/^[\w\s\dáàâäãéèêëíìîïóòôöõúùûüýÿÁÀÂÄÃÉÈÊËÍÌÎÏÓÒÔÖÕÚÙÛÜÝ\'\-]+$/i",
     *      htmlPattern  = "[\w\s\áàâäãéèêëíìîïóòôöõúùûüýÿÁÀÂÄÃÉÈÊËÍÌÎÏÓÒÔÖÕÚÙÛÜÝ\'\-]+",
     *      message = "Oups, un caractère est invalide "
     * )
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="category", orphanRemoval=true)
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
