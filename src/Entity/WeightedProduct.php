<?php

namespace App\Entity;

use App\Repository\WeightedProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Model\PricingStrategy;

#[ORM\Entity(repositoryClass: WeightedProductRepository::class)]
class WeightedProduct implements PricingStrategy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column]
    private ?float $weight = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    public function __construct()
    {
        $this->inventoryProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getProductPrice(int $amount, float $price): float {
        $result = $amount * $price;
        if ($amount > 10) {
            $discount = $result * 0.1;
            $result = $result - $discount;
        }
        return $result;
    }
}
