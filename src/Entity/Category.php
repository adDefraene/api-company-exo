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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $productRelation;

    public function __construct()
    {
        $this->productRelation = new ArrayCollection();
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

    /**
     * @return Collection|Product[]
     */
    public function getProductRelation(): Collection
    {
        return $this->productRelation;
    }

    public function addProductRelation(Product $productRelation): self
    {
        if (!$this->productRelation->contains($productRelation)) {
            $this->productRelation[] = $productRelation;
            $productRelation->setCategory($this);
        }

        return $this;
    }

    public function removeProductRelation(Product $productRelation): self
    {
        if ($this->productRelation->removeElement($productRelation)) {
            // set the owning side to null (unless already changed)
            if ($productRelation->getCategory() === $this) {
                $productRelation->setCategory(null);
            }
        }

        return $this;
    }
}
