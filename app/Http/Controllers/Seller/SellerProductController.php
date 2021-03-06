<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transformers\ProductTransformer;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Response;
use Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . ProductTransformer::class)->only(['store', 'update']);
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
        $data['image'] = $request->image->store('', Product::DISK_STORAGE);
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

        if ($request->hasFile('image')) {
            Storage::disk(Product::DISK_STORAGE)->delete($product->image);
            $product->image = $request->image->store('', Product::DISK_STORAGE);
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
     * @return Response
     * @throws Exception
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);
        Storage::disk(Product::DISK_STORAGE)->delete($product->image);
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
