<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{

    protected $fillable = [
        'sale_id',
        'inventory_id',
        'description',
        'quantity',
        'price_base',
        'price',
        'discount',
        'total',
        'is_except',
        'exemptSale',
        'tributes',
    ];

    protected static function booted()
    {
        parent::booted();

        // Evento al crear un registro
        static::creating(function ($item) {
            if (!isset($item->price_base)) {
                $item->price_base = $item->price; // o cualquier valor por defecto
            }

        });
    }
    protected $casts = [
        'tributes' => 'array',
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function whereHouse()
    {
        return $this->belongsTo(Branch::class, 'wherehouse_id','id');

    }
}
