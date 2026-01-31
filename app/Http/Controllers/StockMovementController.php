<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StockMovementsExport;
use Maatwebsite\Excel\Facades\Excel;

class StockMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-stock-movements')->only(['index']);
        $this->middleware('permission:create-stock-movements')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }

        $movements = $query->orderBy('movement_date', 'desc')->paginate(20);
        $products = Product::orderBy('name')->get();

        return view('stock-movements.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::with('stock')->orderBy('name')->get();
        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_unit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::findOrFail($validated['product_id']);
            
            // Get or create warehouse stock
            $warehouseStock = WarehouseStock::firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => 0, 'reserved_quantity' => 0]
            );

            $quantityBefore = $warehouseStock->quantity;
            $quantity = $validated['quantity'];

            // Calculate quantity after based on type
            switch ($validated['type']) {
                case 'in':
                    $quantityAfter = $quantityBefore + $quantity;
                    break;
                case 'out':
                    // Validate sufficient stock
                    if ($quantity > $quantityBefore) {
                        throw new \Exception('Qoldiqda yetarli mahsulot yo\'q. Hozirgi qoldiq: ' . $quantityBefore);
                    }
                    $quantityAfter = $quantityBefore - $quantity;
                    break;
                case 'adjustment':
                    $quantityAfter = $quantity;
                    break;
            }

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'price_per_unit' => $validated['price_per_unit'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
                'movement_date' => now(),
            ]);

            // Update warehouse stock
            $warehouseStock->update(['quantity' => $quantityAfter]);
        });

        return redirect()->route('stock-movements.index')->with('success', 'Ombor harakati muvaffaqiyatli saqlandi');
    }

    public function export(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        $isFiltered = false;

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
            $isFiltered = true;
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
            $isFiltered = true;
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
            $isFiltered = true;
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
            $isFiltered = true;
        }

        if ($isFiltered) {
            $movements = $query->orderBy('movement_date', 'desc')->get();
        } else {
            $movements = $query->orderBy('movement_date', 'desc')->take(50)->get();
        }

        return Excel::download(new StockMovementsExport($movements), 'stock_movements.xlsx');
    }
}
