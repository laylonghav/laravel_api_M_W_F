<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{

    public function summaryPurchase()
    {
        //Get start and end of the current Month
        $startOfMonth = Carbon::now("UTC")->startOfMonth();
        $endOfMonth = Carbon::now("UTC")->endOfMonth();

        $purchasThisMonth = Purchase::query()->whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();



        //Get start and end of the current Month
        $startOfYear = Carbon::now("UTC")->startOfYear();
        $endOfYear = Carbon::now("UTC")->endOfYear();

        $summaryPurchaseByMonth = Purchase::raw(function ($collection) use ($startOfYear, $endOfYear) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'created_at' => [
                            '$gte' => new \MongoDB\BSON\UTCDateTime($startOfYear),
                            '$lte' => new \MongoDB\BSON\UTCDateTime($endOfYear),
                        ]
                    ]
                ],
                [
                    '$group' => [
                        "_id" => [
                            'month' => ['$month' => '$created_at'], // 11 ,12
                            'year' => ['$year' => '$created_at']
                        ],
                        "total" => ['$sum' => ['$toDouble' => '$paid']],
                    ]
                ],
                [
                    '$sort' => ['_id.month' => 1],
                ],
                [
                    '$project' => [
                        'title' => [
                            '$dateToString' => [
                                'format' => '%b', // Dec,Nov
                                'date' =>  [
                                    '$dateFromParts' => [
                                        'month' => '$_id.month',
                                        'year' => '$_id.year',
                                        'day' => 1,
                                    ]
                                ]
                            ]
                        ],
                        'total' => 1,
                        "_id" => 0
                    ]
                ]
            ]);
        });

        return response()->json([
            "total_purchase_this_Month" => $purchasThisMonth,
            "summary_purchase_by_month" => $summaryPurchaseByMonth
        ], 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchas = Purchase::with(["productPurchase.product", "supplier"])->get();

        return response()->json([
            "data" => $purchas,
            "message" => "Get purchase successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "supplier_id" => "required|string|exists:suppliers,_id",
            "shipping_cost" => "required|string|max:255",
            "paid" => "required|string|max:255",
            "paid_date" => "required|string|max:255",
            "product_purchase" => "required|array"
        ]);

        //Create Purchase
        $purchas = Purchase::create([
            "supplier_id" => $request->supplier_id,
            "shipping_cost" => $request->shipping_cost,
            "paid" => $request->paid,
            "paid_date" => $request->paid_date,
        ]);

        // Create Product Purchase
        foreach ($request->product_purchase as $item) {
            $purchas->productPurchase()->create([
                "product_id" => $item["product_id"],
                "cost" => $item["cost"],
                "qty" => $item["qty"],
                "retail_price" => $item["retail_price"],
                "ref" => $item["ref"],
                "remark" => $item["remark"],
            ]);
        }

        return response()->json([
            "data" => $purchas->load(["productPurchase.product", "supplier"]),
            "message" => "Purchase created successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchas = Purchase::find($id);

        if (!$purchas) {
            return response()->json([
                "message" => "Purchase is not found."
            ], 404);
        }

        return response()->json([
            "data" => $purchas,
            "message" => "Get one purchase successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchas = Purchase::find($id);

        if (!$purchas) {
            return response()->json([
                "message" => "Purchase is not found."
            ], 404);
        }


        $request->validate([
            "supplier_id" => "required|string|exists:suppliers,_id",
            "shipping_cost" => "required|string|max:255",
            "paid" => "required|string|max:255",
            "paid_date" => "required|string|max:255",
            "product_purchase" => "required|array"
        ]);

        // Update Purchase
        $purchas->update([
            "supplier_id" => $request->supplier_id,
            "shipping_cost" => $request->shipping_cost,
            "paid" => $request->paid,
            "paid_date" => $request->paid_date,
        ]);

        //Remove old Purchase product
        $purchas->productPurchase()->delete();

        // Create new Product Purchase
        foreach ($request->product_purchase as $item) {
            $purchas->productPurchase()->create([
                "product_id" => $item["product_id"],
                "cost" => $item["cost"],
                "qty" => $item["qty"],
                "retail_price" => $item["retail_price"],
                "ref" => $item["ref"],
                "remark" => $item["remark"],
            ]);
        }

        return response()->json([
            "data" => $purchas->load(["productPurchase.product", "supplier"]),
            "message" => "Purchase updated successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchas = Purchase::find($id);

        if (!$purchas) {
            return response()->json([
                "message" => "Purchase is not found."
            ], 404);
        }

        // Delete Purchase
        $purchas->delete();

        //Delete Purchase product
        $purchas->productPurchase()->delete();

        return response()->json([
            "data" => $purchas->load(["productPurchase.product", "supplier"]),
            "message" => "Purchase Deleted successfully."
        ], 200);
    }
}
