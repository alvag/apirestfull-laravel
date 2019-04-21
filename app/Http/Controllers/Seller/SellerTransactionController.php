<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Http\Controllers\ApiController;
use Illuminate\Validation\ValidationException;
use Response;

class SellerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return Response
     * @throws ValidationException
     */
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();

        return $this->showAll($transactions);
    }

}
