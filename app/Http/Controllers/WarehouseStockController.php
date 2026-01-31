<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStock;
use App\Models\Category;
use Illuminate\Http\Request;

class WarehouseStockController extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseStock::with(['product.category']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;
                case 'low_stock':
                    $query->where('quantity', '>', 0)
                          ->where('quantity', '<=', 10);
                    break;
                case 'in_stock':
                    $query->where('quantity', '>', 10);
                    break;
            }
        }

        $stocks = $query->orderBy('quantity', 'asc')->paginate(20);

        // Statistics
        $totalProducts = WarehouseStock::count();
        $outOfStock = WarehouseStock::where('quantity', '<=', 0)->count();
        $lowStock = WarehouseStock::where('quantity', '>', 0)
                                  ->where('quantity', '<=', 10)
                                  ->count();
        $inStock = WarehouseStock::where('quantity', '>', 10)->count();

        $categories = Category::all();

        return view('warehouse-stocks.index', compact(
            'stocks',
            'totalProducts',
            'outOfStock',
            'lowStock',
            'inStock',
            'categories'
        ));
    }
}
