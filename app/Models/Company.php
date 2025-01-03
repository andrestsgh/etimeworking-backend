<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
		    
    protected $fillable = [
        'name',
        'city',
        'country',
        'email',
        'phone',
        'cif',
        'address',
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'company_cif', 'cif');
    }

}
