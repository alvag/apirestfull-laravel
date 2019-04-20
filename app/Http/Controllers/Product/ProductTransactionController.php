<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Validation\ValidationException;
use Response;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return Response
     * @throws ValidationException
     */
    public function index(Product $product)
    {
        return $this->showAll($product->transactions);
    }

}
