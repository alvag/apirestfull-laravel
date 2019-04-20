<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Response;
use Illuminate\Validation\ValidationException;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws ValidationException
     */
    public function index()
    {
        $sellers = Seller::has('products')->get();
        return $this->showAll($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param Seller $seller
     * @return Response
     */
    public function show(Seller $seller)
    {
        return $this->showOne($seller);
    }

}
