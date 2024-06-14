<?php 
namespace App\Model;

interface PricingStrategy {
    public function getProductPrice(int $amount, float $price): float;
}