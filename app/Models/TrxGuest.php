<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxGuest extends Model
{
    protected $guarded = [];

    /**
     * Get room associated with the transaction.
     */
    public function guest()
    {
        return $this->hasOne(Guest::class, 'id','guest_id');
    }
}
