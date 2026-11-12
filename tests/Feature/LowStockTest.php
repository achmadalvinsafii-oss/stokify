<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class LowStockTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_minimum_stock_column_is_available_with_a_default_of_ten(): void
    {
        $product = Product::create([
            'name' => 'Stok default',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 20,
            'unit' => 'pcs',
        ]);

        $this->assertSame(10, $product->fresh()->min_stock);
    }

    public function test_products_at_or_below_their_minimum_are_counted_as_low_stock(): void
    {
        Product::create([
            'name' => 'Tepat di batas',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 10,
            'min_stock' => 10,
            'unit' => 'pcs',
        ]);
        Product::create([
            'name' => 'Di bawah batas',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 0,
            'min_stock' => 10,
            'unit' => 'pcs',
        ]);
        Product::create([
            'name' => 'Stok aman',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 11,
            'min_stock' => 10,
            'unit' => 'pcs',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('>3</div>', false)
            ->assertSee('>2</div>', false)
            ->assertSee('Tepat di batas')
            ->assertSee('Di bawah batas')
            ->assertSee('Stok menipis')
            ->assertSee('Aman');
    }

    public function test_product_list_shows_stock_status_and_configured_minimum(): void
    {
        Product::create([
            'name' => 'Kopi stok rendah',
            'category' => 'Kopi',
            'price' => 85000,
            'stock' => 5,
            'min_stock' => 6,
            'unit' => 'pak',
        ]);
        Product::create([
            'name' => 'Susu aman',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 7,
            'min_stock' => 6,
            'unit' => 'kotak',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Kopi stok rendah')
            ->assertSee('Susu aman')
            ->assertSee('Stok menipis')
            ->assertSee('Aman')
            ->assertSee('Batas minimum: 6');
    }

    public function test_product_minimum_stock_can_be_set_and_defaults_to_ten(): void
    {
        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee('value="10"', false);

        $this->post(route('products.store'), [
            'name' => 'Teh Botol',
            'category' => 'Minuman',
            'price' => 5000,
            'stock' => 20,
            'min_stock' => 7,
            'unit' => 'botol',
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Teh Botol',
            'stock' => 20,
            'min_stock' => 7,
        ]);

        $this->from(route('products.create'))
            ->post(route('products.store'), [
                'name' => 'Produk tanpa batas valid',
                'category' => 'Uji',
                'price' => 1000,
                'stock' => 5,
                'min_stock' => -1,
                'unit' => 'pcs',
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors('min_stock');
    }
}
