<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function search(Request $req)
    {
        $query = $req->query("q");

        $supplier = Supplier::where("name", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query" => $query,
            "data" => $supplier,
            "message" => "Get supplier successfully."
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::query()->get();

        return response()->json([
            "data" => $supplier,
            "message" => "Get supplier successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string|min:3|max:255",
            "email" => "required|email|unique:suppliers,email",
            "address" => "required|string|min:3|max:255",
            "website" => "required|url|max:255",
        ]);

        $supplier = new Supplier();
        $supplier->fill($validate);
        $supplier->save();

        return response()->json([
            "data" => $supplier,
            "message" => "Created supplier successfully."
        ], 201);
    }

    public function Bulkstore(Request $req)
    {
        $validate = $req->validate([
            "supplier" => "required|array",
            "supplier.*.name" => "required",
            "supplier.*.address" => "required",
            "supplier.*.email" => "required",
            "supplier.*.website" => "required",
        ]);

        $supplier = [];

        foreach ($validate["supplier"] as $item) {
            $supplier[] = Supplier::create($item);
        }

        return response()->json([
            "data" => $supplier,
            "message" => "Created Bulk supplier successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found."
            ], 404);
        }

        return response()->json([
            "data" => $supplier,
            "message" => "Get one supplier successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found."
            ], 404);
        }


        $validate = $request->validate([
            "name" => "required|string|min:3|max:255",
            "email" => "required|email|unique:suppliers,email",
            "address" => "required|string|min:3|max:255",
            "website" => "required|url|max:255",
        ]);

        $supplier->fill($validate);
        $supplier->save();

        return response()->json([
            "data" => $supplier,
            "message" => "Updated supplier successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                "message" => "supplier is not found."
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            "data" => $supplier,
            "message" => "Deleted supplier successfully."
        ], 200);
    }
}
