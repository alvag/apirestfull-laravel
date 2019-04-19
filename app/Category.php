<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed description
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed deleted_at
 */
class Category extends Model
{
    use SoftDeletes;

    public $transformer = CategoryTransformer::class;

    protected $dates = ['delete_at'];
    protected $fillable = [
        'name',
        'description'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
