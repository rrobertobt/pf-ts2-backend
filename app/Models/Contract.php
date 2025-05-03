<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
  protected $fillable = [
    'start_date',
    'end_date',
    'price',
    'occupant_id',
    'niche_id',
    'representative_user_id',
    'state_id',
  ];

  public function occupant()
  {
    return $this->belongsTo(Occupant::class, 'occupant_id');
  }

  public function niche()
  {
    return $this->belongsTo(Niche::class, 'niche_id');
  }

  public function representative()
  {
    return $this->belongsTo(User::class, 'representative_user_id');
  }

  public function state()
  {
    return $this->belongsTo(ContractState::class, 'state_id');
  }
  public function payments()
  {
    return $this->hasMany(Payment::class);
  }
  public function currentPayment()
  {
    return $this->hasOne(Payment::class, 'id', 'current_payment_id');
  }
}
