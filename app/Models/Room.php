<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    /**
     * Get all of transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'room_id', 'id');
    }
}
