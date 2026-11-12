<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class StockInTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_stock_in_transaction_increases_product_stock_and_is_listed(): void
    {
        $product = Product::create([
            'name' => 'Kopi Arabika',
            'category' => 'Kopi',
            'price' => 85000,
            'stock' => 12,
            'unit' => 'pak',
        ]);

        $response = $this->post(route('stock-in.store'), [
            'product_id' => $product->id,
            'quantity' => 8,
            'entry_date' => '2026-11-06',
            'notes' => 'Pembelian supplier',
        ]);

        $response->assertRedirect(route('stock-in.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 20,
        ]);
        $this->assertDatabaseHas('stock_entries', [
            'product_id' => $product->id,
            'product_name' => 'Kopi Arabika',
            'quantity' => 8,
            'entry_date' => '2026-11-06 00:00:00',
            'notes' => 'Pembelian supplier',
        ]);

        $this->get(route('stock-in.index'))
            ->assertOk()
            ->assertSee('Kopi Arabika')
            ->assertSee('Pembelian supplier');
    }

    public function test_stock_in_rejects_invalid_quantity_without_changing_stock(): void
    {
        $product = Product::create([
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 5,
            'unit' => 'kotak',
        ]);

        $response = $this->from(route('stock-in.index'))
            ->post(route('stock-in.store'), [
                'product_id' => $product->id,
                'quantity' => 0,
                'entry_date' => '2026-11-06',
                'notes' => '',
            ]);

        $response->assertRedirect(route('stock-in.index'));
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 5,
        ]);
        $this->assertSame(0, StockEntry::count());
    }
}
