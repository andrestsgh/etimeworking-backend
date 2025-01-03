<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
		    
    protected $fillable = [
        'user_dni',
        'type',
        'begin_date',
        'end_date',
        'company_cif',
        'hours',
        'periodicity',
        'job_position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_dni', 'dni');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_cif', 'cif');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

}
