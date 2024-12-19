<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBoxOpen extends Model
{
    protected $fillable = [
          'cashbox_id',
          'open_employee_id',
          'opened_at',
          'open_amount',
          'saled_amount',
          'ordered_amount',
          'out_cash_amount',
          'in_cash_amount',
          'closed_amount',
          'closed_at',
          'close_employee_id',
          'status',
      ];


    public function cashbox()
    {
        return $this->belongsTo(CashBox::class);
    }
    public function openEmployee()
    {
        return $this->belongsTo(Employee::class,'open_employee_id');
    }
    public function closeEmployee()
    {
        return $this->belongsTo(Employee::class,'close_employee_id');
    }


}
