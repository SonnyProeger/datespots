<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    public function places()
    {
        return $this->belongsToMany(DateSpot::class, 'date_spot_type');
    }

}
