<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $category1 = Category::factory()->create(['name' => '家電']);
        Product::factory()->count(3)->create([
            'category_id' => $category1->id,
            'price' => fake()->numberBetween(50000, 200000),
        ]);

        $category2 = Category::factory()->create(['name' => '本']);
        Product::factory()->count(2)->create([
            'category_id' => $category2->id,
            'price' => fake()->numberBetween(500, 3000),
        ]);

        $category3 = Category::factory()->create(['name' => '衣料品']);
        Product::factory()->count(5)->create([
            'category_id' => $category3->id,
            'price' => fake()->numberBetween(3000, 15000),
        ]);
    }

    /**
     * Successfully gets a list of products when products exist
     */
    #[Test]
    public function it_can_get_a_list_of_products(): void
    {
        $response = $this->getJson('api/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'image_url',
                        'category' => [
                                'id',
                                'name',
                            ],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active',
                        ],
                    ],
                    "path",
                    "per_page",
                    "to",
                    "total",
                ],
            ])
            ->assertJsonCount(10, 'data');

        $response->assertJsonFragment([
            'total' => 10,
            'per_page' => 10,
            'current_page' => 1,
        ]);
    }

    /**
     * Successfully gets an empty list when no products exist
     */
    #[Test]
    public function it_can_get_an_empty_list_if_no_products_exist(): void
    {
        Category::query()->delete();
        Product::query()->delete();

        $response = $this->getJson('api/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
            ])
            ->assertJsonCount(0, 'data');
    }

    /**
     * Filters products by a specific category
     */
    #[Test]
    public function it_can_filter_products_by_category(): void
    {
        $category2 = Category::where('name', '本')->first();
        $expectedProductCount = Product::where('category_id', $category2->id)->count();

        $response = $this->getJson('api/products?category_id=' . $category2->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'image_url',
                        'category' => [
                                'id',
                                'name',
                            ],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active',
                        ],
                    ],
                    "path",
                    "per_page",
                    "to",
                    "total",
                ],
            ])
            ->assertJsonFragment([
                'total' => $expectedProductCount,
                'per_page' => 10,
                'current_page' => 1,
            ]);

        $products = $response->json('data');
        foreach ($products as $product) {
            $this->assertEquals($category2->id, $product['category']['id']);
        }
    }

    /**
     * Filters products by price range
     */
    #[Test]
    public function it_can_filter_products_by_price_range(): void
    {
        $minPrice = 5000;
        $maxPrice = 10000;

        $expectedProductCount = Product::whereBetween('price', [$minPrice, $maxPrice])->count();

        $response = $this->getJson("api/products?min_price={$minPrice}&max_price={$maxPrice}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($expectedProductCount, 'data')
            ->assertJsonFragment([
                'total' => $expectedProductCount,
            ]);

        $products = $response->json('data');
        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual($minPrice, $product['price']);
            $this->assertLessThanOrEqual($maxPrice, $product['price']);
        }
    }

    /**
     * Get a specific page of products
     */
    #[Test]
    public function it_gets_a_specific_page_of_products(): void
    {
        $page = 2;
        $perPage = 3;

        $expectedProducts = Product::orderBy('id', 'desc')
            ->skip($perPage * ($page -1))
            ->take($perPage)
            ->get();

        $response = $this->getJson("api/products?page={$page}&per_page={$perPage}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'current_page' => $page,
                'per_page' => $perPage,
            ]);

        $responseData = $response->json('data');
        foreach ($expectedProducts as $index => $expectedProduct) {
            $this->assertEquals($expectedProduct->id, $responseData[$index]['id']);
            $this->assertEquals($expectedProduct->name, $responseData[$index]['name']);
        }
    }

    /**
     * Validation fails with invalid category_id
     */
    #[Test]
    public function it_returns_validation_error_for_invalid_category_id(): void
    {
        $invalidCategoryId = 9999;

        $response = $this->getJson("api/products?category_id={$invalidCategoryId}");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['category_id']);
    }

    /**
     * Validation fails with invalid per_page parameter
     */
    #[Test]
    public function it_returns_validation_error_for_invalid_per_page(): void
    {
        $response = $this->getJson('api/products?per_page=abc');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['per_page']);
    }
}
