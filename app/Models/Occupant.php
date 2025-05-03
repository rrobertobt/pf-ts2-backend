<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'bith_location',
        'dpi',
        'death_date',
        'death_location',
        'death_cause',
        'observations',
        'gender_id',
        'current_niche_id',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'death_date' => 'date',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function currentNiche()
    {
        return $this->belongsTo(Niche::class, 'current_niche_id');
    }
}
