<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')->where('user_id', Auth::id())],
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $productId = $filters['product_id'] ?? null;
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;

        if ($fromDate && $toDate && strtotime($toDate) < strtotime($fromDate)) {
            throw ValidationException::withMessages([
                'to_date' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            ]);
        }

        $products = Product::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name']);

        $inventoryQuery = Product::query()
            ->where('user_id', Auth::id())
            ->when($productId, fn ($query) => $query->whereKey($productId));
        $inventory = (clone $inventoryQuery)
            ->orderBy('name')
            ->paginate(10, ['*'], 'products_page')
            ->withQueryString();
        $totalProducts = (clone $inventoryQuery)->count();
        $totalStock = (clone $inventoryQuery)->sum('stock');

        $entries = StockEntry::query()
            ->where('user_id', Auth::id())
            ->when($productId, fn ($query) => $query->where('product_id', $productId))
            ->when($fromDate, fn ($query) => $query->whereDate('entry_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('entry_date', '<=', $toDate))
            ->latest('entry_date')
            ->latest('id')
            ->with('product:id,name,category')
            ->paginate(10, ['*'], 'entries_page')
            ->withQueryString();

        $exits = StockExit::query()
            ->where('user_id', Auth::id())
            ->when($productId, fn ($query) => $query->where('product_id', $productId))
            ->when($fromDate, fn ($query) => $query->whereDate('exit_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('exit_date', '<=', $toDate))
            ->latest('exit_date')
            ->latest('id')
            ->with('product:id,name,category')
            ->paginate(10, ['*'], 'exits_page')
            ->withQueryString();

        return view('stock-report.index', compact(
            'products',
            'inventory',
            'entries',
            'exits',
            'totalProducts',
            'totalStock',
            'productId',
            'fromDate',
            'toDate',
        ));
    }
}
