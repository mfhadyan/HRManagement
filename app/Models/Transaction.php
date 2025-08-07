<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'date',
        'transaction_time',
        'cashier_id',
        'payment_method_id',
        'comments'
    ];

    protected $casts = [
        'date' => 'date',
        'transaction_time' => 'datetime',
    ];

    protected $appends = ['total_sales'];

    public function cashier()
    {
        return $this->belongsTo(Employee::class, 'cashier_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function getTotalSalesAttribute()
    {
        return $this->transactionDetails->sum(function($detail) {
            return $detail->quantity * $detail->historical_unit_price;
        });
    }

    public function paginate($count = 10)
    {
        return $this->with(['cashier', 'paymentMethod', 'transactionDetails.product'])->latest()->paginate($count);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByCashier($query, $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    public function scopeByPaymentMethod($query, $paymentMethod)
    {
        // Handle both old string-based payment method and new ID-based
        if (is_numeric($paymentMethod)) {
            return $query->where('payment_method_id', $paymentMethod);
        } else {
            return $query->whereHas('paymentMethod', function($q) use ($paymentMethod) {
                $q->where('payment_method_name', $paymentMethod);
            });
        }
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTransactionTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public static function generateTransactionId()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
} 