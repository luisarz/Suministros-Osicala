<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBoxCorrelative extends Model
{
   protected $fillable = [
       'cash_box_id',
       'document_type_id',
       'serie',
       'start_number',
       'end_number',
       'current_number',
       'is_active',
   ];
}
