<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $guarded = [];

    /**
     * Get all of the f&b transactions.
     */
    public function trx_guests()
    {
        return $this->hasMany(TrxGuest::class,'guest_id', 'id');
    }
}
