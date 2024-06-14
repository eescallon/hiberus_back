<?php

namespace App\Entity;

use App\Repository\CountableProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Model\PricingStrategy;

#[ORM\Entity(repositoryClass: CountableProductRepository::class)]
class CountableProduct implements PricingStrategy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column]
    private ?int $totalAmount = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getTotalPrice(float $totalUnities): float
    {
        $total = $this->price * $totalUnities;
        return $total;
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
        return $amount * $price;
    }
}
