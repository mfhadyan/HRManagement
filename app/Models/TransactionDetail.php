<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_detail_id';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'historical_unit_price'
    ];

    protected $casts = [
        'historical_unit_price' => 'decimal:2',
    ];

    protected $appends = ['total_amount'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getTotalAmountAttribute()
    {
        return $this->quantity * $this->historical_unit_price;
    }
} 