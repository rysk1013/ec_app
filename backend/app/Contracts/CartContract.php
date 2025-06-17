<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use App\Models\Product;

interface CartContract
{
    public function add(Product $product, int $quantity = 1): void;
    public function remove(Product $product): void;
    public function update(Product $product, int $quantity): void;
    public function getCartItems(): Collection;
    public function getTotal(): float;
    public function clear(): void;
    public function load(): void;
    public function save(): void;
}
