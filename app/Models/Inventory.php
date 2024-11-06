<?php

namespace App\Models;

use App\Helpers\KardexHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'branch_id',
        'stock',
        'stock_min',
        'stock_max',
        'cost_without_taxes',
        'cost_with_taxes',
        'is_stock_alert',
        'is_expiration_date',
        'is_active',
    ];

    protected static function booted()
    {
        parent::booted();
        static::saved(function ($inventory) {
            KardexHelper::createKardexFromInventory(
                $inventory,
                'Inventario Inicial',             // operation_type
                'Iventario Inicial',                   // operation_id
                0,                 // operation_detail_id
                'Inventario Inicial',           // document_type
                '',          // document_number
                'Inventario Inicial',       // entity
                'Salvadoreña'       // nationality
            );


//            $kardex = new Kardex();
//            $kardex->branch_id = $inventory->branch_id;
//            $kardex->date = now();
//            $kardex->operation_type = 'Inventario Inicial';
//            $kardex->operation_id = 0;
//            $kardex->operation_detail_id = 0;
//            $kardex->document_type = 'Inventario Inicial';
//            $kardex->document_number = 'Inventario Inicial';
//            $kardex->entity = 'Inventario Inicial';
//            $kardex->nationality = 'Salvadoreña';
//            $kardex->inventory_id = $inventory->id;
//            $kardex->previous_stock = 0;
//            $kardex->stock_in = $inventory->stock;
//            $kardex->stock_out = 0;
//            $kardex->stock_actual = $inventory->stock;
//            $kardex->money_in = $inventory->cost_without_taxes * $inventory->stock;
//            $kardex->money_out = 0;
//            $kardex->money_actual = $inventory->cost_without_taxes * $inventory->stock;
//            $kardex->sale_price = $inventory->product->prices->where('type', 'sale')->first()->price??0;
//            $kardex->purchase_price = $inventory->cost_without_taxes;
//            $kardex->save();
//            dd($inventory);  / Muestra el registro antes de guardarlo
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

}
