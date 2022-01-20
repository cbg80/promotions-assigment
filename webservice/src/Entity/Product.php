<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\adapters\DoctrineProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\VO\Price;

/**
 * @ORM\Entity(repositoryClass=DoctrineProductRepository::class)
 * @ORM\Table(indexes={
 *      @ORM\Index(name="idx_price_original", columns={"original"})
 * })
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, cascade={"persist"})
     */
    private Collection $categories;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, inversedBy="products")
     */
    private Collection $promotions;

    /**
     * @ORM\Embedded(class="App\VO\Price", columnPrefix=false)
     */
    private Price $price;

    public function __construct(string $id)
    {
        $this->categories = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        $this->promotions->removeElement($promotion);

        return $this;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): self
    {
        $this->price = $price;

        return $this;
    }
}
