<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends     Controller
{
    public function getSale()
    {
        //Get start and end of the current Month
        $startOfMonth = Carbon::now("UTC")->startOfMonth();
        $endOfMonth = Carbon::now("UTC")->endOfMonth();

        $totalSaleThisMonth = Order::raw(function ($collection) use ($startOfMonth, $endOfMonth) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'created_at' => [
                            '$gte' => new \MongoDB\BSON\UTCDateTime($startOfMonth),
                            '$lte' => new \MongoDB\BSON\UTCDateTime($endOfMonth),
                        ]
                    ]
                ],
                [
                    '$group' => [
                        "_id" => null,
                        "total" => ['$sum' => ['$toDouble' => '$total_amount']],
                        "total_order" => ['$sum' => 1]
                    ]
                ]
            ]);
        });

        $totalSale = isset($totalSaleThisMonth[0]) ? $totalSaleThisMonth[0]->total : 0;
        $totalOrder = isset($totalSaleThisMonth[0]) ? $totalSaleThisMonth[0]->total_order : 0;

        //Get start and end of the current Month
        $startOfYear = Carbon::now("UTC")->startOfYear();
        $endOfYear = Carbon::now("UTC")->endOfYear();

        $summarySaleByMonth = Order::raw(function ($collection) use ($startOfYear, $endOfYear) {
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
                        "total" => ['$sum' => ['$toDouble' => '$total_amount']],
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
            "total_sale_this_Month" => [
                "total" => $totalSale,
                "total_order" => $totalOrder,
            ],
            "summary_sale_by_month" => $summarySaleByMonth
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with("orderDetail")->get();

        return response()->json([
            "data" => $orders,
            "message" => "Get order successfully."
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "paid_amount" => "required|string",
            "total_amount" => "required|string",
            "payment_method" => "required|string",
            "detail" => "required|array",
            "detail.*.price" => "required",
            "detail.*.qty" => "required",
            "detail.*.discount" => "required",
            "detail.*.total" => "required",
            "detail.*.product_id" => "required",
        ]);

        // "ORD00001"

        $lastOrder = Order::orderBy("_id", "desc")->first();

        if ($lastOrder) {
            $lastNumber = substr($lastOrder->order_no, 3);
            $order_no = "ORD" . str_pad($lastNumber + 1, 5, "0", STR_PAD_LEFT);
        } else {
            $order_no = "ORD00001";
        }

        $order = Order::create([
            "order_no" => $order_no,
            "paid_amount" => $request->paid_amount,
            "total_amount" => $request->total_amount,
            "payment_method" => $request->payment_method,
        ]);

        if ($order) {
            foreach ($request->detail as $item) {
                OrderDetail::create([
                    "price" => $item["price"],
                    "qty" => $item["qty"],
                    "discount" => $item["discount"],
                    "total" => $item["total"],
                    "product_id" => $item["product_id"],
                    "order_id" => $order->_id,
                ]);

                $product = Product::find($item["product_id"]);

                $currnetQty = (int) $product->qty;
                $orderQty = (int) $item["qty"];

                $newQty = max(0, $currnetQty - $orderQty);
                $product->update(["qty" => $newQty]);
            }
        }

        return response()->json([
            "data" => $order->load("orderDetail"),
            "message" => "Created order successfully."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                "message" => "Order is not found."
            ], 404);
        }

        return response()->json([
            "data" => $order->load("orderDetail"),
            "message" => "Get one order successfully."
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                "message" => "Order is not found."
            ], 404);
        }

        $order->update($request->only(["paid_amount", "total_amount", "payment_method", "order_no"]));

        return response()->json([
            "data" => $order->load("orderDetail"),
            "message" => "Updated order successfully."
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                "message" => "Order is not found."
            ], 404);
        }

        $order->delete();

        return response()->json([
            "data" => $order->load("orderDetail"),
            "message" => "Deleted order successfully."
        ], 200);
    }
}
