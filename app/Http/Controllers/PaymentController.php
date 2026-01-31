<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['customer', 'sale'])->latest();

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $payments = $query->paginate(20);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $customer = null;
        if ($request->has('customer_id')) {
            $customer = Customer::find($request->customer_id);
        }
        
        $customers = Customer::where('total_debt', '>', 0)->orderBy('name')->get();

        return view('payments.create', compact('customer', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card',
            'notes' => 'nullable|string|max:1000',
            'payment_date' => 'nullable|date',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        
        // Prevent paying more than total debt (optional, but good for data integrity)
        // For now, allow it, but maybe warn? No, let's allow overpayment to go to balance if needed, 
        // but current logic is simplify: just decrease debt.
        
        try {
            DB::beginTransaction();

            // Create Payment Record
            $payment = Payment::create([
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'payment_date' => $validated['payment_date'] ?? now(),
            ]);

            // FIFO Debt Repayment Logic
            $remainingPayment = $validated['amount'];
            
            // Get unpaid debts ordered by date (Oldest first)
            $debts = Debt::where('customer_id', $customer->id)
                ->where('status', '!=', 'paid')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($debts as $debt) {
                if ($remainingPayment <= 0) break;

                $debtBalance = $debt->amount - $debt->paid_amount;

                if ($remainingPayment >= $debtBalance) {
                    // Fully pay this debt
                    $paymentAmountForDebt = $debtBalance;
                    
                    $debt->paid_amount += $paymentAmountForDebt;
                    $debt->status = 'paid';
                    $debt->save();
                    
                    $remainingPayment -= $paymentAmountForDebt;

                    // Update Sale status to paid/completed if needed
                    // Assuming Sale has 'payment_status' or we rely on Debt status. 
                    // Let's check Sale model later. For now focus on Debt.
                    
                } else {
                    // Partially pay this debt
                    $debt->paid_amount += $remainingPayment;
                    $debt->status = 'partial';
                    $debt->save();
                    
                    $remainingPayment = 0;
                }
            }

            // Update Customer Total Debt
            // Recalculate from Debts or just decrement? 
            // Better to decrement for performance, but verify with recalc if needed.
            $customer->decrement('total_debt', $validated['amount']);

            DB::commit();

            return redirect()->route('customers.show', $customer->id)
                ->with('success', 'To\'lov qabul qilindi va qarzlar yangilandi');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Xatolik yuz berdi: ' . $e->getMessage()])->withInput();
        }
    }
}
