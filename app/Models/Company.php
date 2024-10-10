<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'nrc', 'nit', 'phone', 'whatsapp', 'email', 'logo', 'economic_activity_id', 'country_id', 'departamento_id','distrito_id', 'address', 'web', 'api_key'];
    protected $casts = [
        'logo' => 'array',
    ];
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
    public function economic_activity()
    {
        return $this->belongsTo(EconomicActivity::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


}
