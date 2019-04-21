<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;
use Response;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @param Transaction $transaction
     * @return Response
     */
    public function index(Transaction  $transaction)
    {
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
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
     * @param Transaction $transaction
     * @return Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Transaction $transaction
     * @return Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Transaction $transaction
     * @return Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Transaction $transaction
     * @return Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
