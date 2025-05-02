<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NicheState extends Model
{
    //
    protected $table = 'niches_states';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}
