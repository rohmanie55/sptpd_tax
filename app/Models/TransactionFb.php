<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFb extends Model
{
    protected $guarded = [];

    /**
     * Get room associated with the transaction.
     */
    public function fab()
    {
        return $this->hasOne(FoodBaverage::class, 'id','fab_id');
    }
}
