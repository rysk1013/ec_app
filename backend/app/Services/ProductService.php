<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getProducts()
    {
        $products = Product::select([
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image_url',
                'products.category_id',
                'categories.name as category_name',
                'products.created_at',
                'products.updated_at',
            ])
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->get();

            return $products;
    }
}
