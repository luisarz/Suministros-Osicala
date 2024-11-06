<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use softDeletes;

    protected $fillable = [
        'document_type_id',
        'document_internal_number',
        'wherehouse_id',
        'seller_id',
        'customer_id',
        'operation_condition_id',
        'payment_method_id',
        'sales_payment_status',
        'status',
        'is_taxed',
        'net_amount',
        'iva',
        'discount',
        'retention',
        'total',
        'cash',
        'change',
        'casher_id',

    ];

    public function wherehouse()
    {
        return $this->belongsTo(Branch::class);
    }

    public function documenttype()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function seller()
    {
        return $this->belongsTo(Employee::class);

    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public  function salescondition()
    {
        return $this->belongsTo(OperationCondition::class, 'operation_condition_id');

    }
    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function casher()
    {
        return $this->belongsTo(Employee::class, 'casher_id');
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function inventories()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function dteProcesado()
    {
        return $this->hasOne(HistoryDte::class,'sales_invoice_id');
    }
}