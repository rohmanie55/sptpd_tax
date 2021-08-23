<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxSptpd extends Model
{
    protected $guarded = [];
    /**
     * Get room associated with the transaction.
     */
    public function insert()
    {
        return $this->hasOne(\App\User::class, 'id','create_by');
    }

        /**
     * Get room associated with the transaction.
     */
    public function approve()
    {
        return $this->hasOne(\App\User::class, 'id','approve_by');
    }
}
