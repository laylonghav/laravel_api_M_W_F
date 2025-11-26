<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [

            [
                "name" => "Iphone",
                "description" => "Good for using.",
                "qyt" => 12,
                "status" => true,
            ],
            [
                "name" => "Oppo",
                "description" => "Good for using.",
                "qyt" => 22,
                "status" => true,
            ],
            [
                "name" => "Sumsong",
                "description" => "Good for using.",
                "qyt" => 11,
                "status" => false,
            ]

        ];

        foreach ($products as $pro) {
            Product::create($pro);
        }
    }
}
