<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockInController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'stock', 'unit']);

        $entries = StockEntry::query()
            ->where('user_id', Auth::id())
            ->latest('entry_date')
            ->latest('id')
            ->with('product:id,name,category')
            ->paginate(10);

        return view('stock-in.index', compact('products', 'entries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('user_id', Auth::id())],
            'quantity' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'entry_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated): void {
            $product = Product::query()
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($validated['product_id']);
            $maximumStock = 4294967295;

            if ($product->stock > $maximumStock - $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah barang masuk melebihi kapasitas stok produk.',
                ]);
            }

            $product->increment('stock', $validated['quantity']);

            StockEntry::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'entry_date' => $validated['entry_date'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('stock-in.index')
            ->with('status', 'Transaksi barang masuk berhasil disimpan dan stok produk telah diperbarui.');
    }
}
