<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class ProductSearchFilterTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_product_search_is_case_insensitive(): void
    {
        Product::create([
            'name' => 'Kopi Susu',
            'category' => 'Minuman',
            'price' => 15000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'gelas',
        ]);
        Product::create([
            'name' => 'Teh Manis',
            'category' => 'Minuman',
            'price' => 8000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'gelas',
        ]);

        $this->get(route('products.index', ['q' => 'kOpI']))
            ->assertOk()
            ->assertSee('Kopi Susu')
            ->assertDontSee('Teh Manis');
    }

    public function test_search_and_category_filter_can_be_applied_together(): void
    {
        Product::create([
            'name' => 'Kopi Susu',
            'category' => 'Minuman',
            'price' => 15000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'gelas',
        ]);
        Product::create([
            'name' => 'Kopi Bubuk',
            'category' => 'Bahan Baku',
            'price' => 25000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'pak',
        ]);
        Product::create([
            'name' => 'Teh Manis',
            'category' => 'Minuman',
            'price' => 8000,
            'stock' => 10,
            'min_stock' => 2,
            'unit' => 'gelas',
        ]);

        $this->get(route('products.index', ['q' => 'kopi', 'category' => 'Minuman']))
            ->assertOk()
            ->assertSee('Kopi Susu')
            ->assertDontSee('Kopi Bubuk')
            ->assertDontSee('Teh Manis')
            ->assertSee('Semua Kategori')
            ->assertSee('Minuman');
    }
}