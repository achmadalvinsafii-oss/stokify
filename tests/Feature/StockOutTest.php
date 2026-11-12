<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockExit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class StockOutTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_stock_out_transaction_decreases_product_stock_and_is_listed(): void
    {
        $product = Product::create([
            'name' => 'Kopi Arabika',
            'category' => 'Kopi',
            'price' => 85000,
            'stock' => 12,
            'unit' => 'pak',
        ]);

        $response = $this->post(route('stock-out.store'), [
            'product_id' => $product->id,
            'quantity' => 5,
            'exit_date' => '2026-11-06',
            'notes' => 'Penjualan harian',
        ]);

        $response->assertRedirect(route('stock-out.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 7,
        ]);
        $this->assertDatabaseHas('stock_exits', [
            'product_id' => $product->id,
            'product_name' => 'Kopi Arabika',
            'quantity' => 5,
            'exit_date' => '2026-11-06 00:00:00',
            'notes' => 'Penjualan harian',
        ]);

        $this->get(route('stock-out.index'))
            ->assertOk()
            ->assertSee('Kopi Arabika')
            ->assertSee('Penjualan harian');
    }

    public function test_stock_out_cannot_exceed_available_stock(): void
    {
        $product = Product::create([
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 3,
            'unit' => 'kotak',
        ]);

        $response = $this->from(route('stock-out.index'))
            ->post(route('stock-out.store'), [
                'product_id' => $product->id,
                'quantity' => 4,
                'exit_date' => '2026-11-06',
                'notes' => '',
            ]);

        $response->assertRedirect(route('stock-out.index'));
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
        $this->assertSame(0, StockExit::count());
    }

    public function test_stock_out_rejects_non_positive_quantity(): void
    {
        $product = Product::create([
            'name' => 'Gula Aren',
            'category' => 'Bahan baku',
            'price' => 32000,
            'stock' => 9,
            'unit' => 'botol',
        ]);

        $response = $this->from(route('stock-out.index'))
            ->post(route('stock-out.store'), [
                'product_id' => $product->id,
                'quantity' => 0,
                'exit_date' => '2026-11-06',
                'notes' => '',
            ]);

        $response->assertRedirect(route('stock-out.index'));
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 9,
        ]);
        $this->assertSame(0, StockExit::count());
    }
}
