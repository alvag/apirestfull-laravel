<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Response;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @param Transaction $transaction
     * @return Response
     * @throws ValidationException
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;
        return $this->showAll($categories);
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
