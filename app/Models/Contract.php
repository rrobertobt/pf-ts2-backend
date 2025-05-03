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
}
