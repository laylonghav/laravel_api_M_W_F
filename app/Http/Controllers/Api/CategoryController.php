<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function search(Request $req)
    {
        $query = $req->query("q");

        $category = Category::where("name", "like", "%" . $query . "%")->get();

        return response()->json([
            "Query" => $query,
            "data" => $category,
            "message" => "Get Category successfully."
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::with("product")->get();

        return response()->json([
            "data" => $category,
            "message" => "Get Category successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string|min:3|max:255",
            "status" => "required|boolean",
            "description" => "nullable|string|min:3|max:255",
        ]);

        $category = new Category();
        $category->fill($validate);
        $category->save();

        return response()->json([
            "data" => $category,
            "message" => "Created Category successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                "message" => "Category is not found."
            ], 404);
        }

        return response()->json([
            "data" => $category,
            "message" => "Get one Category successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                "message" => "Category is not found."
            ], 404);
        }


        $validate = $request->validate([
            "name" => "required|string|min:3|max:255",
            "status" => "required|boolean",
            "description" => "nullable|string|min:3|max:255",
        ]);

        $category->fill($validate);
        $category->save();

        return response()->json([
            "data" => $category,
            "message" => "Updated Category successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                "message" => "Category is not found."
            ], 404);
        }

        $category->delete();

        return response()->json([
            "data" => $category,
            "message" => "Deleted Category successfully."
        ], 200);
    }
}
