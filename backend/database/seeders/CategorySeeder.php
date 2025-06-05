<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '家電',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '本',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '家具',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '食品',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '衣料品',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'スポーツ用品',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ゲーム',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'おもちゃ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '美容',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'パソコン・周辺機器',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
