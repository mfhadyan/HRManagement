<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
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
        if ($request->filled('payment_method')) {
            $query->byPaymentMethod($request->payment_method);
        }

        $reports = $query->orderBy('date', 'desc')->paginate(10);
        $cashiers = Employee::all();
        $paymentMethods = PaymentMethod::all();

        return view('pages.reports', compact('reports', 'cashiers', 'paymentMethods'));
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
        
        return view('pages.reports_create', compact('cashiers', 'paymentMethods', 'products'));
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

        return redirect()->route('reports')->with('status', 'Transaction report created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $report)
    {
        $report->load(['cashier', 'paymentMethod', 'transactionDetails.product']);
        return view('pages.reports_show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $report)
    {
        $cashiers = Employee::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();
        
        return view('pages.reports_edit', compact('report', 'cashiers', 'paymentMethods', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $report)
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

        $report->update([
            'date' => $request->date,
            'transaction_time' => $request->transaction_time,
            'cashier_id' => $request->cashier_id,
            'payment_method_id' => $request->payment_method_id,
            'comments' => $request->comments
        ]);

        // Delete existing transaction details
        $report->transactionDetails()->delete();

        // Create new transaction details
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            $report->transactionDetails()->create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'historical_unit_price' => $product->unit_price
            ]);
        }

        return redirect()->route('reports')->with('status', 'Transaction report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $report)
    {
        $report->delete();
        return redirect()->route('reports')->with('status', 'Transaction report deleted successfully.');
    }

    /**
     * Print reports
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
        if ($request->filled('payment_method')) {
            $query->byPaymentMethod($request->payment_method);
        }

        $reports = $query->orderBy('date', 'desc')->get();
        
        return view('pages.reports_print', compact('reports'));
    }

    /**
     * Get daily summary
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dailySummary(Request $request)
    {
        $date = $request->filled('date') ? $request->date : today()->format('Y-m-d');
        
        $reports = Transaction::with(['cashier', 'paymentMethod', 'transactionDetails.product'])
            ->byDate($date)
            ->orderBy('date', 'desc')
            ->get();

        $totalSales = $reports->sum('total_sales');
        $totalTransactions = $reports->count();
        $totalQuantity = $reports->sum(function($transaction) {
            return $transaction->transactionDetails->sum('quantity');
        });

        return view('pages.reports_daily_summary', compact('reports', 'date', 'totalSales', 'totalTransactions', 'totalQuantity'));
    }
} 