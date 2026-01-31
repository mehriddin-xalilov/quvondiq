<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WarehouseStock;
use App\Models\StockMovement;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items.product']);

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(20);

        // Statistics
        $todaySales = Sale::today()->completed()->sum('total');
        $monthSales = Sale::thisMonth()->completed()->sum('total');
        $totalDebt = Sale::where('debt_amount', '>', 0)->sum('debt_amount');

        $customers = Customer::all();

        return view('sales.index', compact(
            'sales',
            'todaySales',
            'monthSales',
            'totalDebt',
            'customers'
        ));
    }

    public function create()
    {
        $products = Product::with('category')->where('is_active', true)->get();
        $customers = Customer::all();
        $prefillCustomer = [];
        $telegramOrderId = null;
        $customerId = null;
        $cart = [];

        return view('sales.create', compact('products', 'customers', 'prefillCustomer', 'telegramOrderId', 'customerId', 'cart'));
    }

    public function createFromTelegram($telegram_order_id)
    {
        $products = Product::with('category')->where('is_active', true)->get();
        $customers = Customer::all();
        $prefillCustomer = [];
        $telegramOrderId = null;
        $customerId = null;
        $cart = [];

        $telegramOrder = \App\Models\TelegramOrder::findOrFail($telegram_order_id);
        $telegramOrderId = $telegramOrder->id;
        
        // Create temporary customer or find existing if phone matches
        if (!empty($telegramOrder->customer_phone)) {
            $customer = \App\Models\Customer::firstOrCreate(
                ['phone' => $telegramOrder->customer_phone],
                [
                    'name' => $telegramOrder->customer_name,
                    'address' => $telegramOrder->customer_address,
                    'balance' => 0
                ]
            );
        } else {
            $customer = null;
        }
        
        // If new customer from Telegram (no phone or created), pass data to view to pre-fill
        if ($customer) {
            $customerId = $customer->id;
            // Add to customers list if not present
            if (!$customers->contains('id', $customer->id)) {
                $customers->push($customer);
            }
        } elseif (!$customer && !empty($telegramOrder->customer_name)) {
            $prefillCustomer = [
                'name' => $telegramOrder->customer_name,
                'phone' => $telegramOrder->customer_phone,
                'address' => $telegramOrder->customer_address,
            ];
        }    

        // Prepare cart items
        foreach ($telegramOrder->items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                $cart[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ];
            }
        }

        return view('sales.create', compact('products', 'customers', 'prefillCustomer', 'telegramOrderId', 'customerId', 'cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'payment_type' => 'required|in:cash,card,debt,mixed',
            'paid_cash' => 'nullable|numeric|min:0',
            'paid_card' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Handle new customer creation if data is provided
    if (empty($validated['customer_id']) && $request->filled('new_customer_name')) {
        $customer = \App\Models\Customer::firstOrCreate(
            ['phone' => $request->input('new_customer_phone')],
            [
                'name' => $request->input('new_customer_name'),
                // 'address' => $request->input('new_customer_address') // Add if available in form
                'balance' => 0
            ]
        );
        $validated['customer_id'] = $customer->id;
    }

    // Validate customer is required for debt
    if ($validated['payment_type'] === 'debt' && empty($validated['customer_id'])) {
        return back()->withErrors(['customer_id' => 'Qarz uchun mijoz tanlanishi shart (yoki yangi mijoz kiritilishi kerak)'])->withInput();
    }

    try {
        DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $discount = $validated['discount'] ?? 0;
            if (!empty($validated['discount_percent'])) {
                $discount = $subtotal * ($validated['discount_percent'] / 100);
            }

            $total = $subtotal - $discount;

            // Calculate payment
            $paidCash = $validated['paid_cash'] ?? 0;
            $paidCard = $validated['paid_card'] ?? 0;
            
            if ($validated['payment_type'] === 'cash') {
                $paidTotal = $total;
                $paidCash = $total;
                $paidCard = 0;
            } elseif ($validated['payment_type'] === 'card') {
                $paidTotal = $total;
                $paidCash = 0;
                $paidCard = $total;
            } elseif ($validated['payment_type'] === 'debt') {
                $paidTotal = 0;
                $paidCash = 0;
                $paidCard = 0;
            } else { // mixed
                $paidTotal = $paidCash + $paidCard;
            }

            $debtAmount = $total - $paidTotal;

            // Validate debt requires customer works for both 'debt' and 'mixed' types
            if ($debtAmount > 0.01 && empty($validated['customer_id'])) {
                throw new \Exception("Qarz yozish uchun mijoz tanlanishi shart. Yetmayotgan summa: " . number_format($debtAmount, 0, '.', ' ') . " so'm");
            }

            // Create sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'source' => 'offline',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'total' => $total,
                'payment_type' => $validated['payment_type'],
                'paid_cash' => $paidCash,
                'paid_card' => $paidCard,
                'paid_total' => $paidTotal,
                'debt_amount' => $debtAmount,
                'status' => 'completed',
                'notes' => $validated['notes'],
                'sale_date' => now(),
            ]);

            // Create sale items and update stock
            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Check stock availability
                $warehouseStock = WarehouseStock::where('product_id', $product->id)->first();
                if (!$warehouseStock || $warehouseStock->quantity < $itemData['quantity']) {
                    throw new \Exception("Mahsulot '{$product->name}' uchun yetarli qoldiq yo'q. Mavjud: " . ($warehouseStock->quantity ?? 0));
                }

                // Create sale item
                $subtotalItem = $itemData['quantity'] * $itemData['price'];
                $profit = ($itemData['price'] - $product->cost_price) * $itemData['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'cost_price' => $product->cost_price,
                    'discount' => 0,
                    'subtotal' => $subtotalItem,
                    'profit' => $profit,
                ]);

                // Update warehouse stock
                $quantityBefore = $warehouseStock->quantity;
                $quantityAfter = $quantityBefore - $itemData['quantity'];
                
                $warehouseStock->update([
                    'quantity' => $quantityAfter,
                    'last_stock_out_at' => now(),
                ]);

                // Create stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $quantityAfter,
                    'price_per_unit' => $itemData['price'],
                    'notes' => "Sotuv: {$sale->invoice_number}",
                    'user_id' => auth()->id(),
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'movement_date' => now(),
                ]);
            }

            // Create debt if needed
            if ($debtAmount > 0 && $validated['customer_id']) {
                Debt::create([
                    'customer_id' => $validated['customer_id'],
                    'sale_id' => $sale->id,
                    'amount' => $debtAmount,
                    'remaining_amount' => $debtAmount,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(30),
                ]);

                // Update customer total debt
                $customer = Customer::find($validated['customer_id']);
                $customer->increment('total_debt', $debtAmount);
            }

            // Update customer statistics
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                $customer->increment('total_purchases', $total);
                $customer->increment('total_orders');
            }

            // Update Telegram Order status if linked
            if ($request->filled('telegram_order_id')) {
                $telegramOrder = \App\Models\TelegramOrder::find($request->input('telegram_order_id'));
                if ($telegramOrder) {
                    $telegramOrder->update([
                        'status' => 'completed',
                        'sale_id' => $sale->id
                    ]);
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                session()->flash('success', 'Sotuv muvaffaqiyatli amalga oshirildi');
                return response()->json([
                    'success' => true,
                    'redirect' => route('sales.show', $sale->id),
                ]);
            }

            return redirect()->route('sales.show', $sale->id)->with('success', 'Sotuv muvaffaqiyatli amalga oshirildi');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => ['error' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        $shopInfo = \App\Models\ShopInformation::first();
        
        return view('sales.show', compact('sale', 'shopInfo'));
    }

    public function destroy(Sale $sale, \Illuminate\Http\Request $request)
    {
        try {
            DB::beginTransaction();

            // Return stock
            foreach ($sale->items as $item) {
                $warehouseStock = WarehouseStock::where('product_id', $item->product_id)->first();
                if ($warehouseStock) {
                    $warehouseStock->increment('quantity', $item->quantity);
                }
            }

            // Remove debt
            if ($sale->debt) {
                $sale->debt->delete();
                if ($sale->customer) {
                    $sale->customer->decrement('total_debt', $sale->debt_amount);
                }
            }

            // Update customer statistics
            if ($sale->customer) {
                $sale->customer->decrement('total_purchases', $sale->total);
                $sale->customer->decrement('total_orders');
            }

            // Soft delete sale
            $sale->delete();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Sotuv bekor qilindi']);
            }

            return redirect()->route('sales.index')->with('success', 'Sotuv bekor qilindi');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Xatolik: ' . $e->getMessage()], 500);
            }

            return back()->withErrors(['error' => 'Xatolik: ' . $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items.product']);

        $isFiltered = false;

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
            $isFiltered = true;
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
            $isFiltered = true;
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
            $isFiltered = true;
        }

        // Payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
            $isFiltered = true;
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $isFiltered = true;
        }

        if ($isFiltered) {
            $sales = $query->orderBy('sale_date', 'desc')->get();
        } else {
            $sales = $query->orderBy('sale_date', 'desc')->take(50)->get();
        }

        return Excel::download(new SalesExport($sales), 'sales.xlsx');
    }
}
