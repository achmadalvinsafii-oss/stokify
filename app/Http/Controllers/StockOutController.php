<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockExit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockOutController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'stock', 'unit']);

        $exits = StockExit::query()
            ->where('user_id', Auth::id())
            ->latest('exit_date')
            ->latest('id')
            ->with('product:id,name,category')
            ->paginate(10);

        return view('stock-out.index', compact('products', 'exits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('user_id', Auth::id())],
            'quantity' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'exit_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated): void {
            $product = Product::query()
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($validated['product_id']);

            $updated = Product::query()
                ->where('user_id', Auth::id())
                ->whereKey($product->id)
                ->where('stock', '>=', $validated['quantity'])
                ->decrement('stock', $validated['quantity']);

            if ($updated !== 1) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi. Stok tersedia saat ini: '.$product->stock.' '.$product->unit.'.',
                ]);
            }

            StockExit::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'exit_date' => $validated['exit_date'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('stock-out.index')
            ->with('status', 'Transaksi barang keluar berhasil disimpan dan stok produk telah diperbarui.');
    }
}
