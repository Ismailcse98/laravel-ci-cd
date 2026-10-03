<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category(): void
    {
        $payload = [
            'name' => 'Laravel',
            'slug' => 'laravel',
            'description' => 'Laravel PHP Framework',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/categories', $payload);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'Category created successfully.',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);
    }

    public function test_category_creation_requires_name_and_slug(): void
    {
        $response = $this->postJson('/api/categories', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'slug',
            ]);
    }

    public function test_category_slug_must_be_unique(): void
    {
        Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'description' => 'Laravel PHP Framework',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/categories', [
            'name' => 'Laravel Framework',
            'slug' => 'laravel',
            'description' => 'Another Laravel category',
            'is_active' => true,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'slug',
            ]);
    }

    public function test_can_get_single_category(): void
    {
        $category = Category::create([
            'name' => 'Docker',
            'slug' => 'docker',
            'description' => 'Containerization Platform',
            'is_active' => true,
        ]);

        $response = $this->getJson(
            "/api/categories/{$category->id}"
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => 'Docker',
                    'slug' => 'docker',
                ],
            ]);
    }

    public function test_can_get_categories(): void
    {
        Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'description' => 'Laravel PHP Framework',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Categories retrieved successfully.',
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Docker',
            'slug' => 'docker',
            'description' => 'Containerization Platform',
            'is_active' => true,
        ]);

        $response = $this->putJson(
            "/api/categories/{$category->id}",
            [
                'name' => 'Docker Container',
                'slug' => 'docker-container',
                'description' => 'Container Platform',
                'is_active' => true,
            ]
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Category updated successfully.',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Docker Container',
            'slug' => 'docker-container',
        ]);
    }

    public function test_returns_404_when_category_does_not_exist(): void
    {
        $response = $this->getJson('/api/categories/99999');

        $response->assertNotFound();
    }


}