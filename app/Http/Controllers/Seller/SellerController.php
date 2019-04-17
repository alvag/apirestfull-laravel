<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sellers = Seller::has('products')->get();
        return $this->sendResponse($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $seller = Seller::has('products')->findOrFail($id);
        return $this->sendResponse($seller);
    }

}
