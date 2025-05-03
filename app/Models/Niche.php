<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niche extends Model
{
    /** @use HasFactory<\Database\Factories\NicheFactory> */
    use HasFactory;

    protected $fillable = [
        'state_id',
        'code',
        'avenue_location',
        'street_location',
        'type_id',
        'is_historical',
    ];

    public function state()
    {
        return $this->belongsTo(NicheState::class);
    }

    public function type()
    {
        return $this->belongsTo(NichesType::class);
    }

    public function occupant()
    {
        return $this->hasOne(Occupant::class, 'current_niche_id');
    }
}
