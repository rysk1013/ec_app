<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getCategories()
    {
        return Category::select([
                'id',
                'name',
            ])
            ->get();
    }
}
