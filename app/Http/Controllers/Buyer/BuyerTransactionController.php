<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Response;
use Illuminate\Validation\ValidationException;

class BuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @param Buyer $buyer
     * @return Response
     * @throws ValidationException
     */
    public function index(Buyer $buyer)
    {
        return $this->showAll($buyer->transactions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Buyer $buyer
     * @return Response
     */
    public function show(Buyer $buyer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Buyer $buyer
     * @return Response
     */
    public function edit(Buyer $buyer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Buyer $buyer
     * @return Response
     */
    public function update(Request $request, Buyer $buyer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Buyer $buyer
     * @return Response
     */
    public function destroy(Buyer $buyer)
    {
        //
    }
}
