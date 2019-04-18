<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return Response
     */
    public function index(Seller $seller)
    {
        return $this->showAll($seller->products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $seller
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = 'producto1.png';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Seller $seller
     * @param Product $product
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status'   => 'in:' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
            'image'    => 'image'
        ];

        $this->validate($request, $rules);

        $this->verifySeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->estaDisponible() && $product->categories()->count() == 0) {
                return $this->errorResponse('Un producto activo debe tener al menos una categoría', 409);
            }
        }

        if ($product->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Seller $seller
     * @param Product $product
     * @return void
     * @throws Exception
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);
        $product->delete();
        return $this->showOne($product);
    }

    /**
     * @param Seller $seller
     * @param Product $product
     */
    protected function verifySeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto.');
        }
    }
}
