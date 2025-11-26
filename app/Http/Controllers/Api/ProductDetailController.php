<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{

    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productDetails = ProductDetail::with("product")->get();

        // return response()->json([
        //     "data" => $productDetails,
        //     "message" => "Get Product Detail successfully !"
        // ], 200);

        return $this->sessuccApiRespone($productDetails, "Get Product Detail successfully.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "barcode" => "nullable|string|min:3|max:225",
            "make_in" => "required|string|min:3|max:225",
            "product_id" => "required|string|exists:products,_id",
            "color" => "required|string|min:3|max:225",
        ]);


        $productDetails = new ProductDetail();
        $productDetails->fill($validate);
        $productDetails->save();

        return response()->json([
            "data" => $productDetails,
            "message" => "Created data successfully !"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productDetails = ProductDetail::find($id);


        if (!$productDetails) {
            return response()->json([
                "message" => "Product detail is not found."
            ], 404);
        }

        return response()->json([
            "data" => $productDetails,
            "message" => "Get One data successfully !"
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productDetails = ProductDetail::find($id);


        if (!$productDetails) {
            return response()->json([
                "message" => "Product detail is not found."
            ], 404);
        }

        $validate = $request->validate([
            "barcode" => "required|string|min:3|max:225",
            "make_in" => "required|string|min:3|max:225",
            "product_id" => "required|string|exists:products,_id",
            "color" => "required|string|min:3|max:225",
        ]);



        $productDetails->fill($validate);
        $productDetails->save();

        return response()->json([
            "data" => $productDetails,
            "message" => "Updated data successfully !"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productDetails = ProductDetail::find($id);


        if (!$productDetails) {
            return response()->json([
                "message" => "Product detail is not found."
            ], 404);
        }
        $productDetails->delete();

        return response()->json([
            "data" => $id,
            "message" => "Deleted data successfully !"
        ], 201);
    }
}
