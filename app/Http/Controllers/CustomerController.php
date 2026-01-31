<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Regular customer filter
        if ($request->filled('is_regular')) {
            $query->where('is_regular', $request->is_regular === '1');
        }

        // Debt filter
        if ($request->filled('has_debt')) {
            if ($request->has_debt === '1') {
                $query->where('total_debt', '>', 0);
            } else {
                $query->where('total_debt', '=', 0);
            }
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('customers.partials.table-body', compact('customers'))->render(),
                'pagination' => $customers->links('pagination::tailwind')->toHtml(),
                'total' => $customers->total(),
            ]);
        }

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'telegram_username' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_regular' => 'boolean',
            'telegram_notifications' => 'boolean',
        ]);

        $validated['is_regular'] = $request->has('is_regular');
        $validated['telegram_notifications'] = $request->has('telegram_notifications');
        $validated['total_debt'] = 0;
        $validated['total_purchases'] = 0;
        $validated['total_orders'] = 0;

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Mijoz muvaffaqiyatli qo\'shildi');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'sales' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'payments' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'debts' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'telegram_username' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_regular' => 'boolean',
            'telegram_notifications' => 'boolean',
        ]);

        $validated['is_regular'] = $request->has('is_regular');
        $validated['telegram_notifications'] = $request->has('telegram_notifications');

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Mijoz ma\'lumotlari yangilandi');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->total_debt > 0) {
            return back()->with('error', 'Diqqat! Ushbu mijozda qarzdorlik mavjud. Uni o\'chirib bo\'lmaydi.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Mijoz muvaffaqiyatli o\'chirildi');
    }

    public function export(Request $request)
    {
        $query = Customer::query();

        $isFiltered = false;

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
            $isFiltered = true;
        }

        // Regular customer filter
        if ($request->filled('is_regular')) {
            $query->where('is_regular', $request->is_regular === '1');
            $isFiltered = true;
        }

        // Debt filter
        if ($request->filled('has_debt')) {
            if ($request->has_debt === '1') {
                $query->where('total_debt', '>', 0);
            } else {
                $query->where('total_debt', '=', 0);
            }
            $isFiltered = true;
        }

        if ($isFiltered) {
            $customers = $query->orderBy('created_at', 'desc')->get();
        } else {
            $customers = $query->orderBy('created_at', 'desc')->take(50)->get();
        }

        return Excel::download(new CustomersExport($customers), 'customers.xlsx');
    }
}
