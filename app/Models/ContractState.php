<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractState extends Model
{
    protected $table = 'contract_states';

    protected $fillable = [
      'name',
      'slug',
      'description',
  ];
}
