<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'sign_time',
        'latitude',
        'longitude',
        'finished',
    ];
    protected $casts = [
        'sign_time' => 'datetime',
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function toArray()
    {
        return $this->attributesToArray();
    }
    
}
