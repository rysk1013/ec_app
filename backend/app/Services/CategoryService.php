<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * Get all categories
     *
     * @return EloquentCollection<Category>
     */
    public function getCategories(): EloquentCollection
    {
        return Category::select([
                'id',
                'name',
            ])
            ->get();
    }
}
