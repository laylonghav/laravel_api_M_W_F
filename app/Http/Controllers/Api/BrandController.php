<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function search(Request $req)
    {
        $query = $req->query("q");

        $brand = Brand::where("name", "like", "%" . $query . "%")->with("product.category")->get();

        return response()->json([
            "Query" => $query,
            "data" => $brand,
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::with('product.category')->get();

        return response()->json([
            "data" => $brands,
            "messsage" => "Get Brand successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $validate = $request->validated();

        $brand = Brand::create($validate);

        return response()->json([
            "data" => $brand,
            "messsage" => "Created Brand successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::where("_id", $id)->get();

        if (!$brand) {
            return response()->json([
                "messsage" => "Brand is not found."
            ], 404);
        }

        return response()->json([
            "data" => $brand->load("product"),
            "messsage" => "Get one Brand successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                "messsage" => "Brand is not found."
            ], 404);
        }

        $validate = $request->validated();

        $brand->update($validate);

        return response()->json([
            "data" => $brand,
            "messsage" => "Updated Brand successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                "messsage" => "Brand is not found."
            ], 404);
        }


        $brand->delete();

        return response()->json([
            "data" => $brand,
            "messsage" => "Deleted Brand successfully."
        ], 200);
    }
}
