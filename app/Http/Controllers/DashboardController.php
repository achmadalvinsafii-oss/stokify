<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::query()
            ->where('user_id', Auth::id())
            ->count();
        $lowStockCount = Product::query()
            ->where('user_id', Auth::id())
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();
        $totalStock = Product::query()
            ->where('user_id', Auth::id())
            ->sum('stock');
        $products = Product::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('totalProducts', 'lowStockCount', 'totalStock', 'products'));
    }
}
