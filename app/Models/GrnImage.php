<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnImage extends Model
{
     protected $fillable = ['grn_session_id', 'image_path'];

    public function grnSession()
    {
        return $this->belongsTo(GrnSession::class);
    }
}
