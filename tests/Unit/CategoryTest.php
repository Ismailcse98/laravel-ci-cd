<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
     public function test_category_name_can_be_converted_to_slug(): void
    {
        $name = 'Laravel Framework';

        $slug = str()->slug($name);

        $this->assertSame(
            'laravel-framework',
            $slug
        );
    }
}
