<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class StockReportTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_report_shows_inventory_summary_and_stock_transactions(): void
    {
        $coffee = Product::create([
            'name' => 'Kopi Arabika',
            'category' => 'Kopi',
            'price' => 85000,
            'stock' => 17,
            'unit' => 'pak',
        ]);
        $milk = Product::create([
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 6,
            'unit' => 'kotak',
        ]);

        StockEntry::create([
            'product_id' => $coffee->id,
            'product_name' => $coffee->name,
            'quantity' => 10,
            'entry_date' => '2026-11-06',
            'notes' => 'Restock kopi',
        ]);
        StockExit::create([
            'product_id' => $coffee->id,
            'product_name' => $coffee->name,
            'quantity' => 3,
            'exit_date' => '2026-11-06',
            'notes' => 'Penjualan kopi',
        ]);

        $this->get(route('stock-report.index'))
            ->assertOk()
            ->assertSee('Kopi Arabika')
            ->assertSee('Susu UHT')
            ->assertSee('Restock kopi')
            ->assertSee('Penjualan kopi')
            ->assertSee('Total Produk')
            ->assertSee('17')
            ->assertSee('23');
    }

    public function test_product_and_date_filters_apply_to_inventory_and_transaction_history(): void
    {
        $coffee = Product::create([
            'name' => 'Kopi Arabika',
            'category' => 'Kopi',
            'price' => 85000,
            'stock' => 17,
            'unit' => 'pak',
        ]);
        $milk = Product::create([
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'price' => 18000,
            'stock' => 6,
            'unit' => 'kotak',
        ]);

        StockEntry::create([
            'product_id' => $coffee->id,
            'product_name' => $coffee->name,
            'quantity' => 10,
            'entry_date' => '2026-11-06',
            'notes' => 'Masuk sesuai tanggal',
        ]);
        StockEntry::create([
            'product_id' => $coffee->id,
            'product_name' => $coffee->name,
            'quantity' => 2,
            'entry_date' => '2026-10-01',
            'notes' => 'Masuk di luar tanggal',
        ]);
        StockEntry::create([
            'product_id' => $milk->id,
            'product_name' => $milk->name,
            'quantity' => 5,
            'entry_date' => '2026-11-06',
            'notes' => 'Produk lain',
        ]);
        StockExit::create([
            'product_id' => $coffee->id,
            'product_name' => $coffee->name,
            'quantity' => 3,
            'exit_date' => '2026-11-06',
            'notes' => 'Keluar sesuai tanggal',
        ]);

        $this->get(route('stock-report.index', [
            'product_id' => $coffee->id,
            'from_date' => '2026-11-06',
            'to_date' => '2026-11-06',
        ]))
            ->assertOk()
            ->assertSee('<td class="product-name">Kopi Arabika</td>', false)
            ->assertDontSee('<td class="product-name">Susu UHT</td>', false)
            ->assertSee('Masuk sesuai tanggal')
            ->assertDontSee('Masuk di luar tanggal')
            ->assertDontSee('Produk lain')
            ->assertSee('Keluar sesuai tanggal');
    }

    public function test_report_rejects_an_end_date_before_start_date(): void
    {
        $this->get(route('stock-report.index', [
            'from_date' => '2026-11-07',
            'to_date' => '2026-11-06',
        ]))
            ->assertRedirect()
            ->assertSessionHasErrors('to_date');
    }
}
