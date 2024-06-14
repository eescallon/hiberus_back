<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inventory $inventory = null;

    #[ORM\Column]
    private ?bool $isCountable = null;

    #[ORM\ManyToOne]
    private ?CountableProduct $countableProduct = null;

    #[ORM\ManyToOne]
    private ?WeightedProduct $weightedProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function isCountable(): ?bool
    {
        return $this->isCountable;
    }

    public function setCountable(bool $isCountable): static
    {
        $this->isCountable = $isCountable;

        return $this;
    }

    public function getCountableProduct(): ?CountableProduct
    {
        return $this->countableProduct;
    }

    public function setCountableProduct(?CountableProduct $countableProduct): static
    {
        $this->countableProduct = $countableProduct;

        return $this;
    }

    public function getWeightedProduct(): ?WeightedProduct
    {
        return $this->weightedProduct;
    }

    public function setWeightedProduct(?WeightedProduct $weightedProduct): static
    {
        $this->weightedProduct = $weightedProduct;

        return $this;
    }
}
