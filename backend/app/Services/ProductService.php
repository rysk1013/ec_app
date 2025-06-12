<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

class ProductService
{
    public function __construct()
    {
        //
    }

    /**
     * Get all products
     *
     * @param array $parameter
     * @return LengthAwarePaginator<Product>
     */
    public function getProducts(array $parameter): LengthAwarePaginator
    {
        $query = Product::select([
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
            ->join('categories', 'categories.id', '=', 'products.category_id');

        if (isset($parameter['category_id'])) {
            $query->where('products.category_id', $parameter['category_id']);
        }

        if (isset($parameter['min_price'])) {
            $query->where('products.price', '>=', $parameter['min_price']);
        }

        if (isset($parameter['max_price'])) {
            $query->where('products.price', '<=', $parameter['max_price']);
        }

        if (isset($parameter['keyword'])) {
            $keyword = '%' . $parameter['keyword'] . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('products.name', 'LIKE', $keyword)
                    ->orWhere('products.description', 'LIKE', $keyword);
            });
        }

        if (isset($parameter['sort_by'])) {
            $query->sortBy($parameter['sort_by']);
        }

        $sortBy = $parameter['sort_by'] ?? 'id';
        $sortOrder = $parameter['sort_order'] ?? 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($parameter['per_page'] ?? 10);

        return $products;
    }
}
