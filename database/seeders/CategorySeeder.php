<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'description' => 'Laravel PHP Framework',
                'is_active' => true,
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'description' => 'Containerization Platform',
                'is_active' => true,
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
                'description' => 'Amazon Web Services',
                'is_active' => true,
            ],
            [
                'name' => 'Redis',
                'slug' => 'redis',
                'description' => 'In-memory data store',
                'is_active' => true,
            ],
            [
                'name' => 'Linux',
                'slug' => 'linux',
                'description' => 'Linux Operating System',
                'is_active' => true,
            ],
        ];

        Category::insert($categories);
    }
}