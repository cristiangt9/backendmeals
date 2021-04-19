<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function meals()
    {
        return $this->belongsToMany(Meal::class)->withPivot(['amount'])->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
