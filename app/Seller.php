<?php

namespace App;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

/**
 * @property mixed products
 */
class Seller extends User
{

    public $transformer = SellerTransformer::class;

    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope(new SellerScope());
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
