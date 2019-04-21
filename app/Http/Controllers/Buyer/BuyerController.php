<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Validation\ValidationException;
use Response;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws ValidationException
     */
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param Buyer $buyer
     * @return Response
     */
    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

}
