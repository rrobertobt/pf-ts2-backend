<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    // relate it to the user
    protected $fillable = [
        'user_id',
        'observations',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
