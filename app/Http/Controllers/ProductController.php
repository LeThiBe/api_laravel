<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Product;
use JWTAuth;

class ProductController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user =JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user
            ->products()
            ->get(['name', 'price', 'quantity'])
            ->toArray();
    }

    public function show($id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id' . $id . 'cannot be found'
            ], 400);
        }
        return $product;
    }

    public function store(ProductRequest $request)
    {
        $data = $request->only([
            'name',
            'price',
            'quantity',
        ]);

        $product = $this->user->products()->create($data);

        if ($product)
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be added'
            ], 500);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = $this->user->products()->find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id' . $id . 'cannot be found'
            ], 400);
        }
        $updated = $product->update($request->all());

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = $this->user->products()->find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id' . $id. 'cannot be found'
            ], 400);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}

/**
 * @SWG\Swagger(
 *      schemes={"http", "https"},
 *      @SWG\Info(
 *          version="1.0.0",
 *          title="L5 Swagger API",
 *          description="L5 Swagger API description",
 *          @SWG\Contact(
 *              email="darius@matulionis.lt"
 *          ),
 *      )
 *  )
 */
