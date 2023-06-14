<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'loan_id',
        'amount',
        'status',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
