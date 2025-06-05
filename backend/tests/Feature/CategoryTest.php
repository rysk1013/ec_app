<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Successfully gets a list of categories when categories exist
     */
    #[Test]
    public function it_can_get_a_list_of_categories(): void
    {
        $categories = Category::factory()->count(5)->create();

        $response = $this->getJson('api/categories');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ],
            ])
            ->assertJsonCount(5, 'data');

        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }
    }

    /**
     * Successfully gets an empty list when no categories exist
     */
    #[Test]
    public function it_can_get_an_empty_list_if_no_categories_exist()
    {
        $response = $this->getJson('api/categories');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
            ])
            ->assertJsonCount(0, 'data');
    }
}
