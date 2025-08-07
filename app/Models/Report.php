<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'transaction_date',
        'transaction_id',
        'product_name',
        'quantity',
        'unit_price',
        'payment_method',
        'cashier_id',
        'transaction_time',
        'comments'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'transaction_time' => 'datetime',
        'unit_price' => 'decimal:2',
    ];

    protected $appends = ['total_sales'];

    public function cashier()
    {
        return $this->belongsTo(Employee::class, 'cashier_id');
    }

    public function getTotalSalesAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function paginate($count = 10)
    {
        return $this->with('cashier')->latest()->paginate($count);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('transaction_date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeByCashier($query, $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    public function scopeByPaymentMethod($query, $paymentMethod)
    {
        return $query->where('payment_method', $paymentMethod);
    }

    public function getTransactionDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTransactionTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public function getUnitPriceAttribute($value)
    {
        return $value;
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
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