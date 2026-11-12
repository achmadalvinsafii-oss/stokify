<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['q'] ?? '');
        $category = $validated['category'] ?? '';
        $categories = Product::query()
            ->where('user_id', Auth::id())
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $products = Product::query()
            ->where('user_id', Auth::id())
            ->when($search !== '', fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($search, 'UTF-8').'%']))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search', 'categories', 'category'));
    }

    public function create(): View
    {
        return view('products.form', [
            'product' => new Product,
            'pageTitle' => 'Tambah Produk',
            'formAction' => route('products.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create($this->validatedProduct($request));

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $produk): View
    {
        abort_unless($produk->user_id === Auth::id(), 404);

        return view('products.form', [
            'product' => $produk,
            'pageTitle' => 'Edit Produk',
            'formAction' => route('products.update', $produk),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, Product $produk): RedirectResponse
    {
        abort_unless($produk->user_id === Auth::id(), 404);

        $produk->update($this->validatedProduct($request));

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk): RedirectResponse
    {
        abort_unless($produk->user_id === Auth::id(), 404);

        $produk->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * @return array{name: string, category: string, price: string, stock: int, min_stock: int, unit: string}
     */
    private function validatedProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'min_stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'unit' => ['required', 'string', 'max:30'],
        ]);
    }
}
