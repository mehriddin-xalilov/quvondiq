<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Statistics
        $stats = [
            'total_this_month' => Expense::whereYear('expense_date', date('Y'))
                ->whereMonth('expense_date', date('m'))
                ->sum('amount'),
            'total_today' => Expense::whereDate('expense_date', today())->sum('amount'),
            'total_all' => $query->sum('amount'), // Based on filter or total? Let's show filtered total
        ];

        $expenses = $query->orderBy('expense_date', 'desc')->latest()->paginate(20);

        // Get unique categories for filter
        $categories = Expense::select('category')->distinct()->pluck('category');

        return view('expenses.index', compact('expenses', 'stats', 'categories'));
    }

    public function create()
    {
        // Predefined categories
        $categories = ['Ijara', 'Kommunal', 'Ish haqi', 'Tushlik', 'Transport', 'Ofis jihozlari', 'Boshqa'];
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,card',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Xarajat muvaffaqiyatli qo\'shildi');
    }

    public function edit(Expense $expense)
    {
        $categories = ['Ijara', 'Kommunal', 'Ish haqi', 'Tushlik', 'Transport', 'Ofis jihozlari', 'Boshqa'];
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,card',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Xarajat yangilandi');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Xarajat o\'chirildi');
    }

    public function export(Request $request)
    {
        $query = Expense::with('user');

        $isFiltered = false;

        if ($request->filled('category')) {
            $query->where('category', $request->category);
            $isFiltered = true;
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
            $isFiltered = true;
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
            $isFiltered = true;
        }

        if ($isFiltered) {
            $expenses = $query->orderBy('expense_date', 'desc')->latest()->get();
        } else {
            $expenses = $query->orderBy('expense_date', 'desc')->latest()->take(50)->get();
        }

        return Excel::download(new ExpensesExport($expenses), 'expenses.xlsx');
    }
}
