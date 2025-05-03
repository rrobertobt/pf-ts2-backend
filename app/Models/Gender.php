<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    //
    public function occupants()
    {
        return $this->hasMany(Occupant::class);
    }
}
