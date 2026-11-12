<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesFeatureTests;
use Tests\TestCase;

class MultiUserIsolationTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatesFeatureTests;

    public function test_each_account_only_sees_its_own_products_and_transactions(): void
    {
        $firstUser = $this->authenticatedUser;
        $secondUser = User::factory()->create();
        $firstProduct = $this->createProduct($firstUser, 'Produk akun pertama', 7);
        $secondProduct = $this->createProduct($secondUser, 'Produk akun kedua', 99);

        $this->actingAs($firstUser);
        StockEntry::create([
            'product_id' => $firstProduct->id,
            'product_name' => $firstProduct->name,
            'quantity' => 3,
            'entry_date' => '2026-11-10',
            'notes' => 'Barang masuk akun pertama',
        ]);
        StockExit::create([
            'product_id' => $firstProduct->id,
            'product_name' => $firstProduct->name,
            'quantity' => 2,
            'exit_date' => '2026-11-10',
            'notes' => 'Barang keluar akun pertama',
        ]);

        $this->actingAs($secondUser);
        StockEntry::create([
            'product_id' => $secondProduct->id,
            'product_name' => $secondProduct->name,
            'quantity' => 4,
            'entry_date' => '2026-11-10',
            'notes' => 'Barang masuk akun kedua',
        ]);
        StockExit::create([
            'product_id' => $secondProduct->id,
            'product_name' => $secondProduct->name,
            'quantity' => 1,
            'exit_date' => '2026-11-10',
            'notes' => 'Barang keluar akun kedua',
        ]);

        foreach ([
            [$firstUser, $firstProduct, $secondProduct, 'akun pertama', 'akun kedua'],
            [$secondUser, $secondProduct, $firstProduct, 'akun kedua', 'akun pertama'],
        ] as [$user, $ownProduct, $otherProduct, $ownLabel, $otherLabel]) {
            $this->actingAs($user);

            foreach ([
                route('home'),
                route('products.index'),
                route('stock-in.index'),
                route('stock-out.index'),
                route('stock-report.index'),
            ] as $url) {
                $this->get($url)
                    ->assertOk()
                    ->assertSee($ownProduct->name)
                    ->assertDontSee($otherProduct->name);
            }

            $this->get(route('stock-in.index'))
                ->assertSee('Barang masuk '.$ownLabel)
                ->assertDontSee('Barang masuk '.$otherLabel);

            $this->get(route('stock-out.index'))
                ->assertSee('Barang keluar '.$ownLabel)
                ->assertDontSee('Barang keluar '.$otherLabel);

            $this->get(route('stock-report.index'))
                ->assertSee('Barang masuk '.$ownLabel)
                ->assertSee('Barang keluar '.$ownLabel)
                ->assertDontSee('Barang masuk '.$otherLabel)
                ->assertDontSee('Barang keluar '.$otherLabel);

            $this->get(route('products.index', ['q' => $ownLabel]))
                ->assertOk()
                ->assertSee($ownProduct->name)
                ->assertDontSee($otherProduct->name);

            $this->get(route('stock-report.index', ['product_id' => $ownProduct->id]))
                ->assertOk()
                ->assertSee($ownProduct->name)
                ->assertDontSee($otherProduct->name);

            $this->get(route('stock-report.index', ['product_id' => $otherProduct->id]))
                ->assertRedirect()
                ->assertSessionHasErrors('product_id');
        }
    }

    public function test_second_account_cannot_edit_update_or_delete_first_accounts_product(): void
    {
        $firstProduct = $this->createProduct($this->authenticatedUser, 'Produk akun pertama', 12);
        $secondUser = User::factory()->create();
        $this->actingAs($secondUser);

        $updateData = [
            'name' => 'Produk diubah',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 12,
            'min_stock' => 2,
            'unit' => 'pcs',
        ];

        $this->get(route('products.edit', $firstProduct))
            ->assertNotFound();

        $this->put(route('products.update', $firstProduct), $updateData)
            ->assertNotFound();

        $this->delete(route('products.destroy', $firstProduct))
            ->assertNotFound();

        $this->assertDatabaseHas('products', [
            'id' => $firstProduct->id,
            'user_id' => $this->authenticatedUser->id,
            'name' => 'Produk akun pertama',
            'stock' => 12,
        ]);
    }

    public function test_second_account_cannot_create_stock_transactions_for_first_accounts_product(): void
    {
        $firstProduct = $this->createProduct($this->authenticatedUser, 'Produk akun pertama', 12);
        $secondUser = User::factory()->create();
        $secondProduct = $this->createProduct($secondUser, 'Produk akun kedua', 8);
        $this->actingAs($secondUser);

        $entryCount = StockEntry::count();
        $exitCount = StockExit::count();

        $this->from(route('stock-in.index'))
            ->post(route('stock-in.store'), [
                'product_id' => $firstProduct->id,
                'quantity' => 2,
                'entry_date' => '2026-11-10',
            ])
            ->assertRedirect(route('stock-in.index'))
            ->assertSessionHasErrors('product_id');

        $this->from(route('stock-out.index'))
            ->post(route('stock-out.store'), [
                'product_id' => $firstProduct->id,
                'quantity' => 2,
                'exit_date' => '2026-11-10',
            ])
            ->assertRedirect(route('stock-out.index'))
            ->assertSessionHasErrors('product_id');

        $this->assertDatabaseHas('products', [
            'id' => $firstProduct->id,
            'user_id' => $this->authenticatedUser->id,
            'stock' => 12,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $secondProduct->id,
            'user_id' => $secondUser->id,
            'stock' => 8,
        ]);
        $this->assertSame($entryCount, StockEntry::count());
        $this->assertSame($exitCount, StockExit::count());
    }

    public function test_both_accounts_can_use_all_inventory_features_for_their_own_products(): void
    {
        $firstUser = $this->authenticatedUser;
        $secondUser = User::factory()->create();

        foreach ([
            [$firstUser, $secondUser, 'fitur akun pertama'],
            [$secondUser, $firstUser, 'fitur akun kedua'],
        ] as [$user, $otherUser, $label]) {
            $this->actingAs($user);
            $productName = 'Produk '.$label;

            $this->post(route('products.store'), [
                'name' => $productName,
                'category' => 'Uji',
                'price' => 1000,
                'stock' => 10,
                'min_stock' => 2,
                'unit' => 'pcs',
                'user_id' => $otherUser->id,
            ])->assertRedirect(route('products.index'));

            $product = Product::query()->where('name', $productName)->firstOrFail();
            $this->assertSame($user->id, $product->user_id);

            $this->get(route('products.edit', $product))
                ->assertOk()
                ->assertSee($productName);

            $this->put(route('products.update', $product), [
                'name' => $productName.' diperbarui',
                'category' => 'Uji',
                'price' => 1200,
                'stock' => 10,
                'min_stock' => 2,
                'unit' => 'pcs',
                'user_id' => $otherUser->id,
            ])->assertRedirect(route('products.index'));

            $product->refresh();
            $this->assertSame($user->id, $product->user_id);
            $this->assertSame($productName.' diperbarui', $product->name);

            $this->post(route('stock-in.store'), [
                'product_id' => $product->id,
                'quantity' => 2,
                'entry_date' => '2026-11-10',
                'notes' => 'Barang masuk '.$label,
                'user_id' => $otherUser->id,
            ])->assertRedirect(route('stock-in.index'));

            $entry = StockEntry::query()
                ->where('product_id', $product->id)
                ->where('notes', 'Barang masuk '.$label)
                ->firstOrFail();
            $this->assertSame($user->id, $entry->user_id);

            $this->post(route('stock-out.store'), [
                'product_id' => $product->id,
                'quantity' => 3,
                'exit_date' => '2026-11-10',
                'notes' => 'Barang keluar '.$label,
                'user_id' => $otherUser->id,
            ])->assertRedirect(route('stock-out.index'));

            $exit = StockExit::query()
                ->where('product_id', $product->id)
                ->where('notes', 'Barang keluar '.$label)
                ->firstOrFail();
            $this->assertSame($user->id, $exit->user_id);
            $this->assertSame(9, $product->fresh()->stock);

            $this->get(route('stock-in.index'))
                ->assertOk()
                ->assertSee('Barang masuk '.$label);

            $this->get(route('stock-out.index'))
                ->assertOk()
                ->assertSee('Barang keluar '.$label);

            $this->delete(route('products.destroy', $product))
                ->assertRedirect(route('products.index'));

            $this->assertDatabaseMissing('products', [
                'id' => $product->id,
            ]);
        }
    }

    public function test_product_creation_uses_authenticated_user_instead_of_submitted_user_id(): void
    {
        $otherUser = User::factory()->create();

        $this->post(route('products.store'), [
            'name' => 'Produk baru',
            'category' => 'Uji',
            'price' => 1000,
            'stock' => 4,
            'min_stock' => 1,
            'unit' => 'pcs',
            'user_id' => $otherUser->id,
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Produk baru',
            'user_id' => $this->authenticatedUser->id,
        ]);
    }

    private function createProduct(User $owner, string $name, int $stock): Product
    {
        $this->actingAs($owner);

        return Product::create([
            'name' => $name,
            'category' => 'Uji',
            'price' => 1000,
            'stock' => $stock,
            'min_stock' => 2,
            'unit' => 'pcs',
        ]);
    }
}
