<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $casts=[
        'numbers'=> 'array',
    ];
    protected $fillable=[
        'identification_number',
        'name',
        'abono',
        'buying_date',
        'methodOfPayment',
        'responsible',
        'reference',
    ];
}
