<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

class CategoryService
{
    /**
     * Get all categories
     *
     * @return Collection<Category>
     */
    public function getCategories(): Collection
    {
        return Category::select([
                'id',
                'name',
            ])
            ->get();
    }
}
