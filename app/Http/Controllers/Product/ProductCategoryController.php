<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Response;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('client.credentials')->only(['index']);
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
        return $this->showAll($product->categories);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param Category $category
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, Product $product, Category $category)
    {
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * @param Product $product
     * @param Category $category
     * @return Response
     * @throws ValidationException
     */
    public function destroy(Product $product, Category $category) {

        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('La categoría especificada no es una categoría de este producto', 404);
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);

    }

}
