<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed seller_id
 * @property mixed status
 * @property mixed transactions
 * @property mixed categories
 * @property mixed seller
 * @property mixed quantity
 * @property mixed id
 * @property mixed image
 * @property mixed name
 * @property mixed description
 * @property mixed created_at
 * @property mixed updated_at
 */
class Product extends Model
{
    use SoftDeletes;

    public $transformer = ProductTransformer::class;

    const DISK_STORAGE = 'images_products';

    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $dates = ['delete_at'];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


}
