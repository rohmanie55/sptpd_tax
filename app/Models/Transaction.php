<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    /**
     * Get all of the guest transactions.
     */
    public function guests()
    {
        return $this->hasMany(TrxGuest::class,'trx_id', 'id');
    }

    /**
     * Get all of the f&b transactions.
     */
    public function fabs()
    {
        return $this->hasMany(TransactionFb::class,'trx_id', 'id');
    }

    /**
     * Get room associated with the transaction.
     */
    public function room()
    {
        return $this->hasOne(Room::class, 'id','room_id');
    }

    /**
     * Get company associated with the transaction.
     */
    public function company()
    {
        return $this->hasOne(Companie::class, 'id','company_id');
    }
}
