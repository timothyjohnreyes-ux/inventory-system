<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalQuantity = Product::sum('quantity');

        $inventoryValue = Product::selectRaw('SUM(quantity * price) as total')->value('total') ?? 0;

        $lowStockProducts = Product::where('quantity', '<=', 10)->count();

        $outOfStockProducts = Product::where('quantity', 0)->count();

        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('dashboard', compact(
            
            'totalProducts',
            'totalCategories',
            'totalQuantity',
            'inventoryValue',
            'lowStockProducts',
            'outOfStockProducts',
            'recentProducts',
            'categories'
        ) );

    }
}
