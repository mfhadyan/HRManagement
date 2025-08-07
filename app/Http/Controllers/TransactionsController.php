<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    private $transactions;

    public function __construct()
    {
        $this->middleware('auth');
        $this->transactions = resolve(Transaction::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['cashier', 'paymentMethod', 'transactionDetails.product']);

        // Filter by date
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter by cashier
        if ($request->filled('cashier_id')) {
            $query->byCashier($request->cashier_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method_id')) {
            $query->byPaymentMethod($request->payment_method_id);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(10);
        $cashiers = Employee::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();

        return view('pages.transactions', compact('transactions', 'cashiers', 'paymentMethods', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cashiers = Employee::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        
        return view('pages.transactions_create', compact('cashiers', 'paymentMethods', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'transaction_time' => 'required',
            'cashier_id' => 'required|exists:employees,id',
            'payment_method_id' => 'required|exists:payment_methods,payment_method_id',
            'comments' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction = Transaction::create([
            'date' => $request->date,
            'transaction_time' => $request->transaction_time,
            'cashier_id' => $request->cashier_id,
            'payment_method_id' => $request->payment_method_id,
            'comments' => $request->comments
        ]);

        // Create transaction details
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $transaction->transactionDetails()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'historical_unit_price' => $product->unit_price
            ]);
        }

        return redirect()->route('transactions')->with('status', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['cashier', 'paymentMethod', 'transactionDetails.product']);
        return view('pages.transactions_show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $cashiers = Employee::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        
        return view('pages.transactions_edit', compact('transaction', 'cashiers', 'paymentMethods', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'date' => 'required|date',
            'transaction_time' => 'required',
            'cashier_id' => 'required|exists:employees,id',
            'payment_method_id' => 'required|exists:payment_methods,payment_method_id',
            'comments' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction->update([
            'date' => $request->date,
            'transaction_time' => $request->transaction_time,
            'cashier_id' => $request->cashier_id,
            'payment_method_id' => $request->payment_method_id,
            'comments' => $request->comments
        ]);

        // Delete existing transaction details
        $transaction->transactionDetails()->delete();

        // Create new transaction details
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $transaction->transactionDetails()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'historical_unit_price' => $product->unit_price
            ]);
        }

        return redirect()->route('transactions')->with('status', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions')->with('status', 'Transaction deleted successfully.');
    }

    /**
     * Print transactions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        $query = Transaction::with(['cashier', 'paymentMethod', 'transactionDetails.product']);

        // Filter by date
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Filter by cashier
        if ($request->filled('cashier_id')) {
            $query->byCashier($request->cashier_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method_id')) {
            $query->byPaymentMethod($request->payment_method_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        
        return view('pages.transactions_print', compact('transactions'));
    }

    /**
     * Get summary (daily, weekly, monthly)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $date = $request->filled('date') ? $request->date : today()->format('Y-m-d');
        
        $query = Transaction::with(['cashier', 'paymentMethod', 'transactionDetails.product']);

        switch ($period) {
            case 'weekly':
                $startDate = Carbon::parse($date)->startOfWeek();
                $endDate = Carbon::parse($date)->endOfWeek();
                $query->byDateRange($startDate, $endDate);
                break;
            case 'monthly':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
                $query->byDateRange($startDate, $endDate);
                break;
            default: // daily
                $query->byDate($date);
                break;
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $totalSales = $transactions->sum('total_sales');
        $totalTransactions = $transactions->count();
        $totalQuantity = $transactions->sum(function($transaction) {
            return $transaction->transactionDetails->sum('quantity');
        });

        return view('pages.transactions_summary', compact('transactions', 'date', 'period', 'totalSales', 'totalTransactions', 'totalQuantity'));
    }
} 