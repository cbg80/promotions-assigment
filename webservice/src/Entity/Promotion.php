<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\adapters\DoctrinePromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctrinePromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private string $id;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private ?string $discountPercentage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="promotions")
     */
    private Collection $products;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="promotions")
     */
    private Collection $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $categoryName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $productSku;

    public function __construct(string $id)
    {
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDiscountPercentage(): ?string
    {
        return $this->discountPercentage;
    }
    
    public function setDiscountPercentage(?string $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;
        
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addPromotion($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removePromotion($this);
        }

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
            $category->addPromotion($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removePromotion($this);
        }

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(?string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getProductSku(): ?string
    {
        return $this->productSku;
    }

    public function setProductSku(?string $productSku): self
    {
        $this->productSku = $productSku;

        return $this;
    }
}
