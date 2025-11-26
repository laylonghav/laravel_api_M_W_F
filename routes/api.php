<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductDetailController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

Route::prefix("auth")->middleware(["auth:sanctum", "admin"])->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/me", [AuthController::class, "me"]);
});


Route::prefix("product")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/search", [ProductController::class, "search"]);
    Route::get("/", [ProductController::class, "index"]);
    Route::get("/{id}", [ProductController::class, "show"]);
    Route::post("/", [ProductController::class, "store"]);
    Route::put("/{id}", [ProductController::class, "update"]);
    Route::delete("/{id}", [ProductController::class, "destroy"]);
});

Route::prefix("productdetail")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/", [ProductDetailController::class, "index"]);
    Route::get("/{id}", [ProductDetailController::class, "show"]);
    Route::post("/", [ProductDetailController::class, "store"]);
    Route::put("/{id}", [ProductDetailController::class, "update"]);
    Route::delete("/{id}", [ProductDetailController::class, "destroy"]);
});

Route::prefix("category")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/search", [CategoryController::class, "search"]);
    Route::get("/", [CategoryController::class, "index"]);
    Route::get("/{id}", [CategoryController::class, "show"]);
    Route::post("/", [CategoryController::class, "store"]);
    Route::put("/{id}", [CategoryController::class, "update"]);
    Route::delete("/{id}", [CategoryController::class, "destroy"]);
});


// Route::apiResource("brand", BrandController::class);
Route::prefix("brand")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/search", [BrandController::class, "search"]);
    Route::get("/", [BrandController::class, "index"]);
    Route::get("/{id}", [BrandController::class, "show"]);
    Route::post("/", [BrandController::class, "store"]);
    Route::put("/{id}", [BrandController::class, "update"]);
    Route::delete("/{id}", [BrandController::class, "destroy"]);
});


// Route::apiResource("brand", BrandController::class);
Route::prefix("order")->middleware(["auth:sanctum"])->group(function () {
    // Route::get("/search", [OrderController::class, "search"]);
    Route::get("/getsale", [OrderController::class, "getSale"]);
    Route::get("/", [OrderController::class, "index"]);
    Route::get("/{id}", [OrderController::class, "show"]);
    Route::post("/", [OrderController::class, "store"]);
    Route::put("/{id}", [OrderController::class, "update"]);
    Route::delete("/{id}", [OrderController::class, "destroy"]);
});

Route::prefix("supplier")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/search", [SupplierController::class, "search"]);
    Route::get("/", [SupplierController::class, "index"]);
    Route::get("/{id}", [SupplierController::class, "show"]);
    Route::post("/", [SupplierController::class, "store"]);
    Route::post("/bulk", [SupplierController::class, "Bulkstore"]);
    Route::put("/{id}", [SupplierController::class, "update"]);
    Route::delete("/{id}", [SupplierController::class, "destroy"]);
});

// Route::apiResource("purchase", PurchaseController::class);
Route::prefix("purchase")->middleware(["auth:sanctum"])->group(function () {
    Route::get("/summarypurchase", [PurchaseController::class, "summaryPurchase"]);
    Route::get("/", [PurchaseController::class, "index"]);
    Route::get("/{id}", [PurchaseController::class, "show"]);
    Route::post("/", [PurchaseController::class, "store"]);
    Route::post("/bulk", [PurchaseController::class, "Bulkstore"]);
    Route::put("/{id}", [PurchaseController::class, "update"]);
    Route::delete("/{id}", [PurchaseController::class, "destroy"]);
});
