<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Validation\ValidationException;
use Response;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return Response
     * @throws ValidationException
     */
    public function index(Product $product)
    {
        $buyers = $product->transactions()
            ->with('buyer')
            ->get()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }

}
