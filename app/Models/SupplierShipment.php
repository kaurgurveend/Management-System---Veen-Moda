<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'product_name',
        'quantity_pieces',
        'payment_status',
        'payment_proof',
        'paid_at',
        'due_date',
        'cost_price',
        'additional_costs',
        'received_date',
        'notes'
    ];

    protected $casts = [
        'received_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'cost_price' => 'decimal:2',
        'additional_costs' => 'decimal:2',
        'hpp' => 'decimal:2'
    ];

    public function isPaid()
    {
        return $this->payment_status === 'lunas';
    }

    public function isDebt()
    {
        return $this->payment_status === 'hutang';
    }

    public function getTotalCostAttribute()
    {
        return $this->cost_price * $this->quantity_pieces;
    }

    public function getTotalHppAttribute()
    {
        return $this->hpp * $this->quantity_pieces;
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }
        return now()->diffInDays($this->due_date, false);
    }

    public function getIsApproachingDueAttribute()
    {
        if (!$this->due_date || $this->isPaid()) {
            return false;
        }
        $daysUntil = $this->days_until_due;
        return $daysUntil !== null && $daysUntil <= 42 && $daysUntil >= 0; // 6 weeks = 42 days
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date || $this->isPaid()) {
            return false;
        }
        return $this->due_date->isPast();
    }
}