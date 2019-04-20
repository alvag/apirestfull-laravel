<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identificador'      => (int)$product->id,
            'titulo'             => (string)$product->name,
            'detalles'           => (string)$product->description,
            'disponibles'        => (int)$product->quantity,
            'estado'             => (string)$product->status,
            'imagen'             => url("images/products/{$product->image}"),
            'vendedor'           => (int)$product->seller_id,
            'fechaCreacion'      => (string)$product->created_at,
            'fechaActualizacion' => (string)$product->updated_at,
            'fechaEliminacion'   => isset($product->deleted_at) ? (string)$product->deleted_at : null
        ];
    }

    /**
     * @param $index
     * @return mixed|null
     */
    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador'      => 'id',
            'titulo'             => 'name',
            'detalles'           => 'description',
            'disponibles'        => 'quantity',
            'estado'             => 'status',
            'imagen'             => 'image',
            'vendedor'           => 'seller_id',
            'fechaCreacion'      => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion'   => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}