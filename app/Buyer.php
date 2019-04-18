<?php

namespace App;

use App\Scopes\BuyerScope;

class Buyer extends User
{

    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope(new BuyerScope());
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
