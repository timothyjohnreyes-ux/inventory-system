<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $totalProducts = Product::where('is_active', true)->count();
        $archivedProducts = Product::where('is_active', false)->count();
        $totalCategories = Category::count();
        $totalQuantity = Product::sum('quantity');

        $inventoryValue = Product::selectRaw('SUM(quantity * price) as total')->value('total') ?? 0;

        $lowStockProducts = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();

        $outOfStockProducts = Product::where('is_active', true)
            ->where('quantity', 0)
            ->count();

        $recentProducts = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'archivedProducts',
            'totalCategories',
            'totalQuantity',
            'inventoryValue',
            'lowStockProducts',
            'outOfStockProducts',
            'recentProducts',
            'categories'
        ));
    }
}
