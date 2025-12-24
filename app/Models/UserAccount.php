<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $fillable = ['user_id', 'balance', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
