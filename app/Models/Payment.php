<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $fillable = [
    'contract_id',
    'observations',
    'paid',
    'payment_date',
    'amount',
    'correlative',
  ];

  // public function contract()
  // {
  //   return $this->belongsTo(Contract::class, 'contract_id');
  // }
}
